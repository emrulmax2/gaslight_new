<?php
// filepath: c:\wamp64\www\gaslight_new\app\Http\Controllers\Auth\LoginController.php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

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
            'redirect_url' => route('api.user.dashboard'),
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logged out successfully']);
    }
}