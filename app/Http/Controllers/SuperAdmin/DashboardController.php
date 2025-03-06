<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;


class DashboardController extends Controller
{
    public function index(): View
    {
        return view('app.superadmin.dashboard.index',[
            'users' => User::all(),
        ]);
    }
}
