<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobAddToCalendarRequest;
use App\Http\Requests\JobStoreRequest;
use App\Http\Requests\JobUpdateRequest;
use App\Models\CalendarTimeSlot;
use App\Models\Customer;
use App\Models\CustomerJob;
use App\Models\CustomerJobCalendar;
use App\Models\CustomerJobPriority;
use App\Models\CustomerJobStatus;
use App\Models\CustomerProperty;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Number;

class JobController extends Controller
{
    public function index(Request $request){
        return view('app.jobs.index', [
            'title' => 'Jobs - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Jobs', 'href' => 'javascript:void(0);'],
            ],
            'titles' => Title::where('active', 1)->orderBy('name', 'ASC')->get(),
            'priorities' => CustomerJobPriority::orderBy('id', 'ASC')->get(),
            'statuses' => CustomerJobStatus::orderBy('id', 'ASC')->get(),
            'slots' => CalendarTimeSlot::where('active', 1)->orderBy('start', 'asc')->get()
        ]);
    }

    public function list(Request $request){
        $queryStr = (isset($request->querystr) && !empty($request->querystr) ? $request->querystr : '');
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);
        $recordparams = (isset($request->recordparams) && !empty($request->recordparams) ? $request->recordparams : '');

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = CustomerJob::with('customer', 'property', 'priority', 'status', 'calendar', 'calendar.slot')->orderByRaw(implode(',', $sorts))->where('created_by', auth()->user()->id);
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
                $theUrl = (!empty($recordparams) ? route('records', [$recordparams, $list->id]) : route('jobs.show', $list->id));
                $data[] = [
                    'id' => $list->id,
                    'sl' => $i,
                    'url' => $theUrl,
                    'customer_id' => $list->customer_id,
                    'description' => $list->description,
                    'customer_full_name' => (isset($list->customer->customer_full_name) ? $list->customer->customer_full_name : ''),
                    'address_line_1' => (isset($list->property->address_line_1) && !empty($list->property->address_line_1) ? $list->property->address_line_1 : ''),
                    'address_line_2' => (isset($list->property->address_line_2) && !empty($list->property->address_line_2) ? $list->property->address_line_2 : ''),
                    'city' => (isset($list->property->city) && !empty($list->property->city) ? $list->property->city : ''),
                    'postal_code' => (isset($list->property->postal_code) && !empty($list->property->postal_code) ? $list->property->postal_code : ''),
                    'priority' => (isset($list->priority->name) && !empty($list->priority->name) ? $list->priority->name : ''),
                    'status' => (isset($list->status->name) && !empty($list->status->name) ? $list->status->name : ''),
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

    public function create(){
        return view('app.jobs.create', [
            'title' => 'Jobs - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Jobs', 'href' => route('jobs')],
                ['label' => 'New Job', 'href' => 'javascript:void(0);'],
            ],
            'titles' => Title::where('active', 1)->orderBy('name', 'ASC')->get(),
            'priorities' => CustomerJobPriority::orderBy('id', 'ASC')->get(),
            'statuses' => CustomerJobStatus::orderBy('id', 'ASC')->get(),
            'customers' => Customer::with('title')->where('created_by', auth()->user()->id)->orderBy('full_name', 'asc')->get(),
            'slots' => CalendarTimeSlot::where('active', 1)->orderBy('start', 'asc')->get()
        ]);
    }

    public function store(JobStoreRequest $request){
        dd($request->all());
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
            return response()->json([
                'msg' => 'Job successfully created.',
                'record' => $request->record,
                'red' => isset(request()->record) && !empty(request()->record) 
                    ? route('records', ['record' => request()->record,'job' => $job->id])
                    : route('jobs.show', $job->id)
            ], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator', 'red' => ''], 304);
        endif;
    }

    public function show(CustomerJob $job){
        $job->load(['customer', 'property', 'property.customer', 'customer.contact']);
        return view('app.jobs.show', [
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

    public function update(JobUpdateRequest $request){


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
            return response()->json([
                'msg' => 'Job successfully updated.',
                'record' => $request->record,
                'red' => isset(request()->record) && !empty(request()->record) 
                    ? route('jobs.show', ['job' => $job->id, 'record' => request()->record])
                    : route('jobs.show', $job->id)
            ], 200);
        else:
            return response()->json(['msg' => 'No changes found. Please change and update fields if need.', 'red' => ''], 304);
        endif;
    }

    public function getCalendarData(Request $request){
        $customer_job_id = $request->customer_job_id;
        $row = CustomerJobCalendar::where('customer_job_id', $customer_job_id)->orderBy('id', 'DESC')->get()->first();

        return response()->json(['row' => $row], 200);
    }

    public function addToCalendar(JobAddToCalendarRequest $request){
        $customer_job_id = $request->customer_job_id;
        $job = CustomerJob::find($customer_job_id);
        $row = CustomerJobCalendar::where('customer_job_id', $customer_job_id)->orderBy('id', 'DESC')->get()->first();
        
        $data = [
            'customer_id' => $job->customer_id,
            'customer_job_id' => $customer_job_id,
            'date' => (isset($request->date) && !empty($request->date) ? date('Y-m-d', strtotime($request->date)) : null),
            'calendar_time_slot_id' => (isset($request->calendar_time_slot_id) && $request->calendar_time_slot_id > 0 ? $request->calendar_time_slot_id : null)
        ];
        if(isset($row->id) && $row->id > 0):
            $data['updated_by'] = auth()->user()->id;
            $calendar = CustomerJobCalendar::where('customer_job_id', $customer_job_id)->where('id', $row->id)->update($data);
        else:
            $data['status'] = 'New';
            $data['created_by'] = auth()->user()->id;

            $calendar = CustomerJobCalendar::create($data);
        endif;

        return response()->json(['msg' => 'Job successfully added to the calendar.', 'red' => route('jobs.show', $customer_job_id)], 200);
    }


    public function searchCustomers(Request $request){
        $queryStr = (isset($request->the_search_query) && !empty($request->the_search_query) ? $request->the_search_query : '');
        $allCustomer = (isset($request->all_customer) && $request->all_customer == 1 ? true : false);

        $html = '';
        if($allCustomer):
            $query = Customer::with('title', 'contact')->where('created_by', auth()->user()->id)->orderBy('full_name')->get();
        else:
            $query = Customer::with('title', 'contact')->where('created_by', auth()->user()->id)->where(function($q) use($queryStr){
                        $q->where('full_name','LIKE','%'.$queryStr.'%')
                            ->orWhere('company_name','LIKE','%'.$queryStr.'%')->orWhere('vat_no','LIKE','%'.$queryStr.'%')
                            ->orWhere('address_line_1','LIKE','%'.$queryStr.'%')->orWhere('address_line_2','LIKE','%'.$queryStr.'%')
                            ->orWhere('postal_code','LIKE','%'.$queryStr.'%')->orWhere('city','LIKE','%'.$queryStr.'%');
                    })->orderBy('full_name')->get();
        endif;
        if($query->count() > 0):
            $html .= '<div class="py-2 px-5 text-xs font-semibold bg-slate-100 rounded-md rounded-bl-none rounded-br-none">'.($query->count() == 1 ? $query->count().' result' : $query->count().' results').' found</div>';
                $html .= '<div class="results px-5 py-4" style="max-height: 250px; overflow-y: auto;">';
                    $i = 1;
                    foreach($query as $customer):
                        $html .= '<div data-id="'.$customer->id.'" data-of="customer" data-title="'.$customer->customer_full_name.'" class="searchResultItems flex items-center cursor-pointer '.($i != $query->count() ? ' pb-3 border-b border-slate-100 mb-3' : '').'">';
                            $html .= '<div>';
                                $html .= '<div class="group flex items-center justify-center border rounded-full primary" style="width: 40px; height: 40px;">';
                                    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user" class="lucide lucide-user h-4 w-4 stroke-[1.3] text-primary"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
                                    //$html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" class="lucide lucide-map-pin h-4 w-4 stroke-[1.3] text-primary"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                                $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="ml-3.5 flex w-full flex-col gap-y-2 sm:flex-row sm:items-center">';
                                $html .= '<div>';
                                    $html .= '<div class="whitespace-nowrap font-medium">';
                                        $html .= $customer->customer_full_name;
                                    $html .= '</div>';
                                    $html .= '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">';
                                        $html .= $customer->address_line_1.' '.$customer->address_line_2.' '.$customer->city.', '.$customer->postal_code;
                                    $html .= '</div>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';

                        $i++;
                    endforeach;
                $html .= '</div>';
            $html .= '</div>';

            return response()->json(['suc' => 1, 'html' => $html], 200);
        else:
            return response()->json(['suc' => 2, 'html' => ''], 200);
        endif;
    }

    public function searchAddress(Request $request){
        $queryStr = (isset($request->the_search_query) && !empty($request->the_search_query) ? $request->the_search_query : '');
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);

        $html = '';
        $query = CustomerProperty::with('customer')->where('customer_id', $customer_id)->where(function($q) use($queryStr){
                    $q->where('address_line_1','LIKE','%'.$queryStr.'%')->orWhere('address_line_2','LIKE','%'.$queryStr.'%')
                        ->orWhere('postal_code','LIKE','%'.$queryStr.'%')->orWhere('city','LIKE','%'.$queryStr.'%');
                })->orderBy('address_line_1', 'ASC')->get();
        if($query->count() > 0):
            $html .= '<div class="py-2 px-5 text-xs font-semibold bg-slate-100 rounded-md rounded-bl-none rounded-br-none">'.($query->count() == 1 ? $query->count().' result' : $query->count().' results').' found</div>';
                $html .= '<div class="results px-5 py-4" style="max-height: 250px; overflow-y: auto;">';
                    $i = 1;
                    foreach($query as $property):
                        $html .= '<div data-id="'.$property->id.'" data-of="address" data-title="'.$property->address_line_1.' '.$property->address_line_2.', '.$property->city.', '.$property->postal_code.'" class="searchResultItems flex items-center cursor-pointer '.($i != $query->count() ? ' pb-3 border-b border-slate-100 mb-3' : '').'">';
                            $html .= '<div>';
                                $html .= '<div class="group flex items-center justify-center border rounded-full primary" style="width: 40px; height: 40px;">';
                                    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" class="lucide lucide-map-pin h-4 w-4 stroke-[1.3] text-primary"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                                $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="ml-3.5 flex w-full flex-col gap-y-2 sm:flex-row sm:items-center">';
                                $html .= '<div>';
                                    $html .= '<div class="whitespace-nowrap font-medium">';
                                        $html .= $property->address_line_1.' '.$property->address_line_2;
                                    $html .= '</div>';
                                    $html .= '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">';
                                        $html .= $property->city.', '.$property->postal_code.', '.$property->country;
                                    $html .= '</div>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';

                        $i++;
                    endforeach;
                $html .= '</div>';
            $html .= '</div>';

            return response()->json(['suc' => 1, 'html' => $html], 200);
        else:
            return response()->json(['suc' => 2, 'html' => ''], 200);
        endif;
    }

    public function getCustomerAddresses(Request $request){
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);

        $html = '';
        $query = CustomerProperty::with('customer')->where('customer_id', $customer_id)->orderBy('address_line_1', 'ASC')->get();
        if($query->count() > 0):
            $html .= '<div class="py-2 px-5 text-xs font-semibold bg-slate-100 rounded-md rounded-bl-none rounded-br-none">'.($query->count() == 1 ? $query->count().' result' : $query->count().' results').' found</div>';
                $html .= '<div class="results px-5 py-4" style="max-height: 250px; overflow-y: auto;">';
                    $i = 1;
                    foreach($query as $property):
                        $html .= '<div data-id="'.$property->id.'" data-of="address" data-title="'.$property->address_line_1.' '.$property->address_line_2.', '.$property->city.', '.$property->postal_code.'" class="searchResultItems flex items-center cursor-pointer '.($i != $query->count() ? ' pb-3 border-b border-slate-100 mb-3' : '').'">';
                            $html .= '<div>';
                                $html .= '<div class="group flex items-center justify-center border rounded-full primary" style="width: 40px; height: 40px;">';
                                    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" class="lucide lucide-map-pin h-4 w-4 stroke-[1.3] text-primary"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                                $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="ml-3.5 flex w-full flex-col gap-y-2 sm:flex-row sm:items-center">';
                                $html .= '<div>';
                                    $html .= '<div class="whitespace-nowrap font-medium">';
                                        $html .= $property->address_line_1.' '.$property->address_line_2;
                                    $html .= '</div>';
                                    $html .= '<div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">';
                                        $html .= $property->city.', '.$property->postal_code.', '.$property->country;
                                    $html .= '</div>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';

                        $i++;
                    endforeach;
                $html .= '</div>';
            $html .= '</div>';

            return response()->json(['suc' => 1, 'html' => $html], 200);
        else:
            return response()->json(['suc' => 2, 'html' => ''], 200);
        endif;
    }
}
