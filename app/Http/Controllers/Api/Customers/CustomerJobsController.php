<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerJobStoreRequest;
use App\Http\Requests\CustomerJobUpdateRequest;
use App\Models\Customer;
use App\Models\CustomerJob;
use App\Models\CustomerJobCalendar;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomerJobsController extends Controller
{
    public function list(Request $request) {
        $validStatuses = ['Due', 'Completed', 'Cancelled', 'Trashed'];
        $status = $request->filled('status') && in_array($request->query('status'), $validStatuses) ? $request->query('status') : 'Due';
        $searchKey = $request->has('search') && !empty($request->query('search')) ? $request->query('search') : '';
        $sortField = $request->has('sort') && !empty($request->query('sort')) ? $request->query('sort') : 'id';
        $sortOrder = $request->has('order') && !empty($request->query('order')) ? $request->query('order') : 'desc';
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);

        $query = CustomerJob::with('customer', 'property', 'priority', 'thestatus', 'calendar', 'calendar.slot')
        ->where('customer_id', $customer_id);

       $searchableColumns = Schema::getColumnListing((new CustomerJob)->getTable());
       if (!empty($searchKey)):
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        endif;

         if ($status !== 'Trashed') {
            $query->where('status', $status);
        } else {
            $query->onlyTrashed();
        }

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

    public function job_store(CustomerJobStoreRequest $request) {

        try {
            $data = [
                    'customer_id' => $request->customer_id,
                    'customer_property_id' => $request->customer_property_id,
                    'description' => (!empty($request->description) ? $request->description : null),
                    'details' => (!empty($request->details) ? $request->details : null),
                    'customer_job_priority_id' => (!empty($request->customer_job_priority_id) ? $request->customer_job_priority_id : null),
                    'due_date' => (!empty($request->due_date) ? date('Y-m-d', strtotime($request->due_date)) : null),
                    'customer_job_status_id' => (!empty($request->customer_job_status_id) ? $request->customer_job_status_id : null),
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
                'success' => true,
                'message' => 'Job successfully created.',
                'data' => $job
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Something went wrong. Please try again later or contact with the administrator'
                ]
            ], 500);
        }
    }

    public function getSingleCustomerJob(Request $request, $id) {
           try {
            $job = CustomerJob::with(['customer', 'property', 'property.customer', 'customer.contact', 'thestatus', 'calendar', 'calendar.slot'])
                    ->withCount("records as number_of_records")
                    ->where('id', $id)->first();

            $job->customer->makeHidden(["full_address_html", "full_address_with_html"]);
            $job->property->makeHidden(["full_address_html", "full_address_with_html"]);

            if ($job->created_by !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to access this job.'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $job
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found. . The requested Job (ID: '.$request->id.') does not exist or may have been deleted.'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }


   public function job_update(CustomerJobUpdateRequest $request, $customer_job_id)
    {
        try {
            DB::beginTransaction();

            $job = CustomerJob::with(['customer', 'property', 'priority', 'status', 'calendar', 'calendar.slot'])->findOrFail($customer_job_id);
            $job->customer->makeHidden(["full_address_html", "full_address_with_html"]);
            $job->property->makeHidden(["full_address_html", "full_address_with_html"]);

            if ($job->created_by !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this job.'
                ], 403);
            }

            $updateData = [];

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
                'message' => 'Customer job successfully updated',
                'data' => $job
            ], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Customer job not found. . The requested Customer job (ID: '.$customer_job_id.') does not exist or may have been deleted.',
            ], 404);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }


    public function jobStatusUpdate(Request $request, $job_id)
    {
        DB::beginTransaction();
        try {
            $status = isset($request->status) && $request->status > 0 ? $request->status : null;
        
            if (empty($status)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status. '.$status.' are not allowed.',
                ], 422);
            }

            $job = CustomerJob::findOrFail($job_id);
            $job->update([
                'customer_job_status_id' => $status,
                'cancel_reason_id' => null,
                'cancel_reason_note' => null
            ]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer job status successfully updated',
                'data' => $job
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Customer job not found. . The requested Customer job (ID: '.$job_id.') does not exist or may have been deleted.',
            ], 404);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }


    public function cancelJob(Request $request, $job_id)
    {
        DB::beginTransaction();
        try {
            $status = isset($request->status) && $request->status == 3 ? $request->status : null;
            $cancel_reason_id = isset($request->cancel_reason_id) && $request->cancel_reason_id > 0 ? $request->cancel_reason_id : null;
            $cancel_reason_note = isset($request->cancel_reason_note) && !empty($request->cancel_reason_note) ? $request->cancel_reason_note : null;
        
            if ($status != 3 || empty($cancel_reason_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data. Status should be 3 & reason should not be empty.',
                ], 422);
            }

            $job = CustomerJob::findOrFail($job_id);
            $job->update([
                'customer_job_status_id' => $status,
                'cancel_reason_id' => $cancel_reason_id,
                'cancel_reason_note' => $cancel_reason_note
            ]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer job cancell reason successfully updated',
                'data' => $job
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Customer job not found. . The requested Customer job (ID: '.$job_id.') does not exist or may have been deleted.',
            ], 404);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later or contact with the administrator',
            ], 500);
        }
    }
}
