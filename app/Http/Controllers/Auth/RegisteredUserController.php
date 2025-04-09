<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PricingPackage;
use App\Models\User;
use App\Models\UserPricingPackage;
use App\Models\UserReferralCode;
use App\Models\UserReferred;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{


    public function index(): View
    {
        $users = User::all();
        return view('app.auth.register', [
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $referral_code = $request->referral_code;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required',
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
        endif;
        event(new Registered($user));

        if (!Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ])) {
            throw new \Exception('Wrong email or password.');
        } else {
            User::where('id', auth()->user()->id)->update([
                'last_login_ip' => $request->getClientIp(),
                'last_login_at' => Carbon::now()
            ]);
        }
       return response()->json(['success' => 'Registration successful!'], 200);
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
