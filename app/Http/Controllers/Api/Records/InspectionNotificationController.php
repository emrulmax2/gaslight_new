<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Models\Record;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class InspectionNotificationController extends Controller
{
    public function list(Request $request){
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $year = (int) $request->input('year', now()->year);
        $userCreatedYear = Carbon::parse($user->created_at)->year;
        $currentYear = now()->year;

        // Previous Year
        $previousYear = (($year - 1) >= $userCreatedYear) ? $year - 1 : null;

        // Next Year
        $nextYear = (($year + 1) <= $currentYear) ? $year + 1 : null;

        return response()->json([
            'data' => get_due_inspection_counts($year),
            'year' => $year,
            'previousYear' => $previousYear,
            'nextYear' => $nextYear
        ], 200);
    }

    public function monthlyList(Request $request){
        $user_id = $request->user_id;
        $monthYear = $request->month_year ?? date('m-Y');

        try {
            $date = Carbon::createFromFormat('m-Y', $monthYear);
        } catch (\Exception $e) {
            $date = now();
        }

        $user = User::find($user_id);
        $date = Carbon::createFromFormat('m-Y', $monthYear);

        $start = $date->copy()->startOfMonth();
        $end   = $date->copy()->endOfMonth();

        $records = Record::query()
            ->where('created_by', $user_id)
            ->whereNotNull('next_inspection_date')
            ->whereBetween('next_inspection_date', [$start, $end])
            ->whereIn('job_form_id', [6, 9])
            ->with([
                'customer',
                'property',
                'billing',
                'inspectionNotifications'
            ])

            ->orderBy('next_inspection_date', 'asc')
            ->get();

        $events = [];

        foreach ($records as $record) {
            $notification = $record->inspectionNotifications
                ->where('inspection_date', $record->next_inspection_date)
                ->first();

            $emailSent = $notification ? true : false;
            $emailSentDate = $notification->created_at ?? null;

            $events[] = [
                'id' => $record->id,
                'id' => $record->id,
                'certificate_number' => $record->certificate_number,
                'prev_inspection_date' => $record->inspection_date,
                'next_inspection_date' => $record->next_inspection_date,
                'status' => $record->status,
                'customer' => $record->customer ?? [],
                'property' => $record->property ?? [],
                'billing' => $record->billing ?? [],
                'url' => route('records.show', $record->id),
                'email_sent' => $emailSent,
                'email_sent_date' => $emailSentDate,
            ];
        }

        return response()->json([
            'data' => $events,
            'count' => $records->count()
        ], 200);
    }
}
