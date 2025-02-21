<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\View\View;

class Dashboard extends Controller
{
    public function index(): View
    {
        
        return view('pages/dashboard-overview-1');
        
    }
}
