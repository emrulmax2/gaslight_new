<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\SuperAdmin;
use App\Http\Requests\StoreSuperAdminRequest;
use App\Http\Requests\SuperAdminLoginRequest;
use App\Http\Requests\UpdateSuperAdminRequest;
use App\Models\Option;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SuperAdminAuthController extends Controller
{
    public function loginView()
    {
        // Fetch users data
        $users = SuperAdmin::all();

        $env= env('APP_ENV');
        return view('app.auth.superadmin.login', [
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
    public function login(SuperAdminLoginRequest $request)
    {
        if (!Auth::guard('superadmin')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            throw new \Exception('Wrong email or password.');
        } else {
            SuperAdmin::where('id', Auth::guard('superadmin')->user()->id)->update([
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
        Auth::guard('superadmin')->logout();
        Cache::flush();
        return redirect('login');
    }

    
}
