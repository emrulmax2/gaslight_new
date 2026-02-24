<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request){
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status === Password::RESET_LINK_SENT):
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => 'Reset password link successfully send to your email.'
            ], 200);
        else:
            return response()->json([
                'success' => false,
                'status' => $status,
                'message' => 'Something went wrong. Please try again later.'
            ], 304);
        endif;

        //return $status === Password::RESET_LINK_SENT ? back()->with('status', __($status)) : back()->withErrors(['email' => __($status)]);
    }
}
