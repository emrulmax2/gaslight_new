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
}
