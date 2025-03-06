<?php
// filepath: /C:/wamp64/www/gaslight_new/app/Http/Controllers/ImpersonateController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    public function impersonate($id)
    {
        if (Auth::guard('superadmin')->check()) {
            $user = User::findOrFail($id);
            session(['original_superadmin_id' => Auth::guard('superadmin')->id()]);
            Auth::guard('superadmin')->user()->impersonate($user);
            session(['impersonate' => true]); // Set session key
            return redirect()->route('company.dashboard');
        }
        return redirect()->route('login');
    }

    public function stopImpersonate()
    {
        
        if (Auth::check()) {
            Auth::logout();
            session()->forget('impersonate'); // Clear session key
            $originalSuperAdminId = session('original_superadmin_id');
            session()->forget('original_superadmin_id'); // Clear original SuperAdmin ID from session
            Auth::guard('superadmin')->loginUsingId($originalSuperAdminId);
            return redirect()->route('superadmin.dashboard');
        }
        return redirect()->route('login');
    }
}