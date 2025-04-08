<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Fakers\Countries;
use App\Http\Requests\SendInvitationSmsRequest;
use App\Models\Customer;
use App\Models\CustomerJob;
use App\Models\JobForm;
use App\Models\User;
use App\Models\UserReferralCode;
use Illuminate\Support\Str;

class Dashboard extends Controller
{
    public function index(): View
    {
        $theUser = auth()->user()->id;
        $this->hasReferralCode();
        return view('app.dashboard.index',[
            'user' => User::with('referral')->find(auth()->user()->id),
            'countries' => Countries::fakeCountries(),
            'first_login' => auth()->user()->first_login,
            'recent_jobs' => CustomerJob::with('customer', 'property')->where('created_by', $theUser)->orderBy('id', 'DESC')->take(5)->get(),
            'user_jobs' => CustomerJob::where('created_by', $theUser)->get()->count(),
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
}
