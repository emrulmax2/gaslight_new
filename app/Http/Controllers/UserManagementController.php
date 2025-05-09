<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;
use App\Models\FileRecord;
use App\Models\PricingPackage;
use App\Models\UserPricingPackage;
use App\Models\UserPricingPackageInvoice;
use Creagia\LaravelSignPad\Signature;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Number;
use Psy\Readline\Hoa\Console;
use Stripe;

class UserManagementController extends Controller
{

    public function index()
    {
        
        return view('app.users.index', [
            'title' => 'Users List - Gas Certificate App',
            'users' => User::all(),
            'packages' => PricingPackage::whereNot('period', 'Free Trail')->where('active', 1)->orderBy('order', 'ASC')->get()
        ]);
    }


    public function store(UserStoreRequest $request){
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
            'fast_login' => 0
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
                            ['price' => 'price_1RKzh62cOx8B9eTd2jYERHnY']
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
                        
                        'created_by' => auth()->user()->id
                    ]);

                    if($userPackage->id):
                        $invoice = UserPricingPackageInvoice::create([
                            'user_id' => $user->id,
                            'user_pricing_package_id' => $userPackage->id,
                            'invoice_id' => $subscription->latest_invoice,
                            'start' => date('Y-m-d', $subscription->current_period_start),
                            'end' => date('Y-m-d', $subscription->current_period_end),
                            'status' => (isset($subscription->status) && !empty($subscription->status) ? $subscription->status : null),
                            
                            'created_by' => auth()->user()->id,
                        ]);
                    endif;
                    return response()->json(['message' => 'User successfully created.', 'red' => ''], 200);
                }catch(Exception $e){
                    $user->companies()->detach(Auth::user()->company->id);
                    $user->forceDelete();

                    $message = $e->getMessage();
                    return response()->json(['message' => 'Can not create the user due to payment failure.', 'red' => ''], 304);
                }
            }catch(Exception $e){
                $user->companies()->detach(Auth::user()->company->id);
                $user->forceDelete();

                $message = $e->getMessage();
                return response()->json(['message' => 'Somthing went wrong. Please try again later.', 'red' => ''], 304);
            }
        else:
            return response()->json(['message' => 'Somthing went wrong. Please try again later.', 'red' => ''], 304);
        endif;
    }


    public function drawSignatureStore(Request $request)
    {
  
        $user = User::find($request->edit_id);

        $existingSignature = Signature::where('model_type', User::class)
            ->where('model_id', $user->id)
            ->first();

        if($request->sign !== null) {

            if ($existingSignature) {
                $filePath = storage_path('app/public/' . $existingSignature->filename);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $existingSignature->delete();
            }

            $signatureData = str_replace('data:image/png;base64,', '', $request->sign);
            $signatureData = base64_decode($signatureData);
            if(strlen($signatureData) > 2621) {
                $imageName = 'signatures/' . Str::uuid() . '.png';
                Storage::disk('public')->put($imageName, $signatureData);
                $signature = new Signature();
                $signature->model_type = User::class;
                $signature->model_id = $user->id;
                $signature->uuid = Str::uuid();
                $signature->filename = $imageName;
                $signature->document_filename = null;
                $signature->certified = false;
                $signature->from_ips = json_encode([request()->ip()]);
                $signature->save();
                return response()->json(['message' => 'Signature created successfully', 'red' => ''], 201);
            }
        }
        return response()->json(['message' => 'Signature could not be created', 'red' => ''], 400);
    }


    public function fileUploadStore(Request $request)
    {

        $user = User::find($request->id);
    
        if ($request->input('file_id') !== null) {
            $fileRecord = FileRecord::find($request->input('file_id'));
    
            if ($fileRecord) {
                $newFilePath = 'signatures/' . Str::uuid() . '.' . pathinfo($fileRecord->name, PATHINFO_EXTENSION);
                Storage::disk('public')->move($fileRecord->path, $newFilePath);
    
                $signature = new Signature();
                $signature->model_type = User::class;
                $signature->model_id = $user->id;
                $signature->uuid = Str::uuid();
                $signature->filename = $newFilePath;
                $signature->document_filename = null;
                $signature->certified = false;
                $signature->from_ips = json_encode([request()->ip()]);
                $signature->save();
    
                return response()->json(['message' => 'Signature uploaded successfully'], 201);
            }
        }
        return response()->json(['message' => 'Signature could not be uploaded'], 400);
    }
    
    public function show(User $user)
    {
        return view('app.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load(['signature' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $signature = $user->signature ? Storage::disk('public')->url($user->signature->filename) : '';

        return view('app.users.edit', [
            'user' => $user,
            'signature' => $signature,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, $user_id)
    {
        $user = User::FindOrFail($user_id);

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

        return response()->json(['message' => 'User updated successfully'], 200);
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $user = User::withTrashed()->find($id);
        $user->restore();
        return redirect()->route('users.index');
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $user_company_id = (isset($user->companies[0]->id) && $user->companies[0]->id > 0 ? $user->companies[0]->id : 0);

        $querystr = isset($request->querystr) ? $request->querystr : '';

        
        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = User::leftJoin('company_staff', 'users.id', '=', 'company_staff.user_id')
                ->leftJoin('companies', 'company_staff.company_id', '=', 'companies.id')
                ->select('users.*', 'companies.company_name as company_name','companies.id as company_id')
                ->where('company_staff.company_id', $user_company_id)
                ->whereNot('users.id', $user->id)
                ->orderByRaw(implode(',', $sorts));

        if (!empty($querystr)):
            $query->where(function($q) use($querystr){
                $q->where('name', 'LIKE', '%' . $querystr . '%')->orWhere('email', 'LIKE', '%' . $querystr . '%')
                    ->where('gas_safe_id_card', 'LIKE', '%' . $querystr . '%')->where('oil_registration_number', 'LIKE', '%' . $querystr . '%')
                    ->where('installer_ref_no', 'LIKE', '%' . $querystr . '%');
            });
        endif;

        $Query= $query->get();
        $html = '';

        if(!empty($Query)):
            $i = 1;
            foreach($Query as $list):
                $userPackage = UserPricingPackage::with('package')->where('user_id', $list->id)->orderBy('id', 'DESC')->get()->first();
                $html .= '<div data-id="'.$list->id.'" class="userWrap px-0 py-4 border-b border-b-slate-100 flex items-center">';
                    $html .= '<div class="mr-auto">';
                        $html .= '<div class=" text-slate-500 text-xs leading-none mb-2">'.(isset($userPackage->package->title) ? $userPackage->package->title : 'N/A').'</div>';
                        $html .= '<div class="font-medium text-dark leading-none mb-2">'.$list->name.'</div>';
                        $html .= '<div class=" text-slate-500 text-xs leading-none mb-4"> Renews '.(isset($userPackage->end) && !empty($userPackage->end) ? date('d F, Y', strtotime($userPackage->end)) : 'N/A').'</div>';
                        $html .= (isset($userPackage->active) && $userPackage->active == 1 ? '<span class="text-xs bg-success-40 text-dark leading-none font-medium px-2 py-0.5">Active</span>' : '<span class="text-xs bg-danger-40 text-dark leading-none font-medium px-2 py-0.5">Inactive</span>');
                    $html .= '</div>';
                    $html .= '<div class="ml-auto">';
                        $html .= '<a href="'.route('users.navigations', $list->id).'" class="text-slate-600"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 lucide lucide-ellipsis-vertical-icon lucide-ellipsis-vertical"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg></a>';
                    $html .= '</div>';
                $html .= '</div>';
                $i++;
                
            endforeach;
        else:
            $html .= '<div role="alert" class="alert relative border rounded-md px-5 py-4 bg-pending border-pending bg-opacity-20 border-opacity-5 text-pending dark:border-pending dark:border-opacity-20 mb-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg>
                        No match found.
                    </div>';
        endif;
        return response()->json(['html' => $html], 200); 
    }

    public function navigations(User $user){
        return view('app.users.navigations', [
            'title' => 'User Navigation - Gas Certificate App',
            'user' => $user
        ]);
    }

    public function userPlans(User $user){
        $user->load('userpackage');
        return view('app.users.plans', [
            'title' => 'User Plans - Gas Certificate App',
            'user' => $user,
            'packages' => PricingPackage::whereNot('period', 'Free Trail')->where('active', 1)->orderBy('order', 'ASC')->get()
        ]);
    }

    public function cancelSubscription(Request $request){
        $currentUser = User::find(Auth::user()->id);
        $user_id = $request->user_id;
        $userPackage = UserPricingPackage::where('user_id', $user_id)->orderBy('id', 'DESC')->get()->first();
        $userInvoice = UserPricingPackageInvoice::where('user_id', $user_id)->where('user_pricing_package_id', $userPackage->id)->orderBy('id', 'DESC')->get()->first();
        
        $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
        try{
            $subscription = $stripe->subscriptions->cancel($userPackage->stripe_subscription_id, [
                'cancellation_details' => [
                    'comment' => 'Canceled by '.$currentUser->name.' at '.date('Y-m-d H:i:s').'.'
                ],
                // 'invoice_now' => true,
                // 'prorate' => true
            ]);
            // $userPackage->update(['active' => 0, 'updated_by' => Auth::user()->id]);
            // if(isset($userInvoice->id) && $userInvoice->id > 0):
            //     $userInvoice->update(['status' => 'canceled', 'updated_by' => Auth::user()->id]);
            // endif;

            return response()->json(['message' => 'Subscription successfully cancelled.', 'red' => route('users.index')], 200);
        }catch(Exception $e){
            $message = $e->getMessage();
            return response()->json(['message' => 'Can not canceled the subscription due to unexpected errors.', 'red' => ''], 422);
        }
    }

    public function paymentHistory(User $user){
        $user->load('userpackage');
        return view('app.users.payment-history', [
            'title' => 'User Plans - Gas Certificate App',
            'user' => $user,
            'packages' => PricingPackage::whereNot('period', 'Free Trail')->where('active', 1)->orderBy('order', 'ASC')->get()
        ]);
    }
}
