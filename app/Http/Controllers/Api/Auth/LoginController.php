<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserOtp;
use Carbon\Carbon;
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

            return response()->json([
                'success' => true,
                'message' => 'OTP has been sent on your mobile number',
                'otp' => $userOtp->otp,
                'user_id' => $userOtp->user_id
            ], 200);

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



}