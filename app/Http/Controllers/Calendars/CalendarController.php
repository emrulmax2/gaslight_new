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
        $start = Carbon::create($request->input('start'));
        $end = Carbon::create($request->input('end'));

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

                $titleHtml = (isset($list->job->customer->full_name) && !empty($list->job->customer->full_name) ? '<div class="font-medium mb-1">'.$list->job->customer->full_name.'</div>' : '');
                $titleHtml .= (isset($list->job->property->full_address) && !empty($list->job->property->full_address) ? '<div class="text-xs text-slate-500 whitespace-normal break-words">'.$list->job->property->full_address.'</div>' : '');
                $data[$i]['title'] = (isset($list->job->customer->full_name) && !empty($list->job->customer->full_name) ? $list->job->customer->full_name : '');
                $data[$i]['start'] = $start;
                $data[$i]['end'] = $end;
                $data[$i]['allDay'] = 0;
                $data[$i]['htmlTitle'] = $titleHtml;
                $data[$i]['backgroundColor'] = '#1e3a8a';
                $data[$i]['textColor'] = '#FFFFFF';
                $i++;
            endforeach;
        endif;

        $eventOutput = [
            'timeZone' => 'UTC',
            'events' => $events
        ];
        return response()->json($data, 200);
    }
}
