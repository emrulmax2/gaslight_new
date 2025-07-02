<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobAddToCalendarRequest;
use App\Http\Requests\JobAppointmentDataUpdateRequest;
use App\Http\Requests\JobStoreRequest;
use App\Http\Requests\JobUpdateRequest;
use App\Models\CalendarTimeSlot;
use App\Models\Customer;
use App\Models\CustomerJob;
use App\Models\CustomerJobCalendar;
use App\Models\CustomerJobPriority;
use App\Models\CustomerJobStatus;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\JobForm;
use App\Models\Title;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

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

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = CustomerJob::with('customer', 'property', 'priority', 'status', 'calendar', 'calendar.slot')->orderByRaw(implode(',', $sorts))->where('status', 'Due')->where('created_by', auth()->user()->id);
        if(!empty($queryStr)):
            $query->where(function($q) use($queryStr){
                $q->where('description','LIKE','%'.$queryStr.'%')->orWhere('details','LIKE','%'.$queryStr.'%')
                    ->orWhere('reference_no','LIKE','%'.$queryStr.'%');
            });
        endif;
        $Query= $query->get();

        $html = '';
        if(!empty($Query)):
            foreach($Query as $list):
                $html .= '<a data-id="'.$list->id.'"  data-status="'.$list->status.'"  show_url="'.route('jobs.show', $list->id).'" class="JobListItem box relative jobItemWrap px-3 py-3 rounded-md block sm:flex w-full items-center mb-1 cursor-pointer">';
                    $html .= '<div class="w-full sm:w-3/6">';
                        $html .= '<div class="font-medium text-dark leading-none mb-1 flex justify-start items-start">';
                            $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="notebook-pen" style="top: -2px;" class="lucide lucide-notebook-pen stroke-1.5 mr-2 h-4 w-4 relative text-slate-500"><path d="M13.4 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7.4"></path><path d="M2 6h4"></path><path d="M2 10h4"></path><path d="M2 14h4"></path><path d="M2 18h4"></path><path d="M21.378 5.626a1 1 0 1 0-3.004-3.004l-5.01 5.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"></path></svg>';
                            $html .= '<span>'.$list->description.'</span>';
                        $html .= '</div>';
                        $html .= '<div class="font-medium text-slate-500 text-sm leading-none flex justify-start items-start">';
                            $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user" class="lucide lucide-user stroke-1.5 mr-2 h-3 w-4"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
                            $html .= '<span>'.(isset($list->customer->customer_full_name) ? $list->customer->customer_full_name : '').'</span>';
                        $html .= '</div>';
                        $html .= '<div class=" text-slate-500 leading-[1.2] text-xs mt-2 flex justify-start items-start">';
                            $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="map-pin" class="lucide lucide-map-pin stroke-1.5 mr-2 h-3 w-4 relative"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                            $html .= '<span>'.(isset($list->property->full_address) && !empty($list->property->full_address) ? $list->property->full_address : '').'</span>';
                        $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="border-t sm:border-t-0 border-l-0 sm:border-l  pl-0 sm:pl-5 mt-2 sm:mt-0 pt-2 sm:pt-0">';
                        $html .= '<div class="text-slate-500 leading-none mb-1.5 text-xs flex justify-start items-center">';
                            $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="list-check" class="lucide lucide-list-check stroke-1.5 mr-2 h-4 w-4"><path d="M11 18H3"></path><path d="m15 18 2 2 4-4"></path><path d="M16 12H3"></path><path d="M16 6H3"></path></svg>';
                            $html .= '<span>'.(isset($list->priority->name) && !empty($list->priority->name) ? $list->priority->name : 'Normal').'</span>';
                        $html .= '</div>';
                        $html .= '<div class=" text-slate-500 leading-none mb-1.5 text-xs flex justify-start items-center">';
                            $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="circle-check" class="lucide lucide-circle-check stroke-1.5 mr-2 h-4 w-4"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>';
                            $html .= '<span>'.(isset($list->status->name) && !empty($list->status->name) ? $list->status->name : 'N/A').'</span>';
                        $html .= '</div>';
                        $html .= '<div class=" text-slate-500 leading-none text-xs font-medium flex justify-start items-center">';
                            $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="badge-pound-sterling" class="lucide lucide-badge-pound-sterling stroke-1.5 mr-2 h-4 w-4"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"></path><path d="M8 12h4"></path><path d="M10 16V9.5a2.5 2.5 0 0 1 5 0"></path><path d="M8 16h7"></path></svg>';
                            $html .= '<span>'.(!empty($list->estimated_amount) ? Number::currency($list->estimated_amount, in: 'GBP') : Number::currency(0, in: 'GBP')).'</span>';
                        $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="ml-auto max-sm:absolute right-12px max-sm:bottom-[24px]">';
                        if(isset($list->calendar->id) && $list->calendar->id > 0):
                            $calDate = (isset($list->calendar->date) && !empty($list->calendar->date) ? date('d', strtotime($list->calendar->date)) : '');
                            $calMonth = (isset($list->calendar->date) && !empty($list->calendar->date) ? date('M', strtotime($list->calendar->date)) : '');
                            $calColor = (isset($list->calendar->slot->color_code) && !empty($list->calendar->slot->color_code) ? $list->calendar->slot->color_code : '#84cc16');
                            $html .= '<button data-customer="'.$list->customer_id.'" data-id="'.$list->id.'" data-tw-toggle="modal" data-tw-target="#addJobCalenderModal" class="addCalenderBtn addedCalBtn border-0 rounded-[3px] bg-slate-200 text-dark p-0 w-[36px] h-[36px] inline-block text-center ml-1">';
                                $html .= '<span style="background: '.$calColor.'" class="block rounded-t-[3px] -mt-[3px] bg-success py-[1px] text-center text-white whitespace-nowrap font-medium uppercase leading-[1.2] text-[10px]">'.$calMonth.'</span>';
                                $html .= '<span style="color: '.$calColor.'" class="block leading-[1] pt-[5px] text-[14px] font-bold">'.$calDate.'</span>';
                            $html .= '</button>';
                        else:
                            $html .= '<button data-customer="'.$list->customer_id.'" data-id="'.$list->id.'" data-tw-toggle="modal" data-tw-target="#addJobCalenderModal" class="addCalenderBtn rounded-full bg-success text-white p-0 w-[30px] sm:w-[36px] h-[30px] sm:h-[36px] inline-flex justify-center items-center ml-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" data-lucide="calendar-days" class="lucide lucide-calendar-days w-3 sm:w-4 h-3 sm:h-4"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line><path d="M8 14h.01"></path><path d="M12 14h.01"></path><path d="M16 14h.01"></path><path d="M8 18h.01"></path><path d="M12 18h.01"></path><path d="M16 18h.01"></path></svg></button>';
                        endif;
                    $html .= '</div>';
                $html .= '</a>';
            endforeach;
        else:
            $html .= '<div role="alert" class="alert relative border rounded-md px-5 py-4 bg-pending border-pending bg-opacity-20 border-opacity-5 text-pending dark:border-pending dark:border-opacity-20 mb-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-octagon" class="lucide lucide-alert-octagon stroke-1.5 mr-2 h-6 w-6"><path d="M12 16h.01"></path><path d="M12 8v4"></path><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"></path></svg>
                        No match found.
                    </div>';
        endif;
        
        return response()->json(['html' => $html]);
    }

    public function create(){
        $user = User::find(auth()->user()->id);
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
            'slots' => CalendarTimeSlot::where('active', 1)->orderBy('start', 'asc')->get(),
            'hasVat' => (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? true : false)
        ]);
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
        $user = User::find(auth()->user()->id);
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
            'job' => $job,
            'hasVat' => (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? true : false)
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

    public function recordAndDrafts(CustomerJob $job, Request $request){
        $job->load(['customer', 'property', 'property.customer', 'customer.contact']);
        $engineers = User::whereHas('companies', function($query) {
                        $query->where('companies.user_id', Auth::id());
                    })->select('id', 'name')->get();
        $certificate_types = JobForm::where('parent_id', '!=',  0)->where('active', 1)->orderBy('id', 'ASC')->get();
        return view('app.jobs.record-drafts', [
            'title' => 'Record & Drafts - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Record & Drafts', 'href' => 'javascript:void(0);'],
            ],
            'engineers' => $engineers,
            'certificate_types' => $certificate_types,
            'titles' => Title::where('active', 1)->orderBy('name', 'ASC')->get(),
            'priorities' => CustomerJobPriority::orderBy('id', 'ASC')->get(),
            'statuses' => CustomerJobStatus::orderBy('id', 'ASC')->get(),
            'slots' => CalendarTimeSlot::where('active', 1)->orderBy('start', 'asc')->get(),
            'job' => $job
        ]);
    }

    public function recordAndDraftsList(Request $request){
        $queryStr = (isset($request->queryStr) && !empty($request->queryStr) ? $request->queryStr : '');
        $job_id = (isset($request->job) && !empty($request->job) ? $request->job : 0);

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;
    
        $query = ExistingRecordDraft::with('customer', 'job', 'job.property', 'form', 'user', 'model')->orderByRaw(implode(',', $sorts))
                 ->where('customer_job_id', $job_id);
        if (!empty($queryStr)):
            $query->whereHas('customer', function ($q) use ($queryStr) {
                $q->where(function($sq) use($queryStr){
                    $sq->where('full_name', 'LIKE', '%' . $queryStr . '%')->orWhere('address_line_1', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('address_line_2', 'LIKE', '%'.$queryStr.'%')->orWhere('postal_code', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('city', 'LIKE', '%'.$queryStr.'%');
                });
            })->orWhereHas('job.property', function ($q) use ($queryStr) {
                $q->where(function($sq) use($queryStr){
                    $sq->where('occupant_name', 'LIKE', '%' . $queryStr . '%')->orWhere('address_line_1', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('address_line_2', 'LIKE', '%'.$queryStr.'%')->orWhere('postal_code', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('city', 'LIKE', '%'.$queryStr.'%');
                });
            });
        endif;
        $query->where('created_by', auth()->user()->id);
        $Query = $query->get();

        $html = '';

        if(!empty($Query)):
            foreach($Query as $list):
                $certificate_number = '';
                if(isset($list->model->certificate_number) && !empty($list->model->certificate_number)):
                    $certificate_number = $list->model->certificate_number;
                elseif(isset($list->model->invoice_number) && !empty($list->model->invoice_number)):
                    $certificate_number = $list->model->quote_number;
                elseif(isset($list->model->quote_number) && !empty($list->model->quote_number)):
                    $certificate_number = $list->model->quote_number;
                endif;
                $theModel = $list->model->getMorphClass();
                switch($list->job_form_id){
                    case(4):
                        $url = route('invoice.show', $list->model_id);
                        break;
                    case(3):
                        $url = route('quote.show', $list->model_id);
                        break;
                    case(6):
                        $url = route('new.records.gsr.view', $list->model_id);
                        break;
                    case(7):
                        $url = route('new.records.glsr.view', $list->model_id);
                        break;
                    case(8):
                        $url = route('new.records.gas.warning.notice.show', $list->model_id);
                        break;
                    case(9):
                        $url = route('new.records.gas.service.show', $list->model_id);
                        break;
                    case(10):
                        $url = route('new.records.gas.breakdown.record.show', $list->model_id);
                        break;
                    case(13):
                        $url = route('new.records.gas.bscc.record.show', $list->model_id);
                        break;
                    case(15):
                        $url = route('new.records.gas.power.flush.record.show', $list->model_id);
                        break;
                    case(16):
                        $url = route('new.records.gcdr.show', $list->model_id);
                        break;
                    case(17):
                        $url = route('new.records.guhwcr.show', $list->model_id);
                        break;
                    case(18):
                        $url = route('new.records.gjsr.show', $list->model_id);
                        break;
                    default:
                        $url = '';
                        break;
                }

                $status = $list->model->status ?? '';
                $html .= '<tr data-url="'.$url.'" class="recordRow intro-x box border max-sm:px-3 max-sm:pt-2 max-sm:pb-2 max-sm:mb-[10px] shadow-[5px_3px_5px_#00000005] rounded">';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2 rounded-tl-none sm:rounded-tl rounded-bl-none sm:rounded-bl">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Type</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">'.($list->form->name ?? '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Serial No</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">'.$certificate_number.'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Inspection Name</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto capitalize">'.($list->job->property->occupant_name ?? ($list->customer->full_name ?? '')).'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start flex-wrap">';
                            $html .= '<label class="sm:hidden mb-1.5 font-medium m-0 flex-zero-full">Inspection Address</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] max-sm:ml-auto flex-zero-full">'.($list->job->property->full_address ?? '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Landlord Name</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto capitalize">'.($list->customer->full_name ?? '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start flex-wrap">';
                            $html .= '<label class="sm:hidden mb-1.5 font-medium m-0 flex-zero-full">Landlord Address</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] max-sm:ml-auto flex-zero-full">'.($list->customer->full_address ?? '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Assigned To</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto capitalize">'.(isset($list->model->user->name) ? $list->model->user->name : '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Created At</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal text-xs leading-[1.3] max-sm:ml-auto">'.($list->model->created_at ? $list->model->created_at->format('Y-m-d h:i A') : '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 border-none px-0 sm:px-3 py-3 sm:py-2 rounded-tr-none sm:rounded-tr rounded-br-none sm:rounded-br">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Status</label>';
                            if($status == 'Cancelled'){
                                $html .= '<button class="ml-auto font-medium bg-danger rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Cancelled</button>';
                            }else if($status == 'Approved & Sent'){
                                $html .= '<button class="ml-auto font-medium bg-success rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Approved & Sent</button>';
                            }else if($status == 'Approved'){
                                $html .= '<button class="ml-auto font-medium bg-primary rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Approved</button>';
                            }else{
                                $html .= '<button class="ml-auto font-medium bg-pending rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">'.$status.'</button>';
                            }
                        $html .= '</div>';
                    $html .= '</td>';
                $html .= '</tr>';
            endforeach;
        else:
            $html .= '<tr data-url="" class="intro-x box bg-pending bg-opacity-10 border border-pending border-opacity-5 max-sm:mb-[10px] shadow-[5px_3px_5px_#00000005] rounded">';
                $html .= '<td colspan="9" class="border-b dark:border-darkmode-300 border-none px-3 py-3 rounded">';
                    $html .= '<div class="flex justify-center items-center text-pending">';
                        $html .= 'No matching records found!';
                    $html .= '</div>';
                $html .= '</td>';
            $html .= '</tr>';
        endif;
        return response()->json(['html' => $html], 200);
    }

    public function updateJobsData(Request $request){
        $job_id = $request->id;
        $value = (isset($request->fieldValue) && !empty($request->fieldValue) ? ($request->fieldName == 'due_date' ? (!empty($request->fieldValue) ? date('Y-m-d', strtotime($request->fieldValue)) : null) : $request->fieldValue) : null);
        $field = $request->fieldName;

        if($job_id > 0 && $field != ''):
            $property = CustomerJob::find($job_id);
            $property->update([$field => $value]);

            return response()->json(['msg' => 'Customer Job data successfully updated.'], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator.'], 304);
        endif;
    }

    public function updateJobsAppointmentData(JobAppointmentDataUpdateRequest $request){
        $job_id = $request->id;
        $job = CustomerJob::find($job_id);
        if(!empty($request->job_calender_date) && !empty($request->calendar_time_slot_id)):
            CustomerJobCalendar::updateOrCreate(['customer_job_id' => $job_id], [
                    'customer_id' => $job->customer_id,
                    'date' => !empty($request->job_calender_date) ? date('Y-m-d', strtotime($request->job_calender_date)) : null,
                    'calendar_time_slot_id' => $request->calendar_time_slot_id ?? null,
            ]);
        else:
            CustomerJobCalendar::where('customer_job_id', $job_id)->forceDelete();
        endif;

        return response()->json(['msg' => 'Job Appointment Deta successfully updated.'], 200);
    }
}
