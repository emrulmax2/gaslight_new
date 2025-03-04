<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    
    public function index()
    {
        return view('app.profile.index', [
            'title' => 'Profile - Gas Certificate APP',
            'user' => User::find(auth()->user()->id),
        ]);
    }
}

