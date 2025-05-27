<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerJobStoreRequest;
use App\Http\Requests\CustomerJobUpdateRequest;
use App\Models\CustomerJob;
use App\Models\CustomerJobCalendar;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CustomerJobsController extends Controller
{
    public function list(Request $request) {
        $status = $request->has('status') && $request->query('status') != '' ? $request->query('status') : 1;
        $searchKey = $request->has('search') && !empty($request->query('search')) ? $request->query('search') : '';
        $sortField = $request->has('sort') && !empty($request->query('sort')) ? $request->query('sort') : 'id';
        $sortOrder = $request->has('order') && !empty($request->query('order')) ? $request->query('order') : 'desc';
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);

        $query = CustomerJob::with('customer', 'property', 'priority', 'status', 'calendar', 'calendar.slot')->where('customer_id', $customer_id);

       $searchableColumns = Schema::getColumnListing((new CustomerJob)->getTable());
       if (!empty($searchKey)):
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        endif;
        if($status == 2):
            $query->onlyTrashed();
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
                    'reference_no' => (!empty($request->reference_no) ? $request->reference_no : null),
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

    public function getSingleCustomerJob($id) {
        try {
            $job = CustomerJob::with(['customer', 'property', 'property.customer', 'customer.contact'])->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'data' => $job
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'The requested job does not exist'
                ]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Something went wrong. Please try again later or contact with the administrator'
                ]
            ], 500);
        }
    }


    public function job_update(CustomerJobUpdateRequest $request) {

        try {
            $customer_job_id = $request->customer_job_id;
                $data = [
                    'description' => (!empty($request->description) ? $request->description : null),
                    'details' => (!empty($request->details) ? $request->details : null),
                    'customer_job_priority_id' => (!empty($request->customer_job_priority_id) ? $request->customer_job_priority_id : null),
                    'due_date' => (!empty($request->due_date) ? date('Y-m-d', strtotime($request->due_date)) : null),
                    'customer_job_status_id' => (!empty($request->customer_job_status_id) ? $request->customer_job_status_id : null),
                    'reference_no' => (!empty($request->reference_no) ? $request->reference_no : null),
                    'estimated_amount' => (!empty($request->estimated_amount) ? $request->estimated_amount : null),
                    'updated_by' => $request->user()->id,
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

            return response()->json([
                'success' => true,
                'message' => 'Job successfully updated.',
                'data' => $job
            ], 200);

        }catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'The requested job does not exist'
                ]
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Something went wrong. Please try again later or contact with the administrator'
                ]
            ], 500);
        }
        
    }
}
