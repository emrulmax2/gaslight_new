<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\EmailLoginOtp;
use App\Models\Option;
use App\Models\User;
use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    /**
     * Show specified view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginView()
    {
        // Fetch users data
        $users = User::all();

        $env= env('APP_ENV');
        return view('app.auth.login', [
            'env' => $env,
            'users' => $users,
            'opt' => Option::where('category', 'SITE_SETTINGS')->pluck('value', 'name')->toArray()
        ]);
    }
    public function emailLoginView()
    {
        // Fetch users data
        $users = User::all();

        $env= env('APP_ENV');
        return view('app.auth.login-email', [
            'env' => $env,
            'users' => $users,
            'opt' => Option::where('category', 'SITE_SETTINGS')->pluck('value', 'name')->toArray()
        ]);
    }

    /**
     * Authenticate login user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function login(LoginRequest $request)
    // {
    //     if (!Auth::attempt([
    //         'email' => $request->email,
    //         'password' => $request->password
    //     ])) {
    //         throw new \Exception('Wrong email or password.');
    //     } else {
    //         User::where('id', auth()->user()->id)->update([
    //             'last_login_ip' => $request->getClientIp(),
    //             'last_login_at' => Carbon::now()
    //         ]);
    //     }
    // }


    public function login(LoginRequest $request)
    {
        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            return response()->json(['message' => 'Wrong email or password.'], 401);
        }

        $user = Auth::user(); 

        $user->update([
            'last_login_ip' => $request->getClientIp(),
            'last_login_at' => Carbon::now()
        ]);

        return response()->json([
            'first_login' => $user->first_login, 
            'message' => 'Login successful'
        ]);
    }
    /**
     * Logout user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();
        Cache::flush();
        return redirect('login');
    }

    public function register(): View
    {
        return view('app.auth.register');
    }


    public function registerPost(Request $request)
    {
        $request->validate([
            //'g-recaptcha-response' => 'required',
            // other validation rules...
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,staff',
            'terms' => 'required',
        ]);

        // $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        //     'secret' => env('RECAPTCHA_SECRET_KEY'),
            
        //     'response' => $request->input('g-recaptcha-response'),
        // ]);

        // $responseBody = json_decode($response->body());

        // if (!$responseBody->success) {
        //     return back()->withErrors(['captcha' => 'reCAPTCHA verification failed.']);
        // }

        // Proceed with registration...
        //implement a user account with the given credentials...

        // Create the user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => $request->input('role'),
        ]);

        // Optionally, you can assign the role using a role management package like Spatie's Laravel-Permission
        // $user->assignRole($request->input('role'));

       // return redirect('login')->with('success', 'A verification email sent to your email. Please check your email.');

       return response()->json(['success' => 'Registration successful!'], 200);

    }


    public function sendOtp(Request $request){
        $request->validate([
            'mobile' => 'required|exists:users,mobile'
        ]);

        $userOtp = $this->generateOtp($request->mobile);
        $response = $userOtp->sendSMS($request->mobile);

        if(!isset($response['success']) || !$response['success']):
            return response()->json(['message' => 'Something went wrong. Please try later.', 'user_id' => 0, 'response' => $response], 422);
        else:
            return response()->json(['msg' => 'OTP has been sent on your mobile number', 'user_id' => $userOtp->user_id], 200);
        endif;
    }


    public function generateOtp($mobile){
        $user = User::where('mobile', $mobile)->first();

        $userOtp = UserOtp::where('user_id', $user->id)->latest()->first();
        $now = now();
        if($userOtp && $now->isBefore($userOtp->expire_at)){
            return $userOtp;
        }

        return UserOtp::create([
            'user_id' => $user->id,
            'otp' => rand(1000, 9999),
            'expire_at' => $now->addMinutes(10)
        ]);
    }

    public function otpLogin(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required'
        ]);

        $userOtp = UserOtp::where('user_id', $request->user_id)->where('otp', $request->otp)->first();
        $now = now();
        if (!$userOtp) {
            return response()->json(['message' => 'Invalid OTP.'], 304);
        }else if($userOtp && $now->isAfter($userOtp->expire_at)){
            return response()->json(['message' => 'Your OTP has been expired.'], 304);
        }

        
        $user = User::whereId($request->user_id)->first();
        if($user){
            $userOtp->update(['expire_at' => now()]);
            Auth::login($user);

            $user->update([
                'last_login_ip' => $request->getClientIp(),
                'last_login_at' => Carbon::now()
            ]);

            return response()->json(['message' => 'Successfully logged in.', 'first_login' => $user->first_login], 200);
        }

        return response()->json(['message' => 'Invalid OTP.'], 304);
    }

    public function sendEmailOtp(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);
        $email = $request->email;

        $user = User::where('email', $email)->first();
        if (!$user):
            return response()->json([
                'status' => false,
                'message' => 'Email not registered.'
            ], 404);
        endif;

        // Prevent spam (1 active OTP at a time)
        $deleteExistingOtp = EmailLoginOtp::where('email', $email)->forceDelete();

        $otp = rand(100000, 999999);
        EmailLoginOtp::updateOrCreate(
            ['email' => $email],
            [
                'otp' => Hash::make($otp),
                'expires_at' => now()->addMinutes(15)
            ]
        );

        // Encrypt payload
        $payload = Crypt::encrypt([
            'email' => $email,
            'otp' => $otp
        ]);

        // Create signed URL (expires in 15 mins)
        $url = URL::temporarySignedRoute(
            'login.magic.otp',
            now()->addMinutes(15),
            ['data' => $payload]
        );

        $configuration = [
            'smtp_host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'smtp_port' => env('MAIL_PORT', '587'),
            'smtp_username' => env('MAIL_USERNAME', 'info@gascertificate.co.uk'),
            'smtp_password' => env('MAIL_PASSWORD', 'PASSWORD'),
            'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'),
            
            'from_email'    => env('MAIL_FROM_ADDRESS', 'info@gascertificate.co.uk'), 
            'from_name'    =>  env('APP_NAME', 'Gas Engineer App') 
        ];
        $subject = 'Login Verification Code.';
        $content = '<p>Hi '.$email.'</p>';
        $content .= '<p>Thank you for using email verification for login.</p>';
        $content .= '<p>You can verify your email by entering the code below or by clicking the verification link in the email</p>';
        $content .= '<p>';
            $content .= '<strong>Verify via Code:</strong><br/>';
            $content .= 'Verirication Code: <strong>'.$otp.'</strong><br/>';
            $content .= '(This code is valid for next 15 minutes)';
        $content .= '</p>';
        $content .= '<p>';
            $content .= '<strong>Verify via Link:</strong><br/>';
            $content .= '<a href="'.$url.'" style="color:blue; text-decoration: underline;">Click here to verify your email address</a><br/>';
            $content .= '(This link is valid for next 15 minutes)';
        $content .= '</p>';
        $content .= '<p>Thanks & Regards<br/>Gas Engineer App</p>';

        GCEMailerJob::dispatch($configuration, [$email], new GCESendMail($subject, $content, [], $subject, 'communication', '', $configuration['from_name']), []);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent Successfully. Please check your inbox.'
        ], 200);

    }


    public function quickLogin(Request $request){
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        $record = EmailLoginOtp::where('email', $request->email)->first();
        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'OTP not found.'
            ], 404);
        }

        // Expired
        if ($record->isExpired()) {
            $record->delete();

            return response()->json([
                'status' => false,
                'message' => 'OTP has been expired.'
            ], 410);
        }

        // Too many attempts
        if ($record->attempts >= 5) {
            $record->delete();

            return response()->json([
                'status' => false,
                'message' => 'Too many attempts. Request new OTP.'
            ], 429);
        }

        // Wrong OTP
        if (!Hash::check($request->otp, $record->otp)) {
            $record->increment('attempts');

            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP. Please insert a valid OTP.'
            ], 401);
        }

        // OTP verified → delete record
        $user = User::where('email', $request->email)->first();
        if($user){
            Auth::login($user);

            $record->delete();
            if($user->email_verified_at == ''):
                $user->update(['email_verified_at' => date('Y-m-d H:i:s')]);
            endif;
            $user->update([
                'last_login_ip' => $request->getClientIp(),
                'last_login_at' => Carbon::now()
            ]);

            $user->update([
                'last_login_ip' => $request->getClientIp(),
                'last_login_at' => Carbon::now()
            ]);

            return response()->json(['message' => 'Successfully logged in.', 'first_login' => $user->first_login], 200);
        }else{
            return response()->json(['message' => 'Wrong email or OTP.'], 401);
        }
    }


    public function magicLogin(Request $request){
        try {
            $data = Crypt::decrypt($request->data);
            $email = $data['email'];
            $otp   = $data['otp'];
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Invalid or tampered link provided.');
        }

        $record = EmailLoginOtp::where('email', $email)->first();
        if (!$record) {
            return redirect('/login')->with('error', 'OTP does not match. Tempered link provided.');
        }

        if ($record->expires_at < now()) {
            $record->delete();
            return redirect('/login')->with('error', 'Link expired.');
        }

        if (!Hash::check($otp, $record->otp)) {
            return redirect('/login')->with('error', 'OTP does not match. Tempered link provided.');
        }


        $user = User::where('email', $email)->first();
        if($user){
            Auth::login($user);

            $record->delete();
            if($user->email_verified_at == ''):
                $user->update(['email_verified_at' => date('Y-m-d H:i:s')]);
            endif;
            $user->update([
                'last_login_ip' => $request->getClientIp(),
                'last_login_at' => Carbon::now()
            ]);

            $user->update([
                'last_login_ip' => $request->getClientIp(),
                'last_login_at' => Carbon::now()
            ]);

            return redirect('/')->with('success', 'Login successful');
        }else{
            return redirect('/login')->with('error', 'User email does not exist.');
        }
    }
    
}
