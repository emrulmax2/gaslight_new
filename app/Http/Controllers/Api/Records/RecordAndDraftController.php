<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\Invoice;
use App\Models\JobFormPrefixMumbering;
use App\Models\Quote;
use App\Models\Record;
use App\Models\User;
use Illuminate\Http\Request;

class RecordAndDraftController extends Controller
{
    public function list($job_form_id, Request $request)
    {
        $user_id = $request->user()->id;
        $status = ($request->has('status') && !empty($request->query('status')) ? $request->query('status') : 'All');
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'id';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? strtolower($request->query('order')) : 'asc';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';
        $customer_job_id = ($request->has('customer_job_id') && !empty($request->query('customer_job_id'))) ? $request->query('customer_job_id') : 0;
        $customer_id = ($request->has('customer_id') && !empty($request->query('customer_id'))) ? $request->query('customer_id') : 0;

        $query = Record::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'occupant', 'billing'])
                 ->where('created_by', $user_id);
        if($status != 'All'):
            $query->where('status', $status);
        endif;
        if(isset($job_form_id) && $job_form_id > 0):
            $query->where('job_form_id', $job_form_id);
        endif;
        if(isset($customer_job_id) && $customer_job_id > 0):
            $query->where('customer_job_id', $customer_job_id);
        endif;
        if(isset($customer_id) && $customer_id > 0):
            $query->where('customer_id', $customer_id);
        endif;

        if (!empty($searchKey)) {
            $query->where(function($q) use ($searchKey) {
                $q->where('certificate_number', 'LIKE', '%' . $searchKey . '%')
                ->orWhereHas('customer', function ($customerQuery) use ($searchKey) {
                    $customerQuery->where('full_name', 'LIKE', '%' . $searchKey . '%');
                })->orWhereHas('customer.address', function($customerAddrQuery) use($searchKey){
                    $customerAddrQuery->orWhere('address_line_1', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('address_line_2', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('postal_code', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('city', 'LIKE', '%' . $searchKey . '%');
                })->orWhereHas('job.property', function ($propertyQuery) use ($searchKey) {
                    $propertyQuery->orWhere('address_line_1', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('address_line_2', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('postal_code', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('city', 'LIKE', '%' . $searchKey . '%');
                })->orWhereHas('occupant', function ($propertyQuery) use ($searchKey) {
                    $propertyQuery->orWhere('occupant_name', 'LIKE', '%' . $searchKey . '%');
                });
            });
        }

        $validSortFields = ['id', 'created_at', 'updated_at', 'inspection_date', 'next_inspection_date'];
        $sortField = in_array($sortField, $validSortFields) ? $sortField : 'id';
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc';
        $query->orderBy($sortField, $sortOrder);

        $records = $query->get();
        return response()->json([
            'success' => true,
            'data' => $records,
            'meta' => [
                'total' => $records->count(),
                'per_page' => -1,
                'current_page' => 1,
                'last_page' => 1
            ]
        ]);
    }


    public function getInvoiceNumber(Request $request){
        $user_id = $request->user()->id;
        $form_id = 4;

        $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form_id)->orderBy('id', 'DESC')->get()->first();
        $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
        $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
        $userLastInvoice = Invoice::where('job_form_id', $form_id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
        $lastInvoiceNo = (isset($userLastInvoice->invoice_number) && !empty($userLastInvoice->invoice_number) ? $userLastInvoice->invoice_number : '');

        // $invSerial = $starting_form;
        // if(!empty($lastInvoiceNo)):
        //     preg_match("/(\d+)/", $lastInvoiceNo, $invoiceNumbers);
        //     $invSerial = (int) $invoiceNumbers[1] + 1;
        // endif;
        // $invoiceNumber = $prifix.str_pad($invSerial, 6, '0', STR_PAD_LEFT);

        $invSerial = $starting_form;
        if(!empty($lastInvoiceNo)):
            preg_match("/(\d+)/", $lastInvoiceNo, $invoiceNumbers);
            $invSerial = isset($invoiceNumbers[1]) ? ((int) $invoiceNumbers[1]) + 1 : $starting_form;
        endif;
        $invoiceNumber = $prifix . $invSerial;

        return response()->json(['invoiceNumber' => $invoiceNumber], 200);
    }

    public function getQuoteNumber(Request $request){
        $user_id = $request->user()->id;
        $form_id = 3;

        $prifixs = JobFormPrefixMumbering::where('user_id', $user_id)->where('job_form_id', $form_id)->orderBy('id', 'DESC')->get()->first();
        $prifix = (isset($prifixs->prefix) && !empty($prifixs->prefix) ? $prifixs->prefix : '');
        $starting_form = (isset($prifixs->starting_from) && !empty($prifixs->starting_from) ? $prifixs->starting_from : 1);
        $userLastQuote = Quote::where('job_form_id', $form_id)->where('created_by', $user_id)->orderBy('id', 'DESC')->get()->first();
        $lastQuoteNo = (isset($userLastQuote->quote_number) && !empty($userLastQuote->quote_number) ? $userLastQuote->quote_number : '');

        // $invSerial = $starting_form;
        // if(!empty($lastQuoteNo)):
        //     preg_match("/(\d+)/", $lastQuoteNo, $quoteNumbers);
        //     $invSerial = (int) $quoteNumbers[1] + 1;
        // endif;
        // $quoteNumber = $prifix.str_pad($invSerial, 6, '0', STR_PAD_LEFT);

        $qutSerial = $starting_form;
        if(!empty($lastQuoteNo)):
            preg_match("/(\d+)/", $lastQuoteNo, $quoteNumbers);
            $qutSerial = isset($quoteNumbers[1]) ? ((int) $quoteNumbers[1]) + 1 : $starting_form;
        endif;
        $quoteNumber = $prifix . $qutSerial;

        return response()->json(['quoteNumber' => $quoteNumber], 200);
    }


    public function getJobs(Request $request)
    {
        $user_id = $request->user()->id;
        $job_form_id = $request->form_id;
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';

        $jobsQuery = CustomerJob::with('customer', 'property', 'thestatus')
            ->where('created_by', $user_id)
            ->orderBy('id', 'DESC');

        if(!$searchKey) {
            $jobsQuery->where(function($query) use ($searchKey) {
                $query->where('description', 'like', '%' . $searchKey . '%')
                    ->orWhereHas('customer', function($q) use ($searchKey) {
                        $q->where('full_name', 'like', '%' . $searchKey . '%');
                    });
            });
        }

        $jobs = $jobsQuery->get();

        if($jobs->count() > 0) {
            $filteredJobs = [];
            foreach ($jobs as $job) {
                $recordExist = Record::where('customer_job_id', $job->id)
                    ->where('job_form_id', $job_form_id)
                    ->where('created_by', $user_id)
                    ->exists();
                
                if (!$recordExist) {
                    $filteredJobs[] = $job;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $filteredJobs
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'No jobs founds.'
        ], 200);
    }
}