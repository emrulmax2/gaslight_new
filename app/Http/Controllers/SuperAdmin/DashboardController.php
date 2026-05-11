<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuspensionReason;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;


class DashboardController extends Controller
{
    public function index(): View
    {
        return view('app.superadmin.dashboard.index',[
            'users' => User::all(),
            'reasons' => SuspensionReason::where('active', 1)->orderBy('name', 'ASC')->get()
        ]);
    }
}
