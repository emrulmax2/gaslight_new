<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Fakers\Countries;
use App\Models\CustomerJob;
use App\Models\User;

class Dashboard extends Controller
{
    public function index(): View
    {
        return view('app.dashboard.index',[
            'user' => User::find(auth()->user()->id),
            'countries' => Countries::fakeCountries(),
            'first_login' => auth()->user()->first_login,
            'recent_jobs' => CustomerJob::with('customer', 'property')->where('created_by', auth()->user()->id)->orderBy('id', 'DESC')->take(5)->get()
        ]);
    }
}
