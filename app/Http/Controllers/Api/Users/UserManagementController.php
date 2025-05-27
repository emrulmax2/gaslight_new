<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserStoreRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\PricingPackage;
use App\Models\User;
use App\Models\UserPricingPackage;
use App\Models\UserPricingPackageInvoice;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Schema;
use Exception;

class UserManagementController extends Controller
{
    public function store(UserStoreRequest $request)
    {
       $package = PricingPackage::find($request->pricing_package_id);
        $hashPassword = Hash::make($request->input('password'));

        $name = $request->input('name');
        $email = $request->input('email');

        $user = User::create([
            'parent_id' => Auth::user()->id,
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
        $user->companies()->attach(Auth::user()->company->id);
        $user->save();

        if($user->id):
            $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
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
                    
                    return response()->json(['message' => 'User successfully created.', 'red' => ''], 200);
                }catch(Exception $e){
                    $deleted = $stripe->customers->delete($customer->id, []);

                    UserPricingPackage::where('user_id', $user->id)->forceDelete();
                    UserPricingPackageInvoice::where('user_id', $user->id)->forceDelete();
                    $user->companies()->detach(Auth::user()->company->id);
                    $user->forceDelete();

                    $message = $e->getMessage();
                    return response()->json(['message' => 'Can not create the user due to payment failure.', 'red' => ''], 304);
                }
            }catch(Exception $e){
                UserPricingPackage::where('user_id', $user->id)->forceDelete();
                UserPricingPackageInvoice::where('user_id', $user->id)->forceDelete();
                $user->companies()->detach(Auth::user()->company->id);
                $user->forceDelete();

                $message = $e->getMessage();
                return response()->json(['message' => 'Somthing went wrong. Please try again later.', 'red' => ''], 304);
            }
        else:
            return response()->json(['message' => 'Somthing went wrong. Please try again later.', 'red' => ''], 304);
        endif;
    }

        public function sendMail($emails, $subject, $content, $attachments = []){
        $configuration = [
            'smtp_host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'smtp_port' => env('MAIL_PORT', '587'),
            'smtp_username' => env('MAIL_USERNAME', 'no-reply@lcc.ac.uk'),
            'smtp_password' => env('MAIL_PASSWORD', 'PASSWORD'),
            'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'),
            
            'from_email'    => env('MAIL_FROM_ADDRESS', 'no-reply@lcc.ac.uk'),
            'from_name'    =>  env('MAIL_FROM_NAME', 'Gas Safe Engineer'),

        ];

        try{
            GCEMailerJob::dispatch($configuration, $emails, new GCESendMail($subject, $content, $attachments));
            $message = 'Mail Success';
        }catch(Exception $e){
            $message = 'Mail Error';
        }
        return $message;
    }



    public function list(Request $request)
    {
        $user = $request->user();
        $user_company_id = (isset($user->companies[0]->id) && $user->companies[0]->id > 0) ? $user->companies[0]->id : 0;
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'id';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? $request->query('order') : 'DESC';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';
        $searchableColumns = Schema::getColumnListing((new User)->getTable());

        
        $query = User::leftJoin('company_staff', 'users.id', '=', 'company_staff.user_id')
        ->leftJoin('companies', 'company_staff.company_id', '=', 'companies.id')
        ->select('users.*', 'companies.company_name as company_name', 'companies.id as company_id')
        ->where('company_staff.company_id', $user_company_id)
        ->whereNot('users.id', $user->id)
        ->orderBy($sortField, $sortOrder);
        
        if (!empty($searchKey)) {
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'LIKE', '%' . $searchKey . '%');
                }
            });
        }

        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);
        
        $users = $query->paginate($limit, ['*'], 'page', $page);

        $transformedData = $users->getCollection()->map(function($user) {
            $userPackage = UserPricingPackage::with('package')->where('user_id', $user->id)->orderBy('id', 'DESC')->first();
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'company_name' => $user->company_name,
                'company_id' => $user->company_id,
                'package' => $userPackage ? [
                    'title' => $userPackage->package->title ?? 'N/A',
                    'active' => $userPackage->active ?? 0,
                    'cancellation_requested' => $userPackage->cancellation_requested ?? 0,
                    'end_date' => isset($userPackage->end) ? $userPackage->end : null,
                    'status' => $this->getPackageStatus($userPackage)
                ] : null
            ];
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
            $userCompanyId = $request->user()->companies->first()->id ?? 0;
        
            $user = User::with(['companies' => function($query) use ($userCompanyId) {
                    $query->where('company_id', $userCompanyId)
                          ->select('companies.id', 'company_name');
                }])
                ->whereHas('companies', function($query) use ($userCompanyId) {
                    $query->where('company_id', $userCompanyId);
                })
                ->findOrFail($id);

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
