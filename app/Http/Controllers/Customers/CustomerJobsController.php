<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerJobStoreRequest;
use App\Http\Requests\CustomerJobUpdateRequest;
use App\Http\Requests\JobAppointmentDataUpdateRequest;
use App\Http\Requests\JobStoreRequest;
use App\Models\CalendarTimeSlot;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
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

        $Query= $query->get();

        $html = '';
        if(!empty($Query)):
            foreach($Query as $list):
                $html .= '<a data-id="'.$list->id.'" href="'.route('customer.jobs.edit', [$list->customer_id, $list->id]).'" class="box relative jobItemWrap px-3 py-3 rounded-md block sm:flex w-full items-center mb-1">';
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
            'slots' => CalendarTimeSlot::where('active', 1)->orderBy('start', 'asc')->get(),
            'customer_contact_info' => CustomerContactInformation::where('customer_id', $request->customer_id)->firstOrFail(),
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
        $job->load(['customer', 'property', 'property.customer', 'customer.contact', 'calendar']);
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
            'job' => $job,
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
                ]
            );
        else:
            CustomerJobCalendar::where('customer_job_id', $job_id)->forceDelete();
        endif;

        return response()->json(['msg' => 'Job Appointment Deta successfully updated.'], 200);
    }
}
