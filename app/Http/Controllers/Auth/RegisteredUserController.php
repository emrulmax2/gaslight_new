<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{


    public function index(): View
    {
        $users = User::all();
        return view('app.auth.register', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {

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
}
