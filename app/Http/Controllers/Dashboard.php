<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Fakers\Countries;

class Dashboard extends Controller
{
    public function index(): View
    {
        
        return view('app.dashboard.index',[
            
            'countries' => Countries::fakeCountries(),
            'first_login' => auth()->user()->first_login,
        ]);
        
    }
}
