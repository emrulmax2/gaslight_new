<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\CustomerJobDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobDocumentController extends Controller
{
    public function store(Request $request){
        $customer_id = $request->customer_id;
        $customer_job_id = $request->customer_job_id;

        $document = $request->file('file');
        $imageName = time().'_'.$customer_id.'_'.$customer_job_id.'.'.$document->getClientOriginalExtension();
        $path = $document->storeAs('customers/'.$customer_id.'/jobs/'.$customer_job_id, $imageName, 'public');
        
        $data = [];
        $data['customer_id'] = $customer_id;
        $data['customer_job_id'] = $customer_job_id;
        $data['display_file_name'] = $imageName;
        $data['current_file_name'] = $imageName;
        $data['doc_type'] = $document->getClientOriginalExtension();
        $data['disk_type'] = 'local';
        $data['path'] = Storage::disk('public')->url($path);
        $data['created_by'] = auth()->user()->id;
        $jobUploads = CustomerJobDocument::create($data);

        return response()->json(['message' => 'Document successfully uploaded.'], 200);
    }
}
