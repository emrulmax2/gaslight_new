<?php

namespace App\Http\Controllers\Api\Jobs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CustomerJobCalenderStoreRequest;
use App\Http\Requests\Api\JobUpdateRequest;
use App\Models\Customer;
use App\Models\CustomerJob;
use App\Models\CustomerJobCalendar;
use App\Models\CustomerProperty;
use App\Models\ExistingRecordDraft;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;
use Illuminate\Support\Facades\Log;

class JobsController extends Controller
{
    public function list(Request $request)
    {
        $status = $request->filled('status') && $request->query('status') ? $request->query('status') : null;
        $searchKey = $request->has('search') && !empty($request->query('search')) ? $request->query('search') : '';
        $sortField = $request->has('sort') && !empty($request->query('sort')) ? $request->query('sort') : 'id';
        $sortOrder = $request->has('order') && !empty($request->query('order')) ? $request->query('order') : 'desc';
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';
        
        $query = CustomerJob::with('customer', 'property', 'priority', 'thestatus', 'calendar', 'calendar.slot')
                ->where('created_by', $request->user()->id);
        if($status > 0):
            $query->where('customer_job_status_id', $status);
        endif;
        
        $searchableColumns = Schema::getColumnListing((new CustomerJob())->getTable());

         if (!empty($searchKey)):
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        endif;

        $query->orderBy($sortField, $sortOrder);

        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);
        
