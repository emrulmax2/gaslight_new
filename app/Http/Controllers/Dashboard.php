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
use Carbon\Carbon;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Auth;
use Stripe;
use App\Services\SubscriptionService;

class Dashboard extends Controller
{
    protected $subscriptionService;
    public function __construct(SubscriptionService $subscriptionService){
        $this->subscriptionService = $subscriptionService;
    }

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
            'userPackage' => UserPricingPackage::with('package')->where('user_id', $user->id)->orderByDesc('id')->first()
        ]);
    }

    public function getSubscribed($package_id){
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

    public function enrolledSubscription(UpgradeSubscriptionRequest $request){
        try {
            $user_id = $request->user()->id;
            $user = User::find($user_id);
            $pricingPackage = PricingPackage::findOrFail($request->pricing_package_id);
            
            $result = $this->subscriptionService->subscribe($user, $pricingPackage, $request->token, $request->card_holder_name);
            
            return response()->json([
                'message' => 'Your subscription plan upgraded request to '.$pricingPackage->title.' successfully submitted.', 
                'result' => $result,
                'red' => route('company.dashboard')
            ], 200);
            
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['message' => 'Somthing went wrong. Please try again later.', 'red' => ''], 304);
        }
    }

    public function upgradeSubscriptions(Request $request){
        $package_id = $request->package_id;
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $newPackage = PricingPackage::find($package_id);
        try{
            $result = $this->subscriptionService->upgradeToYearly($user, $newPackage);

            return response()->json(['message' => 'Subscription upgrade request successfully submitted. At the end of the current period your new package will activate.', 'red' => ''], 200);
        }catch(Exception $e){
            $message = $e->getMessage();
            return response()->json(['message' => 'Can not upgrade the subscription due to unexpected errors.', 'red' => ''], 422);
        }
    }
}
