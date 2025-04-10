<?php

namespace App\Http\Controllers\Drafts;

use App\Http\Controllers\Controller;
use App\Models\Company;
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
        $status = (isset($request->status) && !empty($request->status) ? $request->status : '');
        $engineerId = (isset($request->engineerId) && !empty($request->engineerId) ? $request->engineerId : '');
        $certificateType = (isset($request->certificateType) && !empty($request->certificateType) ? $request->certificateType : '');
        $dateRange = (isset($request->dateRange) && !empty($request->dateRange) ? $request->dateRange : '');

        
        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;


        $model = null;
        $withRelations = ['customer', 'job.property'];
        
        if ($certificateType) {
            switch ($certificateType) {
                case '3':
                    $model = new Quote();
                    break;
                case '4':
                    $model = new Invoice();
                    break;
                case '6':
                    $model = new GasSafetyRecord();
                    break;
                case '8':
                    $model = new GasWarningNotice();
                    break;
                case '9':
                    $model = new GasServiceRecord();
                    break;
                case '10':
                    $model = new GasBreakdownRecord();
                    break;
                case '13':
                    $model = new GasBoilerSystemCommissioningChecklist();
                    break;
                case '15':
                    $model = new GasPowerFlushRecord();
                    break;
                case '17':
                    $model = new GasUnventedHotWaterCylinderRecord();
                    break;
                case '16':
                    $model = new GasCommissionDecommissionRecord();
                    break;
                default:
                    $model = new Quote();
                    break;
            }
        } else {
            $model = new Quote();
        }
    
        $query = $model->with($withRelations)
            ->orderByRaw(implode(',', $sorts));


        if (!empty($queryStr)):
            $query->whereHas('customer', function ($q) use ($queryStr) {
                $q->where('full_name', 'LIKE', '%' . $queryStr . '%');
            });
        endif;

        
        if(!empty($engineerId) && $engineerId != 'all'):
            $query->where('created_by', $engineerId);
        endif;
            
        if(!empty($status) && $status != 'all'):
            $query->where('status', $status);
        endif;

        if(!empty($dateRange)):
            $dates = explode(' - ', $dateRange);
            $query->whereBetween('created_at', [
                date('Y-m-d', strtotime($dates[0])), 
                date('Y-m-d', strtotime($dates[1]))
            ]);
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
                $certType = JobForm::where('id', $certificateType)->first();
                    $data[] = [
                        'id' => $i,
                        'landlord_name' => $list->customer->full_name ?? '',
                        'landlord_address' => $list->customer->full_address ?? '',
                        'inspection_address' => $list->job->property->full_address ?? '',
                        'certificate_type' => $certType->name ?? '',
                        'assign_to' => Auth::user()->name ?? '',
                        'created_at' => $list->created_at ? $list->created_at->format('jS M, Y \<b\r\/> \a\t h:i a') : '',
                        'status' => $list->status ?? '',
                        'actions' => $list->id
                    ];
                    $i++;
            endforeach;
        endif;
        return response()->json(['last_page' => $last_page, 'data' => $data]); 
    }
}