        $jobs = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $jobs->items(),
            'meta' => [
                'total' => $jobs->total(),
                'per_page' => $jobs->perPage(),
                'current_page' => $jobs->currentPage(),
                'last_page' => $jobs->lastPage(),
                'from' => $jobs->firstItem(),
                'to' => $jobs->lastItem(),
            ]
        ]);
    }

    private function generateReferenceNo($customerId)
    {
        $customer = Customer::find($customerId);
        if (!$customer) return null;
        
        $nameParts = explode(' ', trim($customer->company_name));
        $prefix = '';
        foreach ($nameParts as $part):
            $prefix .= strtoupper(substr($part, 0, 1));
        endforeach;
        $lastJob = CustomerJob::where('customer_id', $customerId)->orderBy('id', 'desc')->first();

        if ($lastJob && preg_match('/\d+$/', $lastJob->reference_no, $matches)):
            $nextNumber = intval($matches[0]) + 1;
        else:
            $nextNumber = 1;
        endif;

        // $referenceNo = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $referenceNo = $prefix . $nextNumber;

        return $referenceNo;
    }



    public function storeCustomerJob(Request $request){

        $data = [
            'customer_id' => $request->customer_id,
            'customer_property_id' => $request->customer_property_id,
            'description' => (!empty($request->description) ? $request->description : null),
            'details' => (!empty($request->details) ? $request->details : null),
            //'customer_job_priority_id' => (!empty($request->customer_job_priority_id) ? $request->customer_job_priority_id : null),
            //'due_date' => (!empty($request->due_date) ? date('Y-m-d', strtotime($request->due_date)) : null),
            'customer_job_status_id' => 1,
            'reference_no' => $this->generateReferenceNo($request->customer_id),
            'estimated_amount' => (!empty($request->estimated_amount) ? $request->estimated_amount : null),
            'created_by' => $request->user()->id,
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

        return response()->json([
            'message' => 'Job successfully created.',
            'data' => $job
        ], 200);
      
    }
    public function storeJobCalendar(CustomerJobCalenderStoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $job = CustomerJob::findOrFail($validated['customer_job_id']);
            $existingCalendar = CustomerJobCalendar::where('customer_job_id', $validated['customer_job_id'])->latest()->first();

            $calendarData = [
                'customer_id' => $job->customer_id,
                'customer_job_id' => $validated['customer_job_id'],
                'date' => isset($validated['date']) ? date('Y-m-d', strtotime($validated['date'])) : null,
                'calendar_time_slot_id' => $validated['calendar_time_slot_id'] ?? null
            ];

            if ($existingCalendar) {
                $calendarData['updated_by'] = $request->user()->id;
                $existingCalendar->update($calendarData);
                $message = 'Calendar entry updated successfully';
            } else {
                $calendarData['status'] = 'New';
                $calendarData['created_by'] = $request->user()->id;
                $existingCalendar = CustomerJobCalendar::create($calendarData);
                $message = 'Job successfully added to calendar';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'calendar' => $existingCalendar,
                    'job' => $job,
                ]
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found',
                'error' => $e->getMessage()
            ], 404);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update calendar',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getJobDetails(Request $request, $id){
       try {
        $user = User::find($request->user()->id);
        $job = CustomerJob::with(['customer', 'property', 'property.customer', 'customer.contact', 'thestatus', 'calendar', 'calendar.slot'])
            ->withCount("records as number_of_records")
            ->findOrFail($id);

        $job->customer->makeHidden(["full_address_html", "full_address_with_html"]);
        $job->property->makeHidden(["full_address_html", "full_address_with_html"]);

        $calendarDate = (isset($job->calendar->date) && !empty($job->calendar->date) ? date('Y-m-d', strtotime($job->calendar->date)) : 0);
        $jobCalendarTimeSlotId = (isset($job->calendar->calendar_time_slot_id) && $job->calendar->calendar_time_slot_id > 0 ? $job->calendar->calendar_time_slot_id : '');
        $user = User:: find($job->created_by);
        $max_job_per_slot = (isset($user->max_job_per_slot) && $user->max_job_per_slot > 0 ? $user->max_job_per_slot : 1);

        $blocked = [];
        if(!empty($calendarDate)):
            $query = DB::table('customer_job_calendars as cjc')
                ->select('cjc.calendar_time_slot_id', DB::raw('count(cjc.id) as totalJob'))
                ->leftJoin('customer_jobs as cj', 'cjc.customer_job_id', 'cj.id')
                ->where('cjc.date', $calendarDate);
            if($jobCalendarTimeSlotId > 0):
                $query->where('cjc.calendar_time_slot_id', '!=', $jobCalendarTimeSlotId);
            endif;
            $result = $query->where('cj.created_by', $job->created_by)
                ->groupBy('cjc.calendar_time_slot_id')
                ->get();
            if($result->count() > 0):
                foreach($result as $res):
                    if($res->totalJob >= $max_job_per_slot):
                        $blocked[] = $res->calendar_time_slot_id;
                    endif;
                endforeach;
            endif;
        endif;


         return response()->json([
            'success' => true,
            'data' =>  [
                'job' => $job,
                'hasVat' => (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? true : false),
                'blocked' => $blocked
            ]
        ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found. . The requested Job (ID: '.$request->id.') does not exist or may have been deleted.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator'
            ], 500);
        }

    }
   public function update(JobUpdateRequest $request, $id)
    {
        $customer_job_id = $id;
        try {
            DB::beginTransaction();

            $job = CustomerJob::with(['customer', 'property', 'priority', 'calendar', 'calendar.slot'])
            ->withCount("records as number_of_records")
            ->findOrFail($id);

            $job->customer->makeHidden(["full_address_html", "full_address_with_html"]);
            $job->property->makeHidden(["full_address_html", "full_address_with_html"]);

            if ($job->created_by !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this job.'
                ], 403);
            }

           $updateData = [];

            if($request->has('customer_id') && $request->customer_id > 0):
                $updateData['customer_id'] = (isset($request->customer_id) && !empty($request->customer_id) ? $request->customer_id : null);
            endif;

            if($request->has('customer_property_id') && $request->customer_property_id > 0):
                $updateData['customer_property_id'] = (isset($request->customer_property_id) && !empty($request->customer_property_id) ? $request->customer_property_id : null);
            endif;

            if($request->has('description')):
                $updateData['description'] = (isset($request->description) && !empty($request->description) ? $request->description : null);
            endif;

            if($request->has('details')):
                $updateData['details'] = (isset($request->details) && !empty($request->details) ? $request->details : null);
            endif;

            if($request->has('estimated_amount')):
                $updateData['estimated_amount'] = (isset($request->estimated_amount) && !empty($request->estimated_amount) ? $request->estimated_amount : null);
            endif;

            if($request->has('customer_job_priority_id')):
                $updateData['customer_job_priority_id'] = (isset($request->customer_job_priority_id) && !empty($request->customer_job_priority_id) ? $request->customer_job_priority_id : null);
            endif;

            if($request->has('customer_job_status_id')):
                $updateData['customer_job_status_id'] = (isset($request->customer_job_status_id) && !empty($request->customer_job_status_id) ? $request->customer_job_status_id : null);
            endif;

            // if($request->has('reference_no')):
            //     $updateData['reference_no'] = (isset($request->reference_no) && !empty($request->reference_no) ? $request->reference_no : null);
            // endif;

            $job->update($updateData);

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

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Job updated successfully.',
                'data' => $job
            ], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Job not found. . The requested Job (ID: '.$request->id.') does not exist or may have been deleted.',
            ], 404);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }
    public function updateCustomerJobCalendar(Request $request, $customer_job_id){

        $job = CustomerJob::find($customer_job_id);
 
        $customer_job_calendar =   CustomerJobCalendar::updateOrCreate(
            ['customer_job_id' => $customer_job_id],
            [
                'customer_id' => $job->customer_id,
                'date' => !empty($request->job_calender_date) ? date('Y-m-d', strtotime($request->job_calender_date)) : null,
                'calendar_time_slot_id' => $request->calendar_time_slot_id ?? null,
            ]
        );

        return response()->json([
            'message' => 'Job calendar updated successfully',
            'data' => $customer_job_calendar
        ], 200);
   
    }

    public function getJobCalendarDetails(Request $request, $id){
        $data = CustomerJobCalendar::where('customer_job_id', $id)->orderBy('id', 'DESC')->get()->first();

        return response()->json([
            'data' => $data
        ], 200);
    }


    public function searchCustomers(Request $request)
    {
        $queryStr = $request->the_search_query ?? '';
        $allCustomer = $request->all_customer == 1;

        $query = Customer::with('title', 'contact')
            ->where('created_by', $request->user()->id);

        if (!$allCustomer) {
            $query->where(function($q) use($queryStr) {
                $q->where('full_name', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('company_name', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('vat_no', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('address_line_1', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('address_line_2', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('postal_code', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('city', 'LIKE', '%'.$queryStr.'%');
            });
        }

        $customers = $query->orderBy('full_name')->get();

        if ($customers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No customers found',
                'data' => []
            ], 200);
        }

        $formattedCustomers = $customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'type' => 'customer',
                'title' => $customer->customer_full_name,
                'full_name' => $customer->customer_full_name,
                'company_name' => $customer->company_name,
                'vat_no' => $customer->vat_no,
                'address' => [
                    'line_1' => $customer->address_line_1,
                    'line_2' => $customer->address_line_2,
                    'city' => $customer->city,
                    'postal_code' => $customer->postal_code,
                    'full_address' => implode(' ', array_filter([
                        $customer->address_line_1,
                        $customer->address_line_2,
                        $customer->city,
                        $customer->postal_code
                    ]))
                ],
                'contact' => $customer->contact ? [
                    'phone' => $customer->contact->phone,
                    'email' => $customer->contact->email
                ] : null
            ];
        });

        return response()->json([
            'success' => true,
            'count' => $customers->count(),
            'data' => $formattedCustomers
        ], 200);
    }

    public function searchAddress(Request $request)
    {
        $queryStr = $request->the_search_query ?? '';
        $customerId = $request->customer_id ?? 0;
    
        if (!is_numeric($customerId)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID',
                'data' => []
            ], 400);
        }
    
        $properties = CustomerProperty::with('customer')
            ->where('customer_id', $customerId)
            ->where(function($q) use($queryStr) {
                $q->where('address_line_1', 'LIKE', '%'.$queryStr.'%')
                  ->orWhere('address_line_2', 'LIKE', '%'.$queryStr.'%')
                  ->orWhere('postal_code', 'LIKE', '%'.$queryStr.'%')
                  ->orWhere('city', 'LIKE', '%'.$queryStr.'%');
            })
            ->orderBy('address_line_1', 'ASC')
            ->get();
    
        if ($properties->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No addresses found for this customer',
                'data' => []
            ], 200);
        }
    
        $formattedProperties = $properties->map(function ($property) {
            return [
                'id' => $property->id,
                'type' => 'address',
                'title' => implode(' ', array_filter([
                    $property->address_line_1,
                    $property->address_line_2,
                    $property->city,
                    $property->postal_code
                ])),
                'address' => [
                    'line_1' => $property->address_line_1,
                    'line_2' => $property->address_line_2,
                    'city' => $property->city,
                    'postal_code' => $property->postal_code,
                    'country' => $property->country,
                    'full_address' => implode(', ', array_filter([
                        implode(' ', array_filter([$property->address_line_1, $property->address_line_2])),
                        $property->city,
                        $property->postal_code,
                        $property->country
                    ]))
                ],
                'customer' => $property->customer ? [
                    'id' => $property->customer->id,
                    'name' => $property->customer->full_name ?? $property->customer->company_name
                ] : null
            ];
        });
    
        return response()->json([
            'success' => true,
            'count' => $properties->count(),
            'data' => $formattedProperties
        ], 200);
    }

    public function getCustomerAddresses(Request $request)
    {
        $customerId = $request->customer_id ?? 0;
    
        if (!is_numeric($customerId) || $customerId <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer ID provided',
                'data' => []
            ], 400);
        }
    
        $properties = CustomerProperty::with('customer')
            ->where('customer_id', $customerId)
            ->orderBy('address_line_1', 'ASC')
            ->get();
    
        if ($properties->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No addresses found for this customer',
                'data' => []
            ], 200);
        }
    
        $formattedProperties = $properties->map(function ($property) {
            return [
                'id' => $property->id,
                'type' => 'address',
                'title' => implode(', ', array_filter([
                    trim($property->address_line_1 . ' ' . $property->address_line_2),
                    $property->city,
                    $property->postal_code
                ])),
                'address' => [
                    'line_1' => $property->address_line_1,
                    'line_2' => $property->address_line_2,
                    'city' => $property->city,
                    'postal_code' => $property->postal_code,
                    'country' => $property->country,
                    'full_address' => implode(', ', array_filter([
                        trim($property->address_line_1 . ' ' . $property->address_line_2),
                        $property->city,
                        $property->postal_code,
                        $property->country
                    ]))
                ],
                'customer' => $property->customer ? [
                    'id' => $property->customer->id,
                    'name' => $property->customer->full_name ?? $property->customer->company_name
                ] : null,
                'created_at' => $property->created_at?->toDateTimeString(),
                'updated_at' => $property->updated_at?->toDateTimeString()
            ];
        });
    
        return response()->json([
            'success' => true,
            'count' => $properties->count(),
            'data' => $formattedProperties
        ], 200);
    }

    public function recordAndDraftsList(Request $request){
        $queryStr = (isset($request->queryStr) && !empty($request->queryStr) ? $request->queryStr : '');
        $status = (isset($request->status) && !empty($request->status) ? $request->status : '');
        $engineerId = (isset($request->engineerId) && !empty($request->engineerId) ? $request->engineerId : '');
        $certificateType = (isset($request->certificateType) && !empty($request->certificateType) ? $request->certificateType : '');
        $dateRange = (isset($request->dateRange) && !empty($request->dateRange) ? $request->dateRange : '');

        
        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;
    
        $query = ExistingRecordDraft::with('customer', 'job', 'job.property', 'form', 'user', 'model')
                ->whereHas('job', function($q) use ($request) {
                    $q->where('id', $request->job);
                })
                ->orderByRaw(implode(',', $sorts));
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
        if(!empty($certificateType) && $certificateType != 'all'):
            $query->where('job_form_id', $certificateType);
        endif;
        if(!empty($engineerId) && $engineerId != 'all'):
            $query->where('created_by', $engineerId);
        endif;
        if(!empty($status) && $status != 'all'):
            $query->whereHas('model', function($q) use($status){
                $q->where('status', $status);
            });
        endif;

        if(!empty($dateRange) && strpos($dateRange, ' - ') !== false):
            $dates = explode(' - ', $dateRange);
            $query->whereHas('model', function($q) use($dates){
                $q->whereBetween('created_at', [
                    date('Y-m-d 00:00:00', strtotime($dates[0])), 
                    date('Y-m-d 23:59:59', strtotime($dates[1]))
                ]);
            });
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
                    'id' => $i,
                    'landlord_name' => $list->customer->full_name ?? '',
                    'landlord_address' => $list->customer->full_address ?? '',
                    'inspection_address' => $list->job->property->full_address ?? '',
                    'certificate_type' => $list->form->name ?? '',
                    'created_at' => $list->model->created_at ? $list->model->created_at->format('jS M, Y \<b\r\/> \a\t h:i a') : '',
                    'status' => $list->model->status ?? '',
                    'actions' => $list->id,
                ];
                $i++;
            endforeach;
        endif;
        return response()->json(['last_page' => $last_page, 'data' => $data]); 
    }


    public function getCalendarSlotStatus(Request $request){
        $date = date('Y-m-d', strtotime($request->date));
        $user = User::find($request->user()->id);
        $max_job_per_slot = (isset($user->max_job_per_slot) && $user->max_job_per_slot > 0 ? $user->max_job_per_slot : 1);

        $customerJobSlots = DB::table('customer_job_calendars as cjc')
            ->select('cjc.calendar_time_slot_id', DB::raw('count(cjc.id) as totalJob'))
            ->leftJoin('customer_jobs as cj', 'cjc.customer_job_id', 'cj.id')
            ->where('date', $date)
            ->where('cj.created_by', $request->user()->id)
            ->groupBy('cjc.calendar_time_slot_id')
            ->get();

        return response()->json(['max' => $max_job_per_slot, 'jobs' => $customerJobSlots], 200);
    }
}
