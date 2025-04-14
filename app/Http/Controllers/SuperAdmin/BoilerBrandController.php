<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBoilerBrandRequest;
use App\Models\BoilerBrand;
use Illuminate\Http\Request;


class BoilerBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('app.superadmin.boiler_brands.index',[
            'boilerBrands' => BoilerBrand::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoilerBrandRequest $request)
    {

        $boilerBrand = BoilerBrand::create([
            'name' => $request->input('name')
        ]);
        
        return response()->json(['message' => 'Staff created successfully', 'red' => ''], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BoilerBrand $boilerBrand)
    {
        return view('app.superadmin.boiler_brands.show',[
            'boilerBrand' => $boilerBrand,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoilerBrand $boilerBrand)
    {
        return response()->json($boilerBrand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoilerBrand $boilerBrand)
    {

        $boilerBrand->name = $request->input('name');
        $boilerBrand->save();

        if($boilerBrand->wasChanged()) {
            return response()->json(['message' => 'Boiler Brand updated successfully'], 204);
        } else {
            return response()->json(['message' => 'Boiler Brand Couldn\'t Updated'], 304);
        }
      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoilerBrand $boilerBrand)
    {
        $boilerBrand->delete();
        return response()->json(['message' => 'Boiler Brand deleted successfully'], 200);

    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $boilerBrand = BoilerBrand::withTrashed()->find($id);
        $boilerBrand->restore();
        return response()->json(['message' => 'Boiler Brand restored successfully'], 200);  
    }


    public function list(Request $request) {

        $queryStr = (isset($request->queryStr) && !empty($request->queryStr) ? $request->queryStr : '');
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = BoilerBrand::orderByRaw(implode(',', $sorts));
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
                    'deleted_at' => isset($list->deleted_at) ? $list->deleted_at : NULL,
                ];
                $i++;
            endforeach;
        endif;
        
        return response()->json(['last_page' => $last_page, 'data' => $data , 'current_page' => $page * 1], 200);
    }

}
