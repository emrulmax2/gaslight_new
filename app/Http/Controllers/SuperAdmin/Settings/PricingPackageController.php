<?php

namespace App\Http\Controllers\Superadmin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\PricingPackStoreRequest;
use App\Http\Requests\PricingPackUpdateRequest;
use App\Models\PricingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Number;

class PricingPackageController extends Controller
{
    public function index(){
        return view('app.superadmin.settings.package.index', [
            'title' => 'Site Settings - Gas Certificate APP',
            'subtitle' => 'User Settings',
            'breadcrumbs' => [
                ['label' => 'Site Settings', 'href' => route('superadmin.site.setting')],
                ['label' => 'Pricing Package', 'href' => 'javascript:void(0);'],
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

        $query = PricingPackage::orderByRaw(implode(',', $sorts));
        if(!empty($queryStr)):
            $query->where(function($q) use ($queryStr){
                $q->where('title', 'LIKE', '%'.$queryStr.'%')->orWhere('subtitle', 'LIKE', '%'.$queryStr.'%')->orWhere('description', 'LIKE', '%'.$queryStr.'%');
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
                    'title' => $list->title,
                    'subtitle' => $list->subtitle,
                    'stripe_plan' => $list->stripe_plan,
                    'description' => $list->description,
                    'period' => $list->period,
                    'price' => $list->price,
                    'order' => $list->order,
                    'active' => $list->active,
                    'price_html' => $list->price > 0 ? Number::currency($list->price, 'GBP') : '',
                    'deleted_at' => isset($list->deleted_at) ? $list->deleted_at : NULL,
                ];
                $i++;
            endforeach;
        endif;
        
        return response()->json(['last_page' => $last_page, 'data' => $data , 'current_page' => $page * 1], 200);
    }

    public function store(PricingPackStoreRequest $request){
        $package = PricingPackage::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description ?? null,
            'period' => $request->period,
            'stripe_plan' => $request->stripe_plan ?? null,
            'price' => $request->price ?? null,
            'order' => PricingPackage::max('order') + 1,
            'active' => $request->active ?? 0,
        ]);

        if($package->id){
            return response()->json(['msg' => 'Pricing package successfull added.', 'red' => ''], 200);
        }else{
            return response()->json(['msg' => 'Something went wrong. Please try again later.', 'red' => ''], 304);
        }
    }

    public function update(PricingPackUpdateRequest $request){
        $id = $request->id;
        $package = PricingPackage::where('id', $id)->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description ?? null,
            'period' => $request->period,
            'stripe_plan' => $request->stripe_plan ?? null,
            'price' => $request->price ?? null,
            'order' => $request->order,
            'active' => $request->active ?? 0,
        ]);

        return response()->json(['msg' => 'Pricing package successfull updated.', 'red' => ''], 200);
    }

    public function destroy($pack_id){
        $package = PricingPackage::find($pack_id)->delete();

        return response()->json(['msg' => 'Pricing package successfully deleted.', 'red' => ''], 200);
    }

    public function restore($pack_id){
        $package = PricingPackage::where('id', $pack_id)->withTrashed()->restore();

        return response()->json(['msg' => 'Pricing package Successfully Restored!', 'red' => ''], 200);
    }
}
