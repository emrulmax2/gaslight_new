<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function invoice(Request $request) {
        return view('app.invoice.invoice', [
            'title' => 'Invoice - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Invoice', 'href' => 'javascript:void(0);'],
            ],
        ]);
    }
}
