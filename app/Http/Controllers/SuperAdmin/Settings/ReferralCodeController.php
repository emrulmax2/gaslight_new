<?php

namespace App\Http\Controllers\SuperAdmin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReferralCodeStoreRequest;
use App\Http\Requests\ReferralCodeUpdateRequest;
use App\Models\UserReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralCodeController extends Controller
{
    public function index(){
        return view('app.superadmin.settings.referral-code.index',[
            'title' => 'Site Settings - Gas Certificate APP',
            'subtitle' => 'Referral Code',
            'breadcrumbs' => [
                ['label' => 'Site Settings', 'href' => route('superadmin.site.setting')],
                ['label' => 'Referral Code', 'href' => 'javascript:void(0);'],
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

        $query = UserReferralCode::where('is_global', 1)->orderByRaw(implode(',', $sorts));
        if(!empty($queryStr)):
            $query->where(function($q) use ($queryStr){
                $q->where('code', 'LIKE', '%'.$queryStr.'%');
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
                    'code' => $list->code,
                    'no_of_days' => $list->no_of_days,
                    'expiry_date' => isset($list->expiry_date) ? date('Y-m-d', strtotime($list->expiry_date)) : 'Unlimited',
                    'max_no_of_use' => isset($list->max_no_of_use) ? $list->max_no_of_use : 'Unlimited',
                    'active' => $list->active,
                    // 'created_by'=> (isset($list->user->name) ? $list->user->name : 'Unknown'),
                    // 'created_at'=> (isset($list->created_at) && !empty($list->created_at) ? date('jS F, Y', strtotime($list->created_at)) : ''),
                    'deleted_at' => isset($list->deleted_at) ? $list->deleted_at : NULL,
                ];
                $i++;
            endforeach;
        endif;
        
        return response()->json(['last_page' => $last_page, 'data' => $data , 'current_page' => $page * 1], 200);
    }

    public function store(ReferralCodeStoreRequest $request)
    {
        $referral_code = UserReferralCode::create([
            'code' => $request->code,
            'user_id' => 1,
            'is_global' => 1,
            'no_of_days' => $request->no_of_days,
            'max_no_of_use' => $request->max_no_of_use,
            'expiry_date' => $request->expiry_date,
            'created_by' => Auth::guard('superadmin')->user()->id,
            'active' => $request->active ?? 0,
        ]);

        return response()->json(['msg' => 'Referral code successfully added.'], 200);
    }


     public function update(ReferralCodeUpdateRequest $request)
    {
        $referral_code = UserReferralCode::find($request->id);
        $referral_code->update([
            'user_id' => 1,
            'is_global' => 1,
            'no_of_days' => $request->no_of_days,
            'max_no_of_use' => $request->max_no_of_use,
            'expiry_date' => $request->expiry_date,
            'updated_by' => Auth::guard('superadmin')->user()->id,
            'active' => $request->active ?? 0,
        ]);

        return response()->json(['msg' => 'Referral code successfully added.'], 200);
    }

    public function destroy($ref_id){
        $package = UserReferralCode::find($ref_id)->delete();

        return response()->json(['msg' => 'Referral code successfully deleted.', 'red' => ''], 200);
    }

    public function restore($ref_id){
        $package = UserReferralCode::where('id', $ref_id)->withTrashed()->restore();

        return response()->json(['msg' => 'Referral code Successfully Restored!', 'red' => ''], 200);
    }

}
