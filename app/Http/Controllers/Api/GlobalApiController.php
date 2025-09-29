<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalendarTimeSlot;
use App\Models\CancelReason;
use App\Models\CustomerJobPriority;
use App\Models\CustomerJobStatus;
use App\Models\JobForm;
use App\Models\Title;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlobalApiController extends Controller
{
    public function getTitles(Request $request)
    {
        $query = Title::query();
        $status = ($request->has('status') && ($request->query('status') != '')) ? $request->query('status') : 1;
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'name';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? $request->query('order') : 'asc';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';
        $searchableColumns = ['name'];
        
        if ($status == 2):
            $query->onlyTrashed();
        else:
            $query->where('active', $status);
        endif;
        
         if (!empty($searchKey)):
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        endif;
        
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'asc';
        $query->orderBy($sortField, $sortOrder);
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);
        
        $titles = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $titles->items(),
            'meta' => [
                'total' => $titles->total(),
                'per_page' => $titles->perPage(),
                'current_page' => $titles->currentPage(),
                'last_page' => $titles->lastPage(),
                'from' => $titles->firstItem(),
                'to' => $titles->lastItem(),
            ]
        ]);
    }
    public function getJobPriorities(Request $request): JsonResponse
    {
        $query = CustomerJobPriority::query();

        $status = ($request->has('status') && ($request->query('status') != '')) ? $request->query('status') : 1;
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'name';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? $request->query('order') : 'asc';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';
        $searchableColumns = ['name'];

        if ($status == 2):
            $query->onlyTrashed();
        else:
            $query->where('active', $status);
        endif;
        
         if (!empty($searchKey)):
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        endif;
        
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'asc';
        $query->orderBy($sortField, $sortOrder);
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);
        
        $job_priorities = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $job_priorities->items(),
            'meta' => [
                'total' => $job_priorities->total(),
                'per_page' => $job_priorities->perPage(),
                'current_page' => $job_priorities->currentPage(),
                'last_page' => $job_priorities->lastPage(),
                'from' => $job_priorities->firstItem(),
                'to' => $job_priorities->lastItem(),
            ]
        ]);
    }
    public function getJobStatus(Request $request): JsonResponse
    {
        $query = CustomerJobStatus::query();

        $status = ($request->has('status') && ($request->query('status') != '')) ? $request->query('status') : 1;
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'name';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? $request->query('order') : 'asc';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';
        $searchableColumns = ['name'];
        
        if ($status == 2):
            $query->onlyTrashed();
        else:
            $query->where('active', $status);
        endif;
        
         if (!empty($searchKey)):
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        endif;
        
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'asc';
        $query->orderBy($sortField, $sortOrder);
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);
        
        $job_status = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $job_status->items(),
            'meta' => [
                'total' => $job_status->total(),
                'per_page' => $job_status->perPage(),
                'current_page' => $job_status->currentPage(),
                'last_page' => $job_status->lastPage(),
                'from' => $job_status->firstItem(),
                'to' => $job_status->lastItem(),
            ]
        ]);
    }
    public function getCalendarTimeSlots(Request $request): JsonResponse
    {

         $query = CalendarTimeSlot::query();

        $status = ($request->has('status') && ($request->query('status') != '')) ? $request->query('status') : 1;
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'id';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? $request->query('order') : 'asc';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';

        $searchableColumns = ['title'];

        
        if ($status == 2):
            $query->onlyTrashed();
        else:
            $query->where('active', $status);
        endif;
        
         if (!empty($searchKey)):
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        endif;
        
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'asc';
        $query->orderBy($sortField, $sortOrder);
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);
        
        $time_slots = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $time_slots->items(),
            'meta' => [
                'total' => $time_slots->total(),
                'per_page' => $time_slots->perPage(),
                'current_page' => $time_slots->currentPage(),
                'last_page' => $time_slots->lastPage(),
                'from' => $time_slots->firstItem(),
                'to' => $time_slots->lastItem(),
            ]
        ]);
    }

    public function getRecordsList(Request $request): JsonResponse
    {
        $query = JobForm::with('childs');

        $status = ($request->has('status') && ($request->query('status') != '')) ? $request->query('status') : 1;
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'id';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? $request->query('order') : 'asc';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';

        $searchableColumns = ['name'];

        
        if ($status == 2):
            $query->onlyTrashed();
        else:
            $query->where('active', $status);
        endif;
        
         if (!empty($searchKey)):
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        endif;
        
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'asc';
        $query->orderBy($sortField, $sortOrder);
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);
        
        $time_slots = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $time_slots->items(),
            'meta' => [
                'total' => $time_slots->total(),
                'per_page' => $time_slots->perPage(),
                'current_page' => $time_slots->currentPage(),
                'last_page' => $time_slots->lastPage(),
                'from' => $time_slots->firstItem(),
                'to' => $time_slots->lastItem(),
            ]
        ]);
    }
    public function getCancelReasons(Request $request): JsonResponse
    {
        $query = CancelReason::query();

        $status = ($request->has('status') && ($request->query('status') != '')) ? $request->query('status') : 1;
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'name';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? $request->query('order') : 'asc';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';
        $searchableColumns = ['name'];
        
        if ($status == 2):
            $query->onlyTrashed();
        else:
            $query->where('active', $status);
        endif;
        
         if (!empty($searchKey)):
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        endif;
        
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'asc';
        $query->orderBy($sortField, $sortOrder);
        
        $job_status = $query->get();

        return response()->json([
            'data' => $job_status,
            'meta' => [
                'total' => $job_status->count(),
            ]
        ]);
    }
}
