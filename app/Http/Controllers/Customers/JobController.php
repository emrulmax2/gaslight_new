<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobStoreRequest;
use App\Models\Customer;
use App\Models\CustomerJob;
use App\Models\CustomerJobPriority;
use App\Models\CustomerJobStatus;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Number;

class JobController extends Controller
{
    public function index(Customer $customer){
        $customer->load(['title', 'contact']);
        return view('app.customers.jobs.index', [
            'title' => 'Customers - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Customers', 'href' => route('customers')],
                ['label' => 'Jobs', 'href' => 'javascript:void(0);'],
            ],
            'titles' => Title::where('active', 1)->orderBy('name', 'ASC')->get(),
            'customer' => $customer,
            'priorities' => CustomerJobPriority::orderBy('id', 'ASC')->get(),
            'statuses' => CustomerJobStatus::orderBy('id', 'ASC')->get(),
        ]);
    }

    public function list(Request $request){
        $queryStr = (isset($request->querystr) && !empty($request->querystr) ? $request->querystr : '');
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = CustomerJob::with('customer', 'property', 'priority', 'status')->orderByRaw(implode(',', $sorts))->where('customer_id', $customer_id);
        if(!empty($queryStr)):
            $query->where(function($q) use($queryStr){
                $q->where('description','LIKE','%'.$queryStr.'%')->orWhere('details','LIKE','%'.$queryStr.'%')
                    ->orWhere('reference_no','LIKE','%'.$queryStr.'%');
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
                    'description' => $list->description,
                    'full_name' => (isset($list->customer->full_name) ? $list->customer->full_name : ''),
                    'address_line_1' => (isset($list->property->address_line_1) && !empty($list->property->address_line_1) ? $list->property->address_line_1 : ''),
                    'address_line_2' => (isset($list->property->address_line_2) && !empty($list->property->address_line_2) ? $list->property->address_line_2 : ''),
                    'city' => (isset($list->property->city) && !empty($list->property->city) ? $list->property->city : ''),
                    'postal_code' => (isset($list->property->postal_code) && !empty($list->property->postal_code) ? $list->property->postal_code : ''),
                    'priority' => (isset($list->priority->name) && !empty($list->priority->name) ? $list->priority->name : ''),
                    'status' => (isset($list->status->name) && !empty($list->status->name) ? $list->status->name : ''),
                    'due_date' => (!empty($list->due_date) ? date('jS F, Y', strtotime($list->due_date)) : ''),
                    'reference_no' => (!empty($list->reference_no) ? $list->reference_no : ''),
                    'estimated_amount' => (!empty($list->estimated_amount) ? Number::currency($list->estimated_amount, in: 'GBP') : Number::currency(0, in: 'GBP')),
                    'deleted_at' => $list->deleted_at
                ];
                $i++;
            endforeach;
        endif;
        
        return response()->json(['last_page' => $last_page, 'data' => $data]);
    }

    public function store(JobStoreRequest $request){
        $data = [
            'customer_id' => $request->customer_id,
            'customer_property_id' => $request->customer_property_id,
            'description' => (!empty($request->description) ? $request->description : null),
            'details' => (!empty($request->details) ? $request->details : null),
            'customer_job_priority_id' => (!empty($request->customer_job_priority_id) ? $request->customer_job_priority_id : null),
            'due_date' => (!empty($request->due_date) ? date('Y-m-d', strtotime($request->due_date)) : null),
            'customer_job_status_id' => (!empty($request->customer_job_status_id) ? $request->customer_job_status_id : null),
            'reference_no' => (!empty($request->reference_no) ? $request->reference_no : null),
            'estimated_amount' => (!empty($request->estimated_amount) ? $request->estimated_amount : null),
            'created_by' => auth()->user()->id,
        ];

        $job = CustomerJob::create($data);

        if($job->id):
            return response()->json(['msg' => 'Job successfully created.', 'red' => route('customers.jobs', $request->customer_id)], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
    }

    public function show(Customer $customer, CustomerJob $job){
        $customer->load(['title', 'contact']);
        $job->load(['customer', 'property', 'priority', 'status']);

        return view('app.customers.jobs.show', [
            'title' => 'Jobs - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Customer', 'href' => route('customers')],
                ['label' => 'Jobs', 'href' => route('customers.jobs', $customer->id)],
                ['label' => 'Details', 'href' => 'javascript:void(0);'],
            ],
            'customers' => Customer::where('created_by', auth()->user()->id)->orderBy('last_name', 'ASC')->get(),
            'priorities' => CustomerJobPriority::orderBy('id', 'ASC')->get(),
            'statuses' => CustomerJobStatus::orderBy('id', 'ASC')->get(),
            'customer' => $customer,
            'job' => $job
        ]);
    }

    public function update(Customer $customer, Request $request){
        $customer_id = $customer->id;
        $customer_job_id = $request->customer_job_id;
        $data = [
            'description' => (!empty($request->description) ? $request->description : null),
            'details' => (!empty($request->details) ? $request->details : null),
            'customer_job_priority_id' => (!empty($request->customer_job_priority_id) ? $request->customer_job_priority_id : null),
            'due_date' => (!empty($request->due_date) ? date('Y-m-d', strtotime($request->due_date)) : null),
            'customer_job_status_id' => (!empty($request->customer_job_status_id) ? $request->customer_job_status_id : null),
            'reference_no' => (!empty($request->reference_no) ? $request->reference_no : null),
            'estimated_amount' => (!empty($request->estimated_amount) ? $request->estimated_amount : null),
            'updated_by' => auth()->user()->id,
        ];
        $job = CustomerJob::where('id', $customer_job_id)->update($data);

        if($job):
            return response()->json(['msg' => 'Job successfully updated.', 'red' => route('customers.jobs.show', [$customer_id, $customer_job_id])], 200);
        else:
            return response()->json(['msg' => 'No changes found. Please change and update fields if need.', 'red' => ''], 304);
        endif;
    }

    public function destroy(Customer $customer, $customer_job_id){
        $customer = CustomerJob::find($customer_job_id)->delete();

        return response()->json(['msg' => 'Customer Job data successfully deleted.', 'red' => ''], 200);
    }

    public function restore(Customer $customer, $customer_job_id){
        $customer = CustomerJob::where('id', $customer_job_id)->withTrashed()->restore();

        return response()->json(['msg' => 'Customer Job data Successfully Restored!', 'red' => ''], 200);
    }


}
