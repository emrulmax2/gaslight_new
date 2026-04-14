<?php
// filepath: c:\wamp64\www\gaslight_new\app\Http\Controllers\Auth\LoginController.php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\EmailLoginOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{


    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to find the user
        $user = User::where('email', $request->email)->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user->update([
            'last_login_ip' => $request->getClientIp(),
            'last_login_at' => Carbon::now()
        ]);
        // Create a personal access token for the user
        $token = $user->createToken('gasCertifiedToken')->accessToken;

        // Return the token in the response
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logged out successfully']);
    }


    public function sendOtp(Request $request)
    {
        try {
            $validated = $request->validate([
                'mobile' => ['required','exists:users,mobile','digits:11','regex:/^07[0-9]{9}$/']
            ]);

            $userOtp = $this->generateOtp($validated['mobile']);
            $smsResponse = $userOtp->sendSMS($validated['mobile']);

            if ($smsResponse['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'OTP has been sent to your mobile number',
                    'otp' => $userOtp->otp,
                    'user_id' => $userOtp->user_id
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => $smsResponse['message']
            ], 500);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
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

   public function otpLogin(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'otp' => 'required'
            ]);

            $userOtp = UserOtp::where('user_id', $validated['user_id'])->where('otp', $validated['otp']) ->first();

            $now = now();

            if (!$userOtp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP.'
                ], 422);
            }

            if ($now->isAfter($userOtp->expire_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your OTP has expired.'
                ], 422);
            }

            $user = User::find($validated['user_id']);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }

            $userOtp->update(['expire_at' => now()]);
            
            $token = $user->createToken('gasCertifiedToken')->accessToken;

            $user->update([
                'last_login_ip' => $request->getClientIp(),
                'last_login_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged in.',
                'token' => $token,
                'user' => $user,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
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
        $existingOtp = EmailLoginOtp::where('email', $email)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingOtp):
            return response()->json([
                'status' => false,
                'message' => 'OTP already sent. Please wait.'
            ], 304);
        endif;

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
        $content .= '<p>You can verify your email by entering the code below.</p>';
        $content .= '<p>';
            $content .= '<strong>Verify via Code:</strong><br/>';
            $content .= 'Verirication Code: <strong>'.$otp.'</strong><br/>';
            $content .= '(This code is valid for next 15 minutes)';
        $content .= '</p>';
        // $content .= '<p>';
        //     $content .= '<strong>Verify via Link:</strong><br/>';
        //     $content .= '<a href="'.$url.'" style="color:blue; text-decoration: underline;">Click here to verify your email address</a><br/>';
        //     $content .= '(This link is valid for next 15 minutes)';
        // $content .= '</p>';
        $content .= '<p>Thanks & Regards<br/>Gas Engineer App</p>';

        GCEMailerJob::dispatch($configuration, [$email], new GCESendMail($subject, $content, [], $subject, 'communication', '', $configuration['from_name']), []);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent Successfully. Please check your inbox.',
            'otp' => $otp
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
            ], 304);
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
            ], 400);
        }

        // OTP verified → delete record
        $user = User::where('email', $request->email)->first();
        if($user){
            Auth::login($user);
            $token = $user->createToken('gasCertifiedToken')->accessToken;

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

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged in.',
                'token' => $token,
                'user' => $user,
            ], 200);
        }else{
            return response()->json(['message' => 'Wrong email or OTP.'], 401);
        }
    }

}