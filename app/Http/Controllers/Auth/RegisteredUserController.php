<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PricingPackage;
use App\Models\RegistrationOtp;
use App\Models\User;
use App\Models\UserPricingPackage;
use App\Models\UserReferralCode;
use App\Models\UserReferred;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Creagia\LaravelSignPad\Signature;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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

    public function store(Request $request){
        $referral_code = isset($request->referral_code) && !empty($request->referral_code) ? $request->referral_code : null;
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'password' => Hash::make($request->input('password')),
            'gas_safe_id_card' => (isset($request->gas_safe_id_card) && !empty($request->gas_safe_id_card) ? $request->gas_safe_id_card : null),
            'role' => 'admin',
            'first_login' => 0
        ]);

        if($user->id):
            $vat_number_check = (isset($request->vat_number_check) && $request->vat_number_check > 0 ? $request->vat_number_check : 0);
            $business_type = (!empty($request->business_type) ? $request->business_type : null);
            $company = Company::create([
                'user_id' => $user->id,
                'company_name' => (!empty($request->company_name) ? $request->company_name : null),
                'business_type' => $business_type,
                'company_registration' => ($business_type == 'Company' && !empty($request->company_registration) ? $request->company_registration : null),
                'vat_number' => ($vat_number_check == 1 && !empty($request->vat_number) ? $request->vat_number : null),
                'display_company_name' => (!empty($request->display_company_name) && $request->display_company_name > 0 ? $request->display_company_name : 0),
                'company_email' => (!empty($request->email) ? $request->email : null),
                'company_phone' => (!empty($request->mobile) ? $request->mobile : null),
                'company_address_line_1' => (!empty($request->company_address_line_1) ? $request->company_address_line_1 : null),
                'company_address_line_2' => (!empty($request->company_address_line_2) ? $request->company_address_line_2 : null),
                'company_city' => (!empty($request->company_city) ? $request->company_city : null),
                'company_state' => (!empty($request->company_state) ? $request->company_state : null),
                'company_postal_code' => (!empty($request->company_postal_code) ? $request->company_postal_code : null),
                'company_country' => (!empty($request->company_country) ? $request->company_country : null),
                'gas_safe_registration_no' => (!empty($request->gas_safe_registration_no) ? $request->gas_safe_registration_no : null),
            ]);
            $company->users()->attach($user->id);


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

            if ($request->has('signature_file')):
                $file = $request->file('signature_file');
                $newFilePath = 'signatures/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        
                Storage::disk('public')->put($newFilePath, file_get_contents($file->getRealPath()));
        
                $signature = new Signature();
                $signature->model_type = User::class;
                $signature->model_id = $user->id;
                $signature->uuid = Str::uuid();
                $signature->filename = $newFilePath;
                $signature->document_filename = null;
                $signature->certified = false;
                $signature->from_ips = json_encode([request()->ip()]);
                $signature->save();
            elseif ($request->has('sign') && $request->input('sign') !== null):
                $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
                $signatureData = base64_decode($signatureData);
                if(strlen($signatureData) > 2621):
                    $user->deleteSignature();
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
                endif;
            endif;
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



        /*$referral_code = $request->referral_code;
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
        return response()->json(['success' => 'Registration successful!'], 200);*/
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

    public function generateOtp(Request $request){
        $MobileNumber = $request->MobileNumber;
        $userExist = User::where('mobile', $MobileNumber)->get()->count();

        if($userExist > 0){
            return response()->json(['message' => 'Mobile number already exist.'], 422);
        }else{
            $regOtp = $this->createOtp($MobileNumber);
            //$regOtp->sendSMS($MobileNumber);

            return response()->json(['message' => 'Otp successfully created and sent to your phone.'], 200);
        }
    }

    public function createOtp($mobile){
        $regOtp = RegistrationOtp::where('mobile', $mobile)->latest()->first();
        $now = now();
        if($regOtp && $now->isBefore($regOtp->expire_at)){
            return $regOtp;
        }

        return RegistrationOtp::create([
            'mobile' => $mobile,
            'otp' => rand(1000, 9999),
            'expire_at' => $now->addMinutes(10)
        ]);
    }

    public function validateOtp(Request $request){
        $MobileNumber = $request->MobileNumber;
        $theOtp = $request->theOtp;

        $otp = RegistrationOtp::where('mobile', $MobileNumber)->orderBy('id', 'desc')->get()->first();
        if(isset($otp->otp) && !empty($otp->otp) && $otp->otp == $theOtp){
            return response()->json(['message' => 'Otp successfully matched.'], 200);
        }else{
            return response()->json(['message' => 'Otp does not match.'], 422);
        }
    }

    public function validateEmail(Request $request){
        $email = $request->email;

        $user = User::where('email', $email)->orderBy('id', 'desc')->get()->first();
        if(isset($user->id) && $user->id > 0){
            return response()->json(['message' => 'The email id already exist.'], 422);
        }else{
            return response()->json(['message' => 'No Match Found'], 200);
        }
    }
}
