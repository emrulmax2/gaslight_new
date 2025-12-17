<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Fakers\Countries;
use App\Http\Requests\SendInvitationSmsRequest;
use App\Http\Requests\UpgradeSubscriptionRequest;
use App\Models\Customer;
use App\Models\CustomerJob;
use App\Models\JobForm;
use App\Models\PricingPackage;
use App\Models\User;
use App\Models\UserPricingPackage;
use App\Models\UserPricingPackageInvoice;
use App\Models\UserReferralCode;
use Illuminate\Support\Str;
use Exception;
use Stripe;

class Dashboard extends Controller
{
    public function index(): View
    {
        $theUser = auth()->user()->id;
        $this->hasReferralCode();
        return view('app.dashboard.index',[
            'title' => 'Dashboard - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Record & Drafts', 'href' => 'javascript:void(0);'],
            ],
            'user' => User::with('referral')->find(auth()->user()->id),
            'countries' => Countries::fakeCountries(),
            'first_login' => auth()->user()->first_login,
            'recent_jobs' => CustomerJob::with('customer', 'property')->where('created_by', $theUser)->where('status', 'Due')->orderBy('id', 'DESC')->take(5)->get(),
            'user_jobs' => CustomerJob::where('created_by', $theUser)->where('status', 'Due')->get()->count(),
            'user_customers' => Customer::where('created_by', $theUser)->get()->count(),
            //'forms' => JobForm::with('childs')->where('parent_id', 0)->orderBy('id', 'ASC')->get()
        ]);
    }

    public function hasReferralCode(){
        $user = User::with('referral')->find(auth()->user()->id);
        if(!isset($user->referral->id)):
            $name = $user->name;
            $prefix = strtoupper(substr(str_replace(' ', '', $name), 0, 3));
            UserReferralCode::create([
                'user_id' => $user->id,
                'code' => $prefix.random_int(100000, 999999),
                'active' => 1,

                'created_by' => $user->id,
            ]);
        endif;
    }

    public function sendInvitationSms(SendInvitationSmsRequest $request){
        $phone_numbers = (isset($request->phone_numbers) && !empty($request->phone_numbers) ? explode(',', str_replace(' ', '', $request->phone_numbers)) : []);
        $messages = (isset($request->messages) && !empty($request->messages) ? $request->messages : '');

        if(!empty($phone_numbers) && !empty($messages)):
            return response()->json(['msg' => 'Invitation successfully sent to your friends', 'red' => ''], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator.', 'red' => ''], 304);
        endif;
    }

    public function manageSubscriptions(){
        $user = User::find(auth()->user()->id);
        return view('app.dashboard.subscription',[
            'title' => 'Manage Subscription - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Manage Subscription', 'href' => 'javascript:void(0);'],
            ],
            'user' => User::with('referral')->find(auth()->user()->id),
            'packages' => PricingPackage::where('active', 1)->orderBy('order', 'ASC')->get(),
            'userPackage' => UserPricingPackage::with('package')->where('user_id', $user->id)->where('active', 1)->orderByDesc('id')->first()
        ]);
    }

    public function upgradeSubscriptions($package_id){
        $user = User::find(auth()->user()->id);
        return view('app.dashboard.upgrade-subscription',[
            'title' => 'Upgrade Subscription - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Upgrade Subscription', 'href' => 'javascript:void(0);'],
            ],
            'user' => User::with('referral')->find(auth()->user()->id),
            'pack' => PricingPackage::find($package_id),
        ]);
    }

    public function upgradeSubscription(UpgradeSubscriptionRequest $request){
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $name = $user->name;
        $email = $user->email;
        $userPackage = UserPricingPackage::where('user_id', $user_id)->orderBy('id', 'desc')->get()->first();

        $pricing_package_id = $request->pricing_package_id;
        $pricingPackage = PricingPackage::find($pricing_package_id);

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
                    
                    'updated_by' => auth()->user()->id
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
                        
                        'created_by' => auth()->user()->id,
                    ]);
                endif;
                return response()->json(['message' => 'Your subscription plan successfully upgraded to '.$pricingPackage->title.'.', 'red' => route('company.dashboard')], 200);
            }catch(Exception $e){
                $message = $e->getMessage();
                return response()->json(['message' => 'Can not upgrade your plan due to payment failure.', 'red' => ''], 304);
            }
        }catch(Exception $e){
            $message = $e->getMessage();
            return response()->json(['message' => 'Somthing went wrong. Please try again later.', 'red' => ''], 304);
        }
    }
}
