<?php

namespace App\Http\Controllers\SuperAdmin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobCancellReasonRequest;
use App\Models\CancelReason;
use Illuminate\Http\Request;

class JobCancelReasonController extends Controller
{
    public function index(){
        return view('app.superadmin.settings.cancel-reason.index', [  
            'title' => 'Site Settings - Gas Certificate APP',
            'subtitle' => 'User Settings',
            'breadcrumbs' => [
                ['label' => 'Site Settings', 'href' => route('superadmin.site.setting')],
                ['label' => 'Job cancel reason', 'href' => 'javascript:void(0);'],
            ]
        ]);
    }

    public function list(Request $request) {
        $queryStr = (isset($request->queryStr) && !empty($request->queryStr) ? $request->queryStr : '');
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'ASC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = CancelReason::orderByRaw(implode(',', $sorts));
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

    public function store(JobCancellReasonRequest $request){
        $package = CancelReason::create([
            'name' => $request->name,
            'active' => $request->active ?? 0,
            'created_by' => 1,
        ]);

        if($package->id){
            return response()->json(['msg' => 'Job cancel reason successfull added.', 'red' => ''], 200);
        }else{
            return response()->json(['msg' => 'Something went wrong. Please try again later.', 'red' => ''], 304);
        }
    }

    public function update(JobCancellReasonRequest $request){
        $id = $request->id;
        $package = CancelReason::where('id', $id)->update([
            'name' => $request->name,
            'active' => $request->active ?? 0,
            'updated_by' => 1,
        ]);

        return response()->json(['msg' => 'Job cancel reason successfull updated.', 'red' => ''], 200);
    }

    public function destroy($pack_id){
        $package = CancelReason::find($pack_id)->delete();

        return response()->json(['msg' => 'Job cancel reason successfully deleted.', 'red' => ''], 200);
    }

    public function restore($pack_id){
        $package = CancelReason::where('id', $pack_id)->withTrashed()->restore();

        return response()->json(['msg' => 'Job cancel reason Successfully Restored!', 'red' => ''], 200);
    }
}
