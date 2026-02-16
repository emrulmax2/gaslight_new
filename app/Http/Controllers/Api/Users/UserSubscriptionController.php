<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpgradeSubscriptionRequest;
use App\Models\PricingPackage;
use App\Models\User;
use App\Models\UserPricingPackage;
use App\Models\UserPricingPackageInvoice;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Number;
use App\Services\SubscriptionService;

class UserSubscriptionController extends Controller
{protected $subscriptionService;
    public function __construct(SubscriptionService $subscriptionService){
        $this->subscriptionService = $subscriptionService;
    }

    public function paymentHistory(Request $request, $id)
    {

        $user_id = $id;
        $query = UserPricingPackage::with('user', 'package');
        $status = ($request->has('status') && ($request->query('status') != '')) ? $request->query('status') : 1;
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'id';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? $request->query('order') : 'DESC';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';
        $searchableColumns = Schema::getColumnListing((new UserPricingPackage)->getTable());

        if ($user_id > 0):
             $query->where('user_id', $user_id); 
        endif;

        if ($status == 2):
            $query->onlyTrashed();
        else:
            $query->where('active', $status);
        endif;

        if (!empty($searchKey)) {
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'LIKE', '%' . $searchKey . '%');
                }
            });
        }

        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'asc';
        $query->orderBy($sortField, $sortOrder);
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);
        
        $pricing_packages = $query->paginate($limit, ['*'], 'page', $page);

       return response()->json([
            'data' => $pricing_packages->items(),
            'meta' => [
                'total' => $pricing_packages->total(),
                'per_page' => $pricing_packages->perPage(),
                'current_page' => $pricing_packages->currentPage(),
                'last_page' => $pricing_packages->lastPage(),
                'from' => $pricing_packages->firstItem(),
                'to' => $pricing_packages->lastItem(),
            ]
        ]);
    }


    public function userPlanDetails(Request $request, $id){
        $user = User::with('userpackage')->where('id', $id)->firstOrFail();
        
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    public function cancelSubscription(Request $request){
        $currentUser = User::find($request->user_id);
        $user_id = $request->user_id;
        $user = User::find($user_id);
        
        try{
            $restul = $this->subscriptionService->cancelSubscription($user);

            return response()->json(['message' => 'Subscription cancelation request successfully submitted. It will be affect at the end of the current preriod.'], 200);
        }catch(Exception $e){
            $message = $e->getMessage();
            return response()->json(['message' => 'Can not canceled the subscription due to unexpected errors.'], 422);
        }
    }

    public function paymentMethods(User $user){
        try{
            $user->load('userpackage');
            $paymentMethods = [];
            $defaultMethod = '';
            if(isset($user->userpackage->stripe_customer_id) && !empty($user->userpackage->stripe_customer_id) && $user->userpackage->package->period != 'Free Trail'):
                $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                $paymentMethods = $stripe->customers->allPaymentMethods(
                    $user->userpackage->stripe_customer_id,
                    [
                        'limit' => 10,
                        'type' => 'card'
                    ]
                )->data;
                usort($paymentMethods, fn($a, $b) => $b->created <=> $a->created);
                $customer = $stripe->customers->retrieve(
                    $user->userpackage->stripe_customer_id, []
                );
                $defaultMethod = $customer->invoice_settings->default_payment_method;
            endif;
            return response()->json([
                'success' => true,
                'methods' => $paymentMethods,
                'default_method_id' => $defaultMethod
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Can not add payment method. try again later', 
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function storePaymentMethod(Request $request){
        try{        
            $user_id = (isset($request->user_id) && !empty($request->user_id) ? $request->user_id : 0);
            $customer_id = (isset($request->stripe_customer_id) && !empty($request->stripe_customer_id) ? $request->stripe_customer_id : null);
            $is_default = (isset($request->is_default) && !empty($request->is_default) ? $request->is_default : 0);
            $paymentMethod = (isset($request->stripe_payment_method_token) && !empty($request->stripe_payment_method_token) ? $request->stripe_payment_method_token : null);

            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $stripe->paymentMethods->attach(
                $paymentMethod,
                ['customer' => $customer_id]
            );

            if($is_default == 1):
                $stripe->customers->update($customer_id, [
                    'invoice_settings' => [
                        'default_payment_method' => $paymentMethod,
                    ],
                ]);
            endif;
            return response()->json([
                'success' => true,
                'message' => 'Payment method successfully added.'
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Can not add payment method. try again later',
                'error' => $e->getMessage()
            ], 304);
        }
    }

    public function enrolledSubscription(UpgradeSubscriptionRequest $request){
        try {
            $user_id = $request->user_id;
            $user = User::find($user_id);
            $pricingPackage = PricingPackage::findOrFail($request->pricing_package_id);
            
            $result = $this->subscriptionService->subscribe($user, $pricingPackage, $request->token, $request->card_holder_name);
            
            return response()->json([
                'message' => 'Your subscription plan upgraded request to '.$pricingPackage->title.' successfully submitted.', 
                'result' => $result
            ], 200);
            
        } catch (Exception $e) {
            $message = $e->getMessage();
            return response()->json(['message' => 'Somthing went wrong. Please try again later.'], 304);
        }
    }

    public function upgradeSubscriptions(Request $request){
        $package_id = $request->package_id;
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $newPackage = PricingPackage::find($package_id);
        try{
            $result = $this->subscriptionService->upgradeToYearly($user, $newPackage);

            return response()->json(['message' => 'Subscription upgrade request successfully submitted. At the end of the current period your new package will activate.'], 200);
        }catch(Exception $e){
            $message = $e->getMessage();
            return response()->json(['message' => 'Can not upgrade the subscription due to unexpected errors.'], 422);
        }
    }
}
