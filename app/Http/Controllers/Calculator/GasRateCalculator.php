<?php

namespace App\Http\Controllers\Calculator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GasRateCalculator extends Controller
{
    public function index()
    {
        return view('app.calculator.index', [
            'title' => 'Gas Rate Calculator',
            'breadcrumbs' => [
                ['label' => 'Calculator', 'href' => 'javascript:void(0);'],
                ['label' => 'Gas Rate Calculator', 'href' => 'javascript:void(0);'],
            ]
        ]);
    }
}
