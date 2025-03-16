<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerJobStoreRequest;
use App\Http\Requests\CustomerJobUpdateRequest;
use App\Http\Requests\JobStoreRequest;
use App\Models\CalendarTimeSlot;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerJob;
use App\Models\CustomerJobCalendar;
use App\Models\CustomerJobPriority;
use App\Models\CustomerJobStatus;
use App\Models\Title;
use Illuminate\Support\Number;

class CustomerJobsController extends Controller
{
    public function index(Request $request)
    {
        return view('app.customers.jobs.index', [
            'title' => 'Customers - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Customers', 'href' => route('customers')],
                ['label' => 'Jobs', 'href' => 'javascript:void(0);'],
            ],
            'customer' => Customer::where('id', $request->customer_id)->first(),
            'priorities' => CustomerJobPriority::orderBy('id', 'ASC')->get(),
            'statuses' => CustomerJobStatus::orderBy('id', 'ASC')->get(),
            'slots' => CalendarTimeSlot::where('active', 1)->orderBy('start', 'asc')->get()
        ]);
    }

    public function list(Request $request) {
        $queryStr = (isset($request->querystr) && !empty($request->querystr) ? $request->querystr : '');
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = CustomerJob::with('customer', 'property', 'priority', 'status', 'calendar', 'calendar.slot')->orderByRaw(implode(',', $sorts))->where('customer_id', $customer_id);
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
                    'deleted_at' => $list->deleted_at,
                    'calendar_added' => (isset($list->calendar->id) && $list->calendar->id > 0 ? 1 : 0),
                    'calendar_date' => (isset($list->calendar->date) && !empty($list->calendar->date) ? date('d_M', strtotime($list->calendar->date)) : ''),
                    'calendar_color' => (isset($list->calendar->slot->color_code) && !empty($list->calendar->slot->color_code) ? $list->calendar->slot->color_code : '#84cc16')
                ];
                $i++;
            endforeach;
        endif;
        
        return response()->json(['last_page' => $last_page, 'data' => $data]);
    }

    public function job_create(Request $request) {
        return view('app.customers.jobs.create',[
            'title' => 'Jobs - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Jobs', 'href' => route('jobs')],
                ['label' => 'New Job', 'href' => 'javascript:void(0);'],
            ],
            'titles' => Title::where('active', 1)->orderBy('name', 'ASC')->get(),
            'priorities' => CustomerJobPriority::orderBy('id', 'ASC')->get(),
            'statuses' => CustomerJobStatus::orderBy('id', 'ASC')->get(),
            'customer' => Customer::where('id', $request->customer_id)->firstOrFail(),
            'slots' => CalendarTimeSlot::where('active', 1)->orderBy('start', 'asc')->get()
        ]);
    }

    public function job_store(CustomerJobStoreRequest $request) {
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

        if(!empty($request->job_calender_date) && !empty($request->calendar_time_slot_id)):
            CustomerJobCalendar::Create(
                [
                    'customer_id' => $job->customer_id,
                    'customer_job_id' => $job->id,
                    'date' => !empty($request->job_calender_date) ? date('Y-m-d', strtotime($request->job_calender_date)) : null,
                    'calendar_time_slot_id' => $request->calendar_time_slot_id ?? null,
                ]
        );
        endif;

        if($job->id):
            return response()->json(['msg' => 'Job successfully created.', 'red' => route('customer.jobs', $request->customer_id)], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
    }

    public function job_edit(Request $request) {
        $job = CustomerJob::where('id', $request->customer_job_id)->firstOrFail();
        $job->load(['customer', 'property', 'property.customer', 'customer.contact']);
        return view('app.customers.jobs.show', [
            'title' => 'Jobs - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Jobs', 'href' => route('jobs')],
                ['label' => 'Show', 'href' => 'javascript:void(0);'],
            ],
            'titles' => Title::where('active', 1)->orderBy('name', 'ASC')->get(),
            'priorities' => CustomerJobPriority::orderBy('id', 'ASC')->get(),
            'statuses' => CustomerJobStatus::orderBy('id', 'ASC')->get(),
            'slots' => CalendarTimeSlot::where('active', 1)->orderBy('start', 'asc')->get(),
            'job' => $job
        ]);
    }

    public function job_update(CustomerJobUpdateRequest $request) {
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
        $job = CustomerJob::find($customer_job_id);
        $job->update($data);

        if(!empty($request->job_calender_date) && !empty($request->calendar_time_slot_id)):
            CustomerJobCalendar::updateOrCreate(
                ['customer_job_id' => $customer_job_id],
                [
                    'customer_id' => $job->customer_id,
                    'date' => !empty($request->job_calender_date) ? date('Y-m-d', strtotime($request->job_calender_date)) : null,
                    'calendar_time_slot_id' => $request->calendar_time_slot_id ?? null,
                ]
            );
        endif;

        if($job):
            return response()->json(['msg' => 'Job successfully updated.', 'red' => route('customer.jobs.edit', ['customer_id' => $request->customer_id, 'customer_job_id' => $customer_job_id])], 200);
        else:
            return response()->json(['msg' => 'No changes found. Please change and update fields if need.', 'red' => ''], 304);
        endif;
    }
}
