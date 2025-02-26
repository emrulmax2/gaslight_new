<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerJob;
use App\Models\CustomerJobDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobDocumentController extends Controller
{
    public function list(Customer $customer, CustomerJob $job, Request $request){
        $queryStr = (isset($request->querystr) && !empty($request->querystr) ? $request->querystr : '');
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = CustomerJobDocument::orderByRaw(implode(',', $sorts))->where('customer_job_id', $job->id);
        if(!empty($queryStr)):
            $query->where(function($q) use($queryStr){
                $q->where('display_file_name','LIKE','%'.$queryStr.'%')->orWhere('current_file_name','LIKE','%'.$queryStr.'%');
            });
        endif;
        if($status == 2):
            $query->onlyTrashed();
        endif;

        $total_rows = $query->count();
        $page = (isset($request->page) && $request->page > 0 ? $request->page : 0);
        $perpage = (isset($request->size) && $request->size == 'true' ? $total_rows : ($request->size > 0 ? $request->size : 10));
        $last_page = $total_rows > 0 ? ceil($total_rows / $perpage) : '';
        
        $limit = $perpage;
        $offset = ($page > 0 ? ($page - 1) * $perpage : 0);

        $Query= $query->skip($offset)
               ->take($limit)
               ->get();

        $data = array();

        if(!empty($Query)):
            $i = 1;
            foreach($Query as $list):
                $data[] = [
                    'id' => $list->id,
                    'sl' => $i,
                    'customer_id' => $list->customer_id,
                    'customer_job_id' => $list->customer_job_id,
                    'display_file_name' => $list->display_file_name,
                    'current_file_name' => $list->current_file_name,
                    'doc_type' => $list->doc_type,
                    'disk_type' => $list->disk_type,
                    'download_url' => ($list->download_url ? $list->download_url : null),
                    'created_at' => (!empty($list->created_at) ? date('jS F, Y', strtotime($list->created_at)) : ''),
                    'deleted_at' => $list->deleted_at
                ];
                $i++;
            endforeach;
        endif;
        
        return response()->json(['last_page' => $last_page, 'data' => $data]);
    }


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

    public function destroy(Customer $customer, CustomerJob $job, $document_id){
        $customer = CustomerJobDocument::find($document_id)->delete();

        return response()->json(['msg' => 'Job Document Successfully Deleted.', 'red' => ''], 200);
    }

    public function restore(Customer $customer, CustomerJob $job, $document_id){
        $customer = CustomerJobDocument::where('id', $document_id)->withTrashed()->restore();

        return response()->json(['msg' => 'Job Document Successfully Restored!', 'red' => ''], 200);
    }
}
