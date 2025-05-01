<?php

namespace App\Http\Controllers\Drafts;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\ExistingRecordDraft;
use App\Models\GasBoilerSystemCommissioningChecklist;
use App\Models\GasBreakdownRecord;
use App\Models\GasCommissionDecommissionRecord;
use App\Models\GasPowerFlushRecord;
use App\Models\GasSafetyRecord;
use App\Models\GasServiceRecord;
use App\Models\GasUnventedHotWaterCylinderRecord;
use App\Models\GasWarningNotice;
use App\Models\Invoice;
use App\Models\JobForm;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RecordAndDraftController extends Controller
{
    public function index(){
       $engineers = User::whereHas('companies', function($query) {
                                $query->where('companies.user_id', Auth::id());
                            })->select('id', 'name')->get();

        $certificate_types = JobForm::where('parent_id', '!=',  0)->where('active', 1)->orderBy('id', 'ASC')->get();
        return view('app.drafts.index', [
            'title' => 'Record & Drafts - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'Record & Drafts', 'href' => 'javascript:void(0);'],
            ],
            'engineers' => $engineers,
            'certificate_types' => $certificate_types
        ]);
    }

    public function list(Request $request){
        $queryStr = (isset($request->queryStr) && !empty($request->queryStr) ? $request->queryStr : '');

        
        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;
    
        $query = ExistingRecordDraft::with('customer', 'job', 'job.property', 'form', 'user', 'model')->orderByRaw(implode(',', $sorts));
        if (!empty($queryStr)):
            $query->whereHas('customer', function ($q) use ($queryStr) {
                $q->where(function($sq) use($queryStr){
                    $sq->where('full_name', 'LIKE', '%' . $queryStr . '%')->orWhere('address_line_1', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('address_line_2', 'LIKE', '%'.$queryStr.'%')->orWhere('postal_code', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('city', 'LIKE', '%'.$queryStr.'%');
                });
            })->orWhereHas('job.property', function ($q) use ($queryStr) {
                $q->where(function($sq) use($queryStr){
                    $sq->where('occupant_name', 'LIKE', '%' . $queryStr . '%')->orWhere('address_line_1', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('address_line_2', 'LIKE', '%'.$queryStr.'%')->orWhere('postal_code', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('city', 'LIKE', '%'.$queryStr.'%');
                });
            });
        endif;
        $Query = $query->get();

        $html = '';

        if(!empty($Query)):
            foreach($Query as $list):
                $certificate_number = '';
                if(isset($list->model->certificate_number) && !empty($list->model->certificate_number)):
                    $certificate_number = $list->model->certificate_number;
                elseif(isset($list->model->invoice_number) && !empty($list->model->invoice_number)):
                    $certificate_number = $list->model->quote_number;
                elseif(isset($list->model->quote_number) && !empty($list->model->quote_number)):
                    $certificate_number = $list->model->quote_number;
                endif;
                $theModel = $list->model->getMorphClass();
                switch($list->job_form_id){
                    case(4):
                        $url = route('invoice.show', $list->model_id);
                        break;
                    case(3):
                        $url = route('quote.show', $list->model_id);
                        break;
                    case(6):
                        $url = route('new.records.gsr.view', $list->model_id);
                        break;
                    case(7):
                        $url = route('new.records.glsr.view', $list->model_id);
                        break;
                    case(8):
                        $url = route('new.records.gas.warning.notice.show', $list->model_id);
                        break;
                    case(9):
                        $url = route('new.records.gas.service.show', $list->model_id);
                        break;
                    case(10):
                        $url = route('new.records.gas.breakdown.record.show', $list->model_id);
                        break;
                    case(13):
                        $url = route('new.records.gas.bscc.record.show', $list->model_id);
                        break;
                    case(15):
                        $url = route('new.records.gas.power.flush.record.show', $list->model_id);
                        break;
                    case(16):
                        $url = route('new.records.gcdr.show', $list->model_id);
                        break;
                    case(17):
                        $url = route('new.records.guhwcr.show', $list->model_id);
                        break;
                    case(18):
                        $url = route('new.records.gjsr.show', $list->model_id);
                        break;
                    default:
                        $url = '';
                        break;
                }

                $status = $list->model->status ?? '';
                $html .= '<tr data-url="'.$url.'" class="recordRow intro-x box border max-sm:px-3 max-sm:pt-2 max-sm:pb-2 max-sm:mb-[10px] shadow-[5px_3px_5px_#00000005] rounded">';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2 rounded-tl-none sm:rounded-tl rounded-bl-none sm:rounded-bl">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Type</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">'.($list->form->name ?? '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Serial No</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">'.$certificate_number.'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Inspection Name</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">'.($list->job->property->occupant_name ?? ($list->customer->full_name ?? '')).'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start flex-wrap">';
                            $html .= '<label class="sm:hidden mb-1.5 font-medium m-0 flex-zero-full">Inspection Address</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] max-sm:ml-auto flex-zero-full">'.($list->job->property->full_address ?? '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Landlord Name</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">'.($list->customer->full_name ?? '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start flex-wrap">';
                            $html .= '<label class="sm:hidden mb-1.5 font-medium m-0 flex-zero-full">Landlord Address</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] max-sm:ml-auto flex-zero-full">'.($list->customer->full_address ?? '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Assigned To</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">'.(isset($list->model->user->name) ? $list->model->user->name : '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Created At</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal text-xs leading-[1.3] max-sm:ml-auto">'.($list->model->created_at ? $list->model->created_at->format('Y-m-d h:i A') : '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 border-none px-0 sm:px-3 py-3 sm:py-2 rounded-tr-none sm:rounded-tr rounded-br-none sm:rounded-br">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Status</label>';
                            if($status == 'Cancelled'){
                                $html .= '<button class="ml-auto font-medium bg-danger rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Cancelled</button>';
                            }else if($status == 'Approved & Sent'){
                                $html .= '<button class="ml-auto font-medium bg-success rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Approved & Sent</button>';
                            }else if($status == 'Approved'){
                                $html .= '<button class="ml-auto font-medium bg-primary rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Approved</button>';
                            }else{
                                $html .= '<button class="ml-auto font-medium bg-pending rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">'.$status.'</button>';
                            }
                        $html .= '</div>';
                    $html .= '</td>';
                $html .= '</tr>';
            endforeach;
        else:
            $html .= '<tr data-url="" class="intro-x box bg-pending bg-opacity-10 border border-pending border-opacity-5 max-sm:mb-[10px] shadow-[5px_3px_5px_#00000005] rounded">';
                $html .= '<td colspan="9" class="border-b dark:border-darkmode-300 border-none px-3 py-3 rounded">';
                    $html .= '<div class="flex justify-center items-center text-pending">';
                        $html .= 'No matching records found!';
                    $html .= '</div>';
                $html .= '</td>';
            $html .= '</tr>';
        endif;
        return response()->json(['html' => $html], 200); 
    }

    public function listBackup(Request $request){

        $queryStr = (isset($request->queryStr) && !empty($request->queryStr) ? $request->queryStr : '');
        $status = (isset($request->status) && !empty($request->status) ? $request->status : '');
        $engineerId = (isset($request->engineerId) && !empty($request->engineerId) ? $request->engineerId : '');
        $certificateType = (isset($request->certificateType) && !empty($request->certificateType) ? $request->certificateType : '');
        $dateRange = (isset($request->dateRange) && !empty($request->dateRange) ? $request->dateRange : '');

        
        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;
    
        $query = ExistingRecordDraft::with('customer', 'job', 'job.property', 'form', 'user', 'model')->orderByRaw(implode(',', $sorts));
        if (!empty($queryStr)):
            $query->whereHas('customer', function ($q) use ($queryStr) {
                $q->where(function($sq) use($queryStr){
                    $sq->where('full_name', 'LIKE', '%' . $queryStr . '%')->orWhere('address_line_1', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('address_line_2', 'LIKE', '%'.$queryStr.'%')->orWhere('postal_code', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('city', 'LIKE', '%'.$queryStr.'%');
                });
            })->orWhereHas('job.property', function ($q) use ($queryStr) {
                $q->where(function($sq) use($queryStr){
                    $sq->where('occupant_name', 'LIKE', '%' . $queryStr . '%')->orWhere('address_line_1', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('address_line_2', 'LIKE', '%'.$queryStr.'%')->orWhere('postal_code', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('city', 'LIKE', '%'.$queryStr.'%');
                });
            });
        endif;
        if(!empty($certificateType) && $certificateType != 'all'):
            $query->where('job_form_id', $certificateType);
        endif;
        if(!empty($engineerId) && $engineerId != 'all'):
            $query->where('created_by', $engineerId);
        else:
            $query->where('created_by', auth()->user()->id);
        endif;
        if(!empty($status) && $status != 'all'):
            $query->whereHas('model', function($q) use($status){
                $q->where('status', $status);
            });
        endif;

        if(!empty($dateRange) && strpos($dateRange, ' - ') !== false):
            $dates = explode(' - ', $dateRange);
            $query->whereHas('model', function($q) use($dates){
                $q->whereBetween('created_at', [
                    date('Y-m-d 00:00:00', strtotime($dates[0])), 
                    date('Y-m-d 23:59:59', strtotime($dates[1]))
                ]);
            });
        endif;

        $total_rows = $query->count();
        $page = (isset($request->page) && $request->page > 0 ? $request->page : 0);
        $perpage = (isset($request->size) && $request->size == 'true' ? $total_rows : ($request->size > 0 ? $request->size : 10));
        $last_page = $total_rows > 0 ? ceil($total_rows / $perpage) : '';
        
        $limit = $perpage;
        $offset = ($page > 0 ? ($page - 1) * $perpage : 0);

        $Query= $query->skip($offset)
            ->take($limit)
            ->get();

        $data = array();

        if(!empty($Query)):
            $i = 1;
            foreach($Query as $list):
                $certificate_number = '';
                if(isset($list->model->certificate_number) && !empty($list->model->certificate_number)):
                    $certificate_number = $list->model->certificate_number;
                elseif(isset($list->model->invoice_number) && !empty($list->model->invoice_number)):
                    $certificate_number = $list->model->quote_number;
                elseif(isset($list->model->quote_number) && !empty($list->model->quote_number)):
                    $certificate_number = $list->model->quote_number;
                endif;
                $theModel = $list->model->getMorphClass();
                switch($list->job_form_id){
                    case(4):
                        $url = route('invoice.show', $list->model_id);
                        break;
                    case(3):
                        $url = route('quote.show', $list->model_id);
                        break;
                    case(6):
                        $url = route('new.records.gsr.view', $list->model_id);
                        break;
                    case(7):
                        $url = route('new.records.glsr.view', $list->model_id);
                        break;
                    case(8):
                        $url = route('new.records.gas.warning.notice.show', $list->model_id);
                        break;
                    case(9):
                        $url = route('new.records.gas.service.show', $list->model_id);
                        break;
                    case(10):
                        $url = route('new.records.gas.breakdown.record.show', $list->model_id);
                        break;
                    case(13):
                        $url = route('new.records.gas.bscc.record.show', $list->model_id);
                        break;
                    case(15):
                        $url = route('new.records.gas.power.flush.record.show', $list->model_id);
                        break;
                    case(16):
                        $url = route('new.records.gcdr.show', $list->model_id);
                        break;
                    case(17):
                        $url = route('new.records.guhwcr.show', $list->model_id);
                        break;
                    case(18):
                        $url = route('new.records.gjsr.show', $list->model_id);
                        break;
                    default:
                        $url = '';
                        break;
                }
                $data[] = [
                    'id' => $i,
                    'landlord_name' => $list->customer->full_name ?? '',
                    'landlord_address' => $list->customer->full_address ?? '',
                    'inspection_address' => $list->job->property->full_address ?? '',
                    'certificate_type' => $list->form->name ?? '',
                    'certificate_number' => $certificate_number,
                    'created_at' => $list->model->created_at ? $list->model->created_at->format('jS M, Y \<b\r\/> \a\t h:i a') : '',
                    'status' => $list->model->status ?? '',
                    'actions' => $list->id,
                    'url' => $url
                ];
                $i++;
            endforeach;
        endif;
        return response()->json(['last_page' => $last_page, 'data' => $data]); 
    }
}
