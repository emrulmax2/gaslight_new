<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $env= env('APP_ENV');
        return view('pages/login', [
            'env' => $env,
            'opt' => Option::where('category', 'SITE_SETTINGS')->pluck('value', 'name')->toArray()
        ]);
    }

    /**
     * Authenticate login user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            throw new \Exception('Wrong email or password.');
        } else {
            User::where('id', auth()->user()->id)->update([
                'last_login_ip' => $request->getClientIp(),
                'last_login_at' => Carbon::now()
            ]);
        }
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
        return view('pages/register');
    }


    public function registerPost(Request $request)
    {
        $request->validate([
            //'g-recaptcha-response' => 'required',
            // other validation rules...
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,engineer',
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
    
}
