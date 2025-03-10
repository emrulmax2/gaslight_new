<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomerJob;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function index($record, CustomerJob $job){
        $job->load(['customer', 'customer.contact', 'property']);
        return view('app.records.'.$record.'.index', [
            'title' => 'Records - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Record', 'href' => 'javascript:void(0);'],
                ['label' => ucfirst($record), 'href' => 'javascript:void(0);'],
            ],
            'record' => $record,
            'job' => $job,
            'company' => Company::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get()->first()
        ]);
    }
}
