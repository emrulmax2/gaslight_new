<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BoilerNewBrand;
use App\Models\BoilerNewManual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BoilerBrandAndManualPageController extends Controller
{
    public function index(Request $request)
    {

        $queryStr = (isset($request->search) && !empty($request->search) ? $request->search : null);
        $orderBy = (isset($request->orderBy) && !empty($request->orderBy) ? $request->orderBy : 'name');
        $order = (isset($request->orderBy) && !empty($request->order) ? $request->order : 'asc');
        $query = BoilerNewBrand::orderBy($orderBy, $order);
        if(!empty($queryStr)):
            $query->where('name', 'LIKE', '%'.$queryStr.'%');
        endif;
        $boilerBrands = $query->get();

        if ($boilerBrands->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No data available',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $boilerBrands,
        ], 200);
    }


    public function brand_manual($id, Request $request)
    {


        $queryStr = (isset($request->search) && !empty($request->search) ? $request->search : null);
        $orderBy = (isset($request->orderBy) && !empty($request->orderBy) ? $request->orderBy : 'model');
        $order = (isset($request->orderBy) && !empty($request->order) ? $request->order : 'asc');

        $query = BoilerNewManual::where('boiler_new_brand_id', $id)->orderBy($orderBy, $order);
        if(!empty($queryStr)):
            $query->where(function($q) use($queryStr){
                $q->where('gc_no','LIKE', '%'.$queryStr.'%')->orWhere('model','LIKE', '%'.$queryStr.'%');
            });
        endif;
        $boilerManuals = $query->get();

        if (!$boilerManuals || $boilerManuals->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No data available for the specified boiler brand',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $boilerManuals,
        ], 200);
    }

    public function boilerBrandManualDownload($manual_id){
        $manual = BoilerNewManual::find($manual_id);

        if (!empty($manual->document) && Storage::disk('s3')->exists('public/boilermanual/'.$manual->boiler_new_brand_id.'/'.$manual->document)):
            $manualUrl = Storage::disk('s3')->temporaryUrl('public/boilermanual/'.$manual->boiler_new_brand_id.'/'.$manual->document, now()->addMinutes(30));
            return response()->json([
                'success' => true,
                'data' => $manualUrl,
            ], 200);
        else:
            return response()->json([
                'success' => false,
                'message' => 'File not exist.',
            ], 404);
        endif;
    }
}
