<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerJobStoreRequest;
use App\Http\Requests\CustomerJobUpdateRequest;
use App\Models\Customer;
use App\Models\CustomerJob;
use App\Models\CustomerJobCalendar;
use App\Models\Invoice;
use App\Models\InvoiceOption;
use App\Models\JobFormPrefixMumbering;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomerJobsController extends Controller
{
    public function list(Request $request) {
        $status = $request->filled('status') && $request->query('status') ? $request->query('status') : 0;
        $searchKey = $request->has('search') && !empty($request->query('search')) ? $request->query('search') : '';
        $sortField = $request->has('sort') && !empty($request->query('sort')) ? $request->query('sort') : 'id';
        $sortOrder = $request->has('order') && !empty($request->query('order')) ? $request->query('order') : 'desc';
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';
        $customer_id = (isset($request->customer_id) && $request->customer_id > 0 ? $request->customer_id : 0);

        $query = CustomerJob::with('customer', 'property', 'priority', 'thestatus', 'calendar', 'calendar.slot', 'invoice', 'billing')
        ->where('customer_id', $customer_id);

       $searchableColumns = Schema::getColumnListing((new CustomerJob)->getTable());
       if (!empty($searchKey)):
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        endif;

        if($status > 0):
            $query->where('customer_job_status_id', $status);
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

    private function generateReferenceNo($customerId, $company){
        $customer = Customer::find($customerId);
        if (!$customer) return null;
        
        $nameParts = (isset($company->company_name) && !empty($company->company_name) ? explode(' ', $company->company_name) : []);
        //$nameParts = explode(' ', trim($customer->company_name));
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
        $referenceNo = $prefix . $nextNumber;

        return $referenceNo;
    }

    public function job_store(CustomerJobStoreRequest $request) {

        try {
            $user_id = $request->user()->id;
            $user = User::find($user_id);
            $company = (isset($user->companies[0]) && !empty($user->companies[0]) ? $user->companies[0] : []);
            $data = [
                    'customer_id' => $request->customer_id,
                    'billing_address_id' => $request->billing_address_id ?? null,
                    'customer_property_id' => $request->customer_property_id,
                    'description' => (!empty($request->description) ? $request->description : null),
                    'details' => (!empty($request->details) ? $request->details : null),
                    'customer_job_priority_id' => (!empty($request->customer_job_priority_id) ? $request->customer_job_priority_id : null),
                    'due_date' => (!empty($request->due_date) ? date('Y-m-d', strtotime($request->due_date)) : null),
                    'customer_job_status_id' => (!empty($request->customer_job_status_id) ? $request->customer_job_status_id : null),
                    'reference_no' => $this->generateReferenceNo($request->customer_id, $company),
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
            $job = $job->fresh(['customer', 'property', 'property.customer', 'customer.contact', 'thestatus', 'calendar', 'calendar.slot', 'billing']);

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
            $job = CustomerJob::with(['customer', 'property', 'property.customer', 'customer.contact', 'thestatus', 'calendar', 'calendar.slot', 'billing'])
                    ->withCount("records as number_of_records")->withCount('invoice as number_of_invoices')
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

            $job = CustomerJob::with(['customer', 'property', 'priority', 'status', 'calendar', 'calendar.slot', 'billing'])->findOrFail($customer_job_id);
            $job->customer->makeHidden(["full_address_html", "full_address_with_html"]);
            $job->property->makeHidden(["full_address_html", "full_address_with_html"]);

            if ($job->created_by !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this job.'
                ], 403);
            }

            $updateData = [];

            if($request->has('customer_id')):
                $updateData['customer_id'] = (isset($request->customer_id) && !empty($request->customer_id) ? $request->customer_id : null);
            endif;
            if($request->has('billing_address_id')):
                $updateData['billing_address_id'] = (isset($request->billing_address_id) && !empty($request->billing_address_id) ? $request->billing_address_id : null);
            endif;

            if($request->has('customer_property_id')):
                $updateData['customer_property_id'] = (isset($request->customer_property_id) && !empty($request->customer_property_id) ? $request->customer_property_id : null);
            endif;

            if($request->has('description')):
                $updateData['description'] = (isset($request->description) && !empty($request->description) ? $request->description : null);
            endif;

            if($request->has('details')):
                $updateData['details'] = (isset($request->details) && !empty($request->details) ? $request->details : null);
            endif;

            if($request->has('customer_job_status_id')):
                $updateData['customer_job_status_id'] = (isset($request->customer_job_status_id) && !empty($request->customer_job_status_id) ? $request->customer_job_status_id : null);
            endif;

            if($request->has('estimated_amount')):
                $updateData['estimated_amount'] = (isset($request->estimated_amount) && !empty($request->estimated_amount) ? $request->estimated_amount : null);
            endif;

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
            $job = $job->fresh(['customer', 'property', 'property.customer', 'customer.contact', 'thestatus', 'calendar', 'calendar.slot', 'billing']);

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


    public function markAsComplete(Request $request){
        try {
            $customer_job_id = $request->customer_job_id;
            $customer_job_status_id = $request->customer_job_status_id;
            $user_id = $request->user()->id;

            $invoice = DB::transaction(function () use ($customer_job_id, $customer_job_status_id, $user_id) {
                $user = User::find($user_id);
                $job = CustomerJob::find($customer_job_id);
                $company = (isset($user->companies[0]) && !empty($user->companies[0]) ? $user->companies[0] : []);

                $non_vat_status = (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? 0 : 1);
                $vat_number = (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? $user->companies[0]->vat_number : '');
                
                $vat_rate = 20;
                $unit_price = (isset($job->estimated_amount) && $job->estimated_amount > 0 ? $job->estimated_amount : 0);
                $unit = 1; 
                $subTotal = $unit_price * $unit;
                $vatAmount = ($non_vat_status ? 0 : ($subTotal / $vat_rate) * 100);
                $lineTotal = $subTotal + $vatAmount; 

                $invoice = Invoice::create([
                    'company_id' => $company->id,
                    'customer_id' => $job->customer_id,
                    'customer_job_id' => $job->id,
                    'job_form_id' => 4,
                    'customer_property_id' => (isset($job->customer_property_id) && $job->customer_property_id > 0 ? $job->customer_property_id : null),
                    
                    'issued_date' => date('Y-m-d'),
                    'expire_date' => date('Y-m-d', strtotime("+30 days")),
                    
                    'updated_by' => $user_id,
                ]);
                $invoice_number = $this->generateInvoiceNumber($invoice->id);

                $invoiceItems[1] = [
                    'vat' => $vat_rate,
                    'edit' => 0,
                    'price' => $unit_price,
                    'units' => $unit,
                    'line_total' => $lineTotal,
                    'description' => (isset($job->description) && !empty($job->description) ? $job->description : 'Invoice Item 01'),
                    'inv_item_title' => (isset($job->description) && !empty($job->description) ? $job->description : 'Invoice Item 01'),
                    'inv_item_serial' => 1
                ];
                InvoiceOption::create([
                    'invoice_id' => $invoice->id,
                    'name' => 'invoiceItems',
                    'value' => $invoiceItems
                ]);

                $invoiceExtra = [
                    'non_vat_invoice' => $non_vat_status,
                    'vat_number' => $vat_number,
                ];
                if(isset($company->bank->payment_term) && !empty($company->bank->payment_term)):
                    $invoiceExtra['payment_term'] = (isset($company->bank->payment_term) && !empty($company->bank->payment_term) ? $company->bank->payment_term : '');
                else:
                    $invoiceExtra['payment_term'] = '';
                endif;
                InvoiceOption::create([
                    'invoice_id' => $invoice->id,
                    'name' => 'invoiceExtra',
                    'value' => $invoiceExtra
                ]);

                return $invoice;
            });
            $job = CustomerJob::where('id', $customer_job_id)->update([
                'customer_job_status_id' => $customer_job_status_id,
                'cancel_reason_id' => null,
                'cancel_reason_note' => null,
                'updated_by' => $request->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => (isset($invoice->id) && $invoice->id > 0) ? 'Invoice created and Customer job successfully mark as completed.' :'Customer job successfully mark as completed.',
                'job' => $job,
                'invoice' => $invoice
            ], 200);
        }catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }


    public function generateInvoiceNumber($invoice_id){
        $invoice = Invoice::find($invoice_id);
        $user_id = $invoice->created_by;
        if(empty($invoice->invoice_number)):
            $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $invoice->job_form_id)->orderBy('id', 'DESC')->get()->first();
            $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
            $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
            $userLastInvoice = Invoice::where('created_by', $user_id)->where('id', '!=', $invoice_id)->orderBy('id', 'DESC')->get()->first();
            $lastInvoiceNo = (isset($userLastInvoice->invoice_number) && !empty($userLastInvoice->invoice_number) ? $userLastInvoice->invoice_number : '');

             $cerSerial = $starting_form;
            if(!empty($lastInvoiceNo)):
                preg_match("/(\d+)/", $lastInvoiceNo, $invoiceNumbers);
                $cerSerial = isset($invoiceNumbers[1]) ? ((int) $invoiceNumbers[1]) + 1 : $starting_form;
            endif;
            $invoiceNumber = $prifix . $cerSerial;
            Invoice::where('id', $invoice_id)->update(['invoice_number' => $invoiceNumber]);

            return $invoiceNumber;
        else:
            return false;
        endif;
    }
}
