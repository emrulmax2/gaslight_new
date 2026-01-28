<?php

namespace App\Http\Controllers\Drafts;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobForm;
use App\Models\Record;
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
    
        $query = Record::with(['customer', 'customer.address', 'customer.contact', 'job', 'job.property', 'form', 'user', 'user.company', 'property', 'occupant'])
                 ->orderByRaw(implode(',', $sorts));
        if (!empty($queryStr)):
            $query->whereHas('customer', function ($q) use ($queryStr) {
                $q->where('full_name', 'LIKE', '%' . $queryStr . '%');
            })->orWhereHas('job.property', function ($q) use ($queryStr) {
                $q->where(function($sq) use($queryStr){
                    $sq->orWhere('address_line_1', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('address_line_2', 'LIKE', '%'.$queryStr.'%')->orWhere('postal_code', 'LIKE', '%'.$queryStr.'%')
                    ->orWhere('city', 'LIKE', '%'.$queryStr.'%');
                });
            })->orWhereHas('occupant', function($q) use ($queryStr){
                $q->where('occupant_name', 'LIKE', '%' . $queryStr . '%');
            });
        endif;
        $query->where('created_by', $request->user()->id);
        $Query = $query->get();

        $html = '';

        if(!empty($Query)):
            foreach($Query as $list):
                $url = route('records.show', $list->id);

                $html .= '<tr data-url="'.$url.'" class="recordRow cursor-pointer intro-x box border px-3 sm:px-0 max-sm:pt-2 max-sm:pb-2 max-sm:mb-[10px] shadow-[5px_3px_5px_#00000005] rounded">';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2 rounded-tl-none sm:rounded-tl rounded-bl-none sm:rounded-bl">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Type</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">'.($list->form->name ?? '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Serial No</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto">'.$list->certificate_number.'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Inspection Name</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto capitalize">'.($list->job->property->occupant_name ?? ($list->customer->full_name ?? '')).'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start flex-wrap">';
                            $html .= '<label class="sm:hidden mb-1.5 font-medium m-0 flex-zero-full">Inspection Address</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] max-sm:ml-auto flex-zero-full">'.($list->job->property->full_address ?? '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start sm:block">';
                            $html .= '<label class="sm:hidden font-medium m-0">Landlord Name</label>';
                            $html .= '<div>';
                                $html .= '<div class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto capitalize">'.($list->customer->full_name ?? '').'</div>';
                                $html .= '<div class="text-slate-500 whitespace-normal text-xs leading-[1.3]  max-sm:ml-auto">'.($list->customer->full_address ?? '').'</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</td>';
                    // $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                    //     $html .= '<div class="flex items-start flex-wrap">';
                    //         $html .= '<label class="sm:hidden mb-1.5 font-medium m-0 flex-zero-full">Billing Address</label>';
                    //         $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] max-sm:ml-auto flex-zero-full">'.($list->billing->full_address ?? '').'</span>';
                    //     $html .= '</div>';
                    // $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Assigned To</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal sm:text-xs leading-[1.3] sm:font-medium max-sm:ml-auto capitalize">'.(isset($list->user->name) ? $list->user->name : '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 max-sm:border-b max-sm:border-solid border-none px-0 sm:px-3 py-3 sm:py-2">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Created At</label>';
                            $html .= '<span class="text-slate-500 whitespace-normal text-xs leading-[1.3] max-sm:ml-auto">'.($list->created_at ? $list->created_at->format('Y-m-d h:i A') : '').'</span>';
                        $html .= '</div>';
                    $html .= '</td>';
                    $html .= '<td class="border-b dark:border-darkmode-300 border-none px-0 sm:px-3 py-3 sm:py-2 rounded-tr-none sm:rounded-tr rounded-br-none sm:rounded-br">';
                        $html .= '<div class="flex items-start">';
                            $html .= '<label class="sm:hidden font-medium m-0">Status</label>';
                            if($list->status == 'Cancelled'){
                                $html .= '<button class="ml-auto font-medium bg-danger rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Cancelled</button>';
                            }else if($list->status == 'Email Sent'){
                                $html .= '<button class="ml-auto font-medium bg-success rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Email Sent</button>';
                            }else if($list->status == 'Approved'){
                                $html .= '<button class="ml-auto font-medium bg-primary rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Approved</button>';
                            }else{
                                $html .= '<button class="ml-auto font-medium bg-pending rounded-[2px] text-white text-[10px] leading-none uppercase px-2 py-1">Draft</button>';
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
}
