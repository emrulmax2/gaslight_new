<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Fakers\Countries;
use App\Models\Customer;
use App\Models\CustomerJob;
use App\Models\User;

class Dashboard extends Controller
{
    public function index(): View
    {
        $theUser = auth()->user()->id;
        return view('app.dashboard.index',[
            'user' => User::find(auth()->user()->id),
            'countries' => Countries::fakeCountries(),
            'first_login' => auth()->user()->first_login,
            'recent_jobs' => CustomerJob::with('customer', 'property')->where('created_by', $theUser)->orderBy('id', 'DESC')->take(5)->get(),
            'user_jobs' => CustomerJob::where('created_by', $theUser)->get()->count(),
            'user_customers' => Customer::where('created_by', $theUser)->get()->count()
        ]);
    }
}
