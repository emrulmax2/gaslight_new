<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserStoreRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\Company;
use App\Models\PricingPackage;
use App\Models\User;
use App\Models\UserPricingPackage;
use App\Models\UserPricingPackageInvoice;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
   public function store(UserStoreRequest $request)
    {
        $package = PricingPackage::find($request->pricing_package_id);
        $hashPassword = Hash::make($request->input('password'));

        $name = $request->input('name');
        $email = $request->input('email');

        $user = User::create([
            'parent_id' => $request->user()->id,
            'role' => 'staff',
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $hashPassword, 
            'gas_safe_id_card' => $request->input('gas_safe_id_card'),
            'oil_registration_number' => $request->input('oil_registration_number'),
            'installer_ref_no' => $request->input('installer_ref_no'),
            'active' => 1,
            'first_login' => 1
        ]);
        $user->companies()->attach($request->user()->company->id);
        $user->save();

        if($user->id):
            if($request->input('photo') !== null):
                $base64Image = $request->input('photo');
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)):
                    $fileType = strtolower($type[1]); // jpg, png, jpeg
                    $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
                    if (!empty($fileType) && in_array($fileType, ['jpg', 'jpeg', 'png'])):
                        $imageData = base64_decode($base64Image);
                        if($imageData):
                            $fileName = $user->id.'_Photo_'.time().'.'.$fileType;
                            $filePath = 'users/'.$user->id.'/'.$fileName;
                            Storage::disk('public')->put($filePath, $imageData);
                            
                            User::where('id', $user->id)->update(['photo' => $fileName]);
                        endif;
                    endif;
                endif;
            endif;
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            try{
                $customer = $stripe->customers->create([
                    'name' => $name,
                    'email' => $email,
                    'payment_method' => $request->token,
                    'invoice_settings' => [
                        'default_payment_method' => $request->token
                    ]
                ]);

                try{
                    $subscription = $stripe->subscriptions->create([
                        'customer' => $customer->id,
                        'items' => [
                            ['price' => $package->stripe_plan]
                        ],
                        'currency' => 'GBP',
                        'default_payment_method' => $request->token,
                        'metadata' => [
                            'billed_to' => $request->card_holder_name,
                            'user_id' => $user->id
                        ],
                        'payment_behavior' => 'allow_incomplete'
                    ]);

                    $userPackage = UserPricingPackage::create([
                        'user_id' => $user->id,
                        'pricing_package_id' => $request->pricing_package_id,
                        'stripe_customer_id' => $customer->id,
                        'stripe_subscription_id' => $subscription->id,
                        'start' => date('Y-m-d', $subscription->current_period_start),
                        'end' => date('Y-m-d', $subscription->current_period_end),
                        'price' => $package->price,
                        'active' => ($subscription->status && $subscription->status == 'active' ? 1 : 0),
                        
                        'created_by' => $request->user()->id
                    ]);

                    if($userPackage->id):
                        $invoice = UserPricingPackageInvoice::create([
                            'user_id' => $user->id,
                            'user_pricing_package_id' => $userPackage->id,
                            'invoice_id' => $subscription->latest_invoice,
                            'start' => date('Y-m-d', $subscription->current_period_start),
                            'end' => date('Y-m-d', $subscription->current_period_end),
                            'status' => (isset($subscription->status) && !empty($subscription->status) ? $subscription->status : null),
                            
                            'created_by' => $request->user()->id,
                        ]);
                    endif;

                    

                    $subject = 'Welcome Message from Gas Safety Engineer';

                    $content = 'Hi '.$name.',<br/><br/>';
                    $content .= '<p>Welcome to the Gas Safety Engineer APP. You are successfully registered. Here hes your login details:</p>';
                    $content .= '<p>';
                        $content .= 'Email: <strong>'.$email.'</strong><br/>';
                        $content .= 'Temporary Password: <strong>'.$request->input('password').'</strong>';
                    $content .= '</p>';
                    $content .= '<p>Subscription Details</p>';
                    $content .= '<p>';
                        $content .= 'Package: <strong>'.$package->title.'</strong><br/>';
                        $content .= 'Amount Charged: <strong>'.Number::currency($package->price, 'GBP').'</strong><br/>';
                        $content .= 'Start From: <strong>'.date('jS F, Y', $subscription->current_period_start).'</strong><br/>';
                        $content .= 'End To: <strong>'.date('jS F, Y', $subscription->current_period_end).'</strong><br/>';
                    $content .= '</p>';

                    $content .= 'Thanks & Regards<br/>';
                    $content .= 'Gas Safety Engineer';

                    $theMail = $this->sendMail([$email], $subject, $content);
                    
                    return response()->json(['message' => 'User successfully created.'], 200);
                }catch(Exception $e){
                    $deleted = $stripe->customers->delete($customer->id, []);

                    UserPricingPackage::where('user_id', $user->id)->forceDelete();
                    UserPricingPackageInvoice::where('user_id', $user->id)->forceDelete();
                    $user->companies()->detach(Auth::user()->company->id);
                    $user->forceDelete();

                    $message = $e->getMessage();
                    return response()->json(['message' => $message], 500);
                }
            }catch(Exception $e){
                UserPricingPackage::where('user_id', $user->id)->forceDelete();
                UserPricingPackageInvoice::where('user_id', $user->id)->forceDelete();
                $user->companies()->detach(Auth::user()->company->id);
                $user->forceDelete();

                 return response()->json([
                    'message' => 'Payment failed: ' . $e->getMessage(),
                    'error_details' => $e
                ], 500);
            }
        else:
            return response()->json(['message' => 'Somthing went wrong. Please try again later.'], 500);
        endif;
    }



   public function list(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'sort' => 'sometimes|string|in:users.id,users.name,users.email,companies.company_name',
                'order' => 'sometimes|string|in:ASC,DESC',
                'search' => 'sometimes|string|max:255',
                'limit' => 'sometimes|integer|min:1|max:100',
                'page' => 'sometimes|integer|min:1'
            ]);

            $user = User::findOrFail($request->query('user_id'));
            
            if ($user->companies->isEmpty()) {
                return response()->json([
                    'error' => 'User has no associated companies',
                    'data' => [],
                    'meta' => [
                        'total' => 0,
                        'per_page' => 10,
                        'current_page' => 1,
                        'last_page' => 1,
                        'from' => null,
                        'to' => null,
                    ]
                ], 200);
            }

            $userCompany = $user->companies->first();
            $userCompanyId = $userCompany->id;

            $sortField = $request->query('sort', 'users.id');
            $sortOrder = $request->query('order', 'DESC');
            $searchKey = $request->query('search', '');

            $allowedSortFields = ['users.id', 'users.name', 'users.email', 'companies.company_name'];
            $safeSortField = in_array($sortField, $allowedSortFields) ? $sortField : 'users.id';
            
            $safeSortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

            $query = User::with(['companies'])
                ->whereHas('companies', function($query) use ($userCompanyId) {
                    $query->where('companies.id', $userCompanyId);
                })
                ->where('users.id', '!=', $user->id)
                ->orderBy($safeSortField, $safeSortOrder);

            if (!empty($searchKey)) {
                $query->where(function($q) use ($searchKey) {
                    $q->where('users.name', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('users.email', 'LIKE', '%' . $searchKey . '%');
                    
                    $q->orWhereHas('companies', function($companyQuery) use ($searchKey) {
                        $companyQuery->where('companies.company_name', 'LIKE', '%' . $searchKey . '%')
                                    ->orWhere('companies.company_email', 'LIKE', '%' . $searchKey . '%')
                                    ->orWhere('companies.company_phone', 'LIKE', '%' . $searchKey . '%');
                    });
                });
            }

            $limit = max(1, min(100, (int)$request->query('limit', 10)));
            $page = max(1, (int)$request->query('page', 1));
            
            $users = $query->paginate($limit, ['users.*'], 'page', $page);

            $transformedData = $users->getCollection()->map(function($user) {
                try {
                    $userPackage = UserPricingPackage::with('package')
                        ->where('user_id', $user->id)
                        ->latest()
                        ->first();

                    $company = $user->companies->first();

                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'company' => $company ? $company->toArray() : null,
                        'package' => $userPackage ? [
                            'title' => $userPackage->package->title ?? 'N/A',
                            'active' => $userPackage->active ?? 0,
                            'cancellation_requested' => $userPackage->cancellation_requested ?? 0,
                            'end_date' => $userPackage->end ?? null,
                            'status' => $this->getPackageStatus($userPackage)
                        ] : null
                    ];
                } catch (\Exception $e) {
                    Log::warning('Error transforming user data', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                    
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'company' => null,
                        'package' => null,
                        'error' => 'Failed to load complete user data'
                    ];
                }
            });

            return response()->json([
                'data' => $transformedData,
                'meta' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Invalid query params',
                'messages' => $e->errors()
            ], 422);
            
        }catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error in user list method: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Database error occurred',
                'message' => config('app.debug') ? $e->getMessage() : 'Please try again later'
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('Unexpected error in user list method: ' . $e->getMessage());
            return response()->json([
                'error' => 'An unexpected error occurred',
                'message' => config('app.debug') ? $e->getMessage() : 'Please try again later'
            ], 500);
        }
    }

    private function getPackageStatus($userPackage)
    {
        if (!$userPackage) return 'N/A';
        
        if (isset($userPackage->active) && $userPackage->active == 1) {
            if (isset($userPackage->cancellation_requested) && $userPackage->cancellation_requested == 1) {
                return 'Active until ' . (isset($userPackage->end) ? date('d F', strtotime($userPackage->end)) : 'N/A');
            }
            return 'Active';
        }
        return 'Inactive';
    }
    
    public function getSingleUser(Request $request, $id)
    {
        try {
            $user = User::with('companies')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'The requested user does not exist'
                ]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Something went wrong. Please try again later or contact with the administrator'
                ]
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function getSignature(Request $request)
    {

        $user = $request->user();

        $request->user()->load(['signature' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $signature = $user->signature ? Storage::disk('public')->url($user->signature->filename) : '';

        return response()->json([
            'success' => true,
            'data' => [
                "path" => $signature
            ]
        ], 200);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        
        try {
            $userCompanyId = $request->user()->companies->first()->id ?? 0;
        
            $user = User::whereHas('companies', function($query) use ($userCompanyId) {
                    $query->where('company_id', $userCompanyId);
                })
                ->findOrFail($id);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'gas_safe_id_card' => $request->gas_safe_id_card,
            'oil_registration_number' => $request->oil_registration_number,
            'installer_ref_no' => $request->installer_ref_no,
        ];

        if($request->password !== null) {
            $hashPassword = Hash::make($request->password);
            $updateData['password'] = $hashPassword;
        }

        $user->update($updateData);

        return response()-> json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'The requested user does not exist'
                ]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Something went wrong. Please try again later or contact with the administrator'
                ]
            ], 500);
        }
        
    
    }

    public function destroy(Request $request, $id)
    {
        try {
            $userCompanyId = $request->user()->companies->first()->id ?? 0;
        
            $user = User::whereHas('companies', function($query) use ($userCompanyId) {
                    $query->where('company_id', $userCompanyId);
                })
                ->findOrFail($id);
    
            $user->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'The requested user does not exist or already deleted'
                ]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Something went wrong. Please try again later or contact with the administrator'
                ]
            ], 500);
        }
       
    }
}
