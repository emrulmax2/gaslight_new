<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\PricingPackage;
use App\Models\User;
use App\Models\UserPricingPackage;
use App\Models\UserReferralCode;
use App\Models\UserReferred;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $referral_code = $request->referral_code;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required',
            'referral_code' => [
            'nullable', // Optional field
                Rule::exists('user_referral_codes', 'code')->where(function ($query) {
                    $query->where('active', 1); // Ensure the referral code is active
                }),
            ],
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'admin',
            'first_login' => 1
        ]);
        if($user->id):
            $name = $request->name;
            $prefix = strtoupper(substr(str_replace(' ', '', $name), 0, 3));
            UserReferralCode::create([
                'user_id' => $user->id,
                'code' => $prefix.random_int(100000, 999999),
                'active' => 1,

                'created_by' => $user->id,
            ]);

            $TRAIL_PERIOD = env('DEFAULT_TRAIL', 14);
            if(!empty($referral_code)):
                $referral = UserReferralCode::where('code', $referral_code)->where('active', 1)->get()->first();
                if(isset($referral->id) && $referral->id > 0):
                    $TRAIL_PERIOD = env('REFEREE_TRAIL', 90);
                    $userReferred = UserReferred::create([
                        'user_referral_code_id' => $referral->id,
                        'referrer_id' => $referral->user_id,
                        'referee_id' => $user->id,
                        'code' => $referral_code,
                        
                        'created_by' => $user->id,
                    ]);
                endif;
            endif;

            $defaultPeriod = PricingPackage::where('period', 'Free Trail')->where('active', 1)->get()->first();
            $package = UserPricingPackage::create([
                'user_id' => $user->id,
                'pricing_package_id' => (isset($defaultPeriod->id) && $defaultPeriod->id > 0 ? $defaultPeriod->id : null),
                'start' => date('Y-m-d'),
                'end' => date('Y-m-d', strtotime('+'.$TRAIL_PERIOD.' days')),
                'price' => (isset($defaultPeriod->price) && $defaultPeriod->price > 0 ? $defaultPeriod->price : 0),
                'active' => 1,
                
                'created_by' => $user->id,
            ]);
            event(new Registered($user));

            if (!Auth::attempt([
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ])):
                return response()->json([
                    'success' => false,
                    'message' => 'Registration successfull but can not logged in!',
                ], 200);
            else:
                User::where('id', auth()->user()->id)->update([
                    'last_login_ip' => $request->getClientIp(),
                    'last_login_at' => Carbon::now()
                ]);

                $token = $user->createToken('gasCertifiedToken')->accessToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Registration & Login successful',
                    'token' => $token,
                    'user' => $user,
                    'redirect_url' => route('api.user.dashboard'),
                ], 200);
            endif;
        else:
            return response()->json([
                'success' => false,
                'message' => 'Registration Failed!',
            ], 200);
        endif;
    }

    public function validateReferral(Request $request){
        $referral_code = $request->referral_code;
        $referral = UserReferralCode::where('code', $referral_code)->where('active', 1)->get()->first();

        if(isset($referral->id) && $referral->id > 0):
            return response()->json(['suc' => 1], 200);
        else:
            return response()->json(['suc' => 2], 200);
        endif;
    }

}
