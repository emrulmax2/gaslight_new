<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\ExistingRecordDraft;
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


    public function download($id){
        return response()->json([
            'success' => false,
            'message' => 'Not active yet'
        ]);
    }
}