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

class UserSubscriptionController extends Controller
{
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
        $user_id = $request->user_id;
        $currentUser = User::find($user_id);
        $userPackage = UserPricingPackage::where('user_id', $user_id)->orderBy('id', 'DESC')->get()->first();
        $userInvoice = UserPricingPackageInvoice::where('user_id', $user_id)->where('user_pricing_package_id', $userPackage->id)->orderBy('id', 'DESC')->get()->first();
        
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        try{
            $subscription = $stripe->subscriptions->update(
                $userPackage->stripe_subscription_id,
                [
                    'cancel_at_period_end' => true,
                    'metadata' => [
                        'is_cancelled' => 1,
                        'upgrade_to' => null,
                        'user_id' => $userPackage->user_id,
                    ]
                ]
            );
            $userPackage->update(['cancellation_requested' => 1, 'requested_by' => $user_id, 'requested_at' => date('Y-m-d H:i:s')]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription successfully cancelled.'
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Can not canceled the subscription due to unexpected errors.',
                'error' => $e->getMessage()
            ], 422);
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
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $name = $user->name;
        $email = $user->email;
        $userPackage = UserPricingPackage::where('user_id', $user_id)->orderBy('id', 'desc')->get()->first();

        $pricing_package_id = $request->pricing_package_id;
        $pricingPackage = PricingPackage::find($pricing_package_id);

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
                        ['price' => $pricingPackage->stripe_plan]
                    ],
                    'currency' => 'GBP',
                    'default_payment_method' => $request->token,
                    'metadata' => [
                        'billed_to' => $request->card_holder_name,
                        'user_id' => $user_id
                    ],
                    'payment_behavior' => 'allow_incomplete'
                ]);

                $userPackage = UserPricingPackage::where('user_id', $user_id)->update([
                    'user_id' => $user_id,
                    'pricing_package_id' => $pricing_package_id,
                    'stripe_customer_id' => $customer->id,
                    'stripe_subscription_id' => $subscription->id,
                    'start' => date('Y-m-d', $subscription->current_period_start),
                    'end' => date('Y-m-d', $subscription->current_period_end),
                    'price' => $pricingPackage->price,
                    'active' => ($subscription->status && $subscription->status == 'active' ? 1 : 0),
                    
                    'updated_by' => $user_id
                ]);

                if($userPackage):
                    $userPricingPackage = UserPricingPackage::where('user_id', $user_id)->where('pricing_package_id', $pricing_package_id)->get()->first();
                    $invoice = UserPricingPackageInvoice::create([
                        'user_id' => $user_id,
                        'user_pricing_package_id' => $userPricingPackage->id,
                        'invoice_id' => $subscription->latest_invoice,
                        'start' => date('Y-m-d', $subscription->current_period_start),
                        'end' => date('Y-m-d', $subscription->current_period_end),
                        'status' => (isset($subscription->status) && !empty($subscription->status) ? $subscription->status : null),
                        
                        'created_by' => $user_id
                    ]);
                endif;
                return response()->json([
                    'success' => true,
                    'message' => 'Your subscription plan successfully upgraded to '.$pricingPackage->title.'.', 
                ], 200);
            }catch(Exception $e){
                return response()->json([
                    'success' => false,
                    'message' => 'Somthing went wrong. Please try again later.', 
                    'error' => $e->getMessage()
                ], 422);
            }
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Somthing went wrong. Please try again later.', 
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function upgradeSubscriptions(Request $request){
        $package_id = $request->package_id;
        $user_id = $request->user_id;
        $user = User::find($user_id);

        $userPackage = UserPricingPackage::where('user_id', $user_id)->orderBy('id', 'DESC')->get()->first();
        $userInvoice = UserPricingPackageInvoice::where('user_id', $user_id)->where('user_pricing_package_id', $userPackage->id)->orderBy('id', 'DESC')->get()->first();
    
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        try{
            $subscription = $stripe->subscriptions->update(
                $userPackage->stripe_subscription_id,
                [
                    'cancel_at_period_end' => true,
                    'metadata' => [
                        'is_cancelled' => 1,
                        'upgrade_to' => $package_id,
                        'user_id' => $userPackage->user_id,
                    ]
                ]
            );
            $userPackage->update([
                'cancellation_requested' => 1, 
                'requested_by' => $user_id, 
                'requested_at' => date('Y-m-d H:i:s'),
                'upgrade_to' => $package_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription upgrade request successfully submitted. At the end of the current period your new package will activate.', 
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Can not upgrade the subscription due to unexpected errors.',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
