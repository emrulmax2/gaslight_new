<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\CustomerJob;
use App\Models\ExistingRecordDraft;
use App\Models\Invoice;
use App\Models\JobFormPrefixMumbering;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;

class RecordAndDraftController extends Controller
{
    public function list(Request $request)
    {
        $status = ($request->has('status') && ($request->query('status') != '') ? $request->query('status') : 1);
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'id';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? strtolower($request->query('order')) : 'asc';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';

        $query = ExistingRecordDraft::with('customer', 'job', 'job.property', 'form', 'user', 'model')
                    ->where('created_by', $request->user()->id);

        if ($status == 2) {
            $query->onlyTrashed();
        }

        if (!empty($searchKey)) {
            $query->where(function($q) use ($searchKey) {
                $q->whereHas('customer', function ($customerQuery) use ($searchKey) {
                    $customerQuery->where('full_name', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('address_line_1', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('address_line_2', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('postal_code', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('city', 'LIKE', '%' . $searchKey . '%');
                })->orWhereHas('job.property', function ($propertyQuery) use ($searchKey) {
                    $propertyQuery->where('occupant_name', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('address_line_1', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('address_line_2', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('postal_code', 'LIKE', '%' . $searchKey . '%')
                    ->orWhere('city', 'LIKE', '%' . $searchKey . '%');
                });
            });
        }

        $validSortFields = ['id', 'created_at', 'updated_at'];
        $sortField = in_array($sortField, $validSortFields) ? $sortField : 'id';
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc';
        
        $query->orderBy($sortField, $sortOrder);

        $limit = max(1, (int)$request->query('limit', 10));
        $page = max(1, (int)$request->query('page', 1));
        $records = $query->paginate($limit, ['*'], 'page', $page);

        $responseData = [];
        foreach ($records->items() as $record) {
            $certificate_number = '';
            if (!empty($record->model->certificate_number)) {
                $certificate_number = $record->model->certificate_number;
            } elseif (!empty($record->model->invoice_number)) {
                $certificate_number = $record->model->invoice_number;
            } elseif (!empty($record->model->quote_number)) {
                $certificate_number = $record->model->quote_number;
            }

            $responseData[] = [
                'id' => $record->id,
                'type' => $record->form->name ?? '',
                'certificate_number' => $certificate_number,
                'inspection_name' => $record->job->property->occupant_name ?? ($record->customer->full_name ?? ''),
                'inspection_address' => $record->job->property->full_address ?? '',
                'landlord_name' => $record->customer->full_name ?? '',
                'landlord_address' => $record->customer->full_address ?? '',
                'assigned_to' => $record->model->user->name ?? '',
                'created_at' => $record->model->created_at ? $record->model->created_at->format('Y-m-d h:i A') : '',
                'status' => $record->model->status ?? '',
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $responseData,
            'meta' => [
                'total' => $records->total(),
                'per_page' => $records->perPage(),
                'current_page' => $records->currentPage(),
                'last_page' => $records->lastPage(),
                'from' => $records->firstItem(),
                'to' => $records->lastItem(),
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
        $user_id = $request->user_id;
        $job_form_id = $request->form_id;
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';

        $jobsQuery = CustomerJob::with('customer', 'property', 'status')
            ->where('created_by', $user_id)
            ->orderBy('id', 'DESC');

        if ($searchKey) {
            $jobsQuery->where(function($query) use ($searchKey) {
                $query->where('description', 'like', '%' . $searchKey . '%')
                    ->orWhereHas('customer', function($q) use ($searchKey) {
                        $q->where('full_name', 'like', '%' . $searchKey . '%')
                            ->orWhere('postal_code', 'like', '%' . $searchKey . '%');
                    });
            });
        }

        $jobs = $jobsQuery->get();

        if ($jobs->count() > 0) {
            $filteredJobs = [];
            foreach ($jobs as $job) {
                $recordExist = ExistingRecordDraft::where('customer_job_id', $job->id)
                    ->where('job_form_id', $job_form_id)
                    ->exists();
                
                if (!$recordExist) {
                    $filteredJobs[] = [
                        'id' => $job->id,
                        'customer_id' => $job->customer_id,
                        'customer_property_id' => $job->customer_property_id,
                        'description' => $job->description ?? '',
                        'customer_name' => $job->customer->full_name ?? '',
                        'postal_code' => $job->customer->postal_code ?? '',
                        'status' => $job->status ? $job->status : null,
                        'property' => $job->property ? [
                            'id' => $job->property->id,
                            'customer_id' => $job->property->customer_id,
                            'address_line_1' => $job->property->address_line_1 ?? '',
                            'address_line_2' => $job->property->address_line_2 ?? '',
                            'city' => $job->property->city ?? '',
                            'state' => $job->property->state ?? '',
                            'postal_code' => $job->property->postal_code ?? '',
                            'country' => $job->property->country ?? '',
                            'latitude' => $job->property->latitude ?? '',
                            'longitude' => $job->property->longitude ?? '',
                            'note' => $job->property->note ?? '',
                            'occupant_name' => $job->property->occupant_name ?? '',
                            'occupant_email' => $job->property->occupant_email ?? '',
                            'occupant_phone' => $job->property->occupant_phone ?? '',
                        ] : null,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $filteredJobs
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'No jobs found.'
        ], 200);
    }

    public function vatStatusNumber(Request $request){
        $user_id = $request->user_id;
        $user = User::find($user_id);

        $data = [
            'non_vat_status' => (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? 0 : 1),
            'vat_number' => (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? $user->companies[0]->vat_number : ''),
        ];


         return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

}