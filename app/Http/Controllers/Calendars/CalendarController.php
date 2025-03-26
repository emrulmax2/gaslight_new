<?php

namespace App\Http\Controllers\Calendars;

use App\Http\Controllers\Controller;
use App\Models\CustomerJobCalendar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(){
        return view('app.calendars.index', [
            'title' => 'Calendar - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Calendar', 'href' => 'javascript:void(0);'],
            ]
        ]);
    }

    public function events(Request $request){
        $user_id = auth()->user()->id;
        $start = Carbon::create($request->input('start'))->format('Y-m-d');
        $end = Carbon::create($request->input('end'))->format('Y-m-d');

        $events = [];
        $Query = CustomerJobCalendar::with('job', 'job.customer', 'job.property', 'slot')->whereBetween('date', [$start, $end])->whereHas('job', function($q) use($user_id){
                    $q->where('created_by', $user_id);
                })->orderBy('date', 'ASC')->get();
        if($Query->isNotEmpty()):
            $i = 0;
            foreach($Query as $list):
                $slotStart = $list->slot->start;
                $slotEnd = $list->slot->end;
                $start = date('Y-m-d H:i:s', strtotime($list->date.' '.$slotStart));
                $end = date('Y-m-d H:i:s', strtotime($list->date.' '.$slotEnd));

                $titleHtml = (isset($list->job->customer->customer_full_name) && !empty($list->job->customer->customer_full_name) ? '<div class="font-medium mb-1">'.$list->job->customer->customer_full_name.'</div>' : '');
                $titleHtml .= (isset($list->job->property->full_address) && !empty($list->job->property->full_address) ? '<div class="text-xs text-slate-500 whitespace-normal break-words">'.$list->job->property->full_address.'</div>' : '');
                $events[$i]['title'] = (isset($list->job->customer->customer_full_name) && !empty($list->job->customer->customer_full_name) ? $list->job->customer->customer_full_name : '');
                $events[$i]['start'] = $start;
                $events[$i]['end'] = $end;
                $events[$i]['allDay'] = 0;
                $events[$i]['htmlTitle'] = $titleHtml;
                $events[$i]['backgroundColor'] = (isset($list->slot->color_code) && !empty($list->slot->color_code) ? $list->slot->color_code : '#1e3a8a');
                $events[$i]['borderColor'] = (isset($list->slot->color_code) && !empty($list->slot->color_code) ? $list->slot->color_code : '#1e3a8a');
                $events[$i]['textColor'] = '#FFFFFF';
                $i++;
            endforeach;
        endif;

        return response()->json($events, 200);
    }
}
