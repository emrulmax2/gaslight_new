<?php

namespace App\Http\Controllers\SuperAdmin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceCancelReasonRequest;
use App\Models\InvoiceCancelReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceCancelReasonController extends Controller
{
    public function index(){
        return view('app.superadmin.settings.inv-cancel-reason.index',[
            'title' => 'Site Settings - Gas Certificate APP',
            'subtitle' => 'Cancel Reason',
            'breadcrumbs' => [
                ['label' => 'Site Settings', 'href' => route('superadmin.site.setting')],
                ['label' => 'Cancel Reason', 'href' => 'javascript:void(0);'],
            ]
        ]);
    }


     public function list(Request $request) {
        $queryStr = (isset($request->queryStr) && !empty($request->queryStr) ? $request->queryStr : '');
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = InvoiceCancelReason::orderByRaw(implode(',', $sorts));
        if(!empty($queryStr)):
            $query->where(function($q) use ($queryStr){
                $q->where('name', 'LIKE', '%'.$queryStr.'%');
            });
        endif;
        if($status == 2):
            $query->onlyTrashed();
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
                $data[] = [
                    'id' => $list->id,
                    'sl' => $i,
                    'name' => $list->name,
                    'active' => $list->active,
                    'deleted_at' => isset($list->deleted_at) ? $list->deleted_at : NULL,
                ];
                $i++;
            endforeach;
        endif;
        
        return response()->json(['last_page' => $last_page, 'data' => $data , 'current_page' => $page * 1], 200);
    }

    public function store(InvoiceCancelReasonRequest $request)
    {
        $referral_code = InvoiceCancelReason::create([
            'name' => $request->name,
            'created_by' => Auth::guard('superadmin')->user()->id,
            'active' => $request->active ?? 0,
        ]);

        return response()->json(['msg' => 'Payment method successfully added.'], 200);
    }


     public function update(InvoiceCancelReasonRequest $request)
    {
        $referral_code = InvoiceCancelReason::find($request->id);
        $referral_code->update([
            'name' => $request->name,
            'updated_by' => Auth::guard('superadmin')->user()->id,
            'active' => $request->active ?? 0,
        ]);

        return response()->json(['msg' => 'Payment method successfully added.'], 200);
    }

    public function destroy($method_id){
        $package = InvoiceCancelReason::find($method_id)->delete();

        return response()->json(['msg' => 'Payment method successfully deleted.', 'red' => ''], 200);
    }

    public function restore($method_id){
        $package = InvoiceCancelReason::where('id', $method_id)->withTrashed()->restore();

        return response()->json(['msg' => 'Payment method Successfully Restored!', 'red' => ''], 200);
    }
}
