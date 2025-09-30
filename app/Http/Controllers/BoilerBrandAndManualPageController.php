<?php

namespace App\Http\Controllers;

use App\Models\BoilerBrand;
use App\Models\BoilerNewBrand;
use App\Models\BoilerNewManual;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BoilerBrandAndManualPageController extends Controller
{
    public function index()
    {
        $boilerBrands = BoilerNewBrand::orderBy('name','asc')->get();
        return view('app.boiler-brand-and-manual.index',compact('boilerBrands'));
    }


    public function boilerBrandManualByBoilerBrandId($id)
    {
        $cacheKey = 'boiler_manuals_' . $id;
        $boilerManuals = Cache::remember($cacheKey, 60, function () use ($id) {
            $boilerBrand = BoilerNewBrand::find($id);
            return $boilerBrand->boilerNewManuals;
        });
        return response()->json($boilerManuals);
    }

    public function boilerBrandManualDownload(Request $request){
        $manual = BoilerNewManual::find($request->boiler_new_manual_id);
        //$pdfUrl (isset($manual->pdf_url) && !empty($manual->pdf_url) ? $manual->pdf_url : false);

        if (!empty($manual->document) ):
            //&& Storage::disk('s3')->exists('public/boilermanual/'.$manual->boiler_new_brand_id.'/'.$manual->document)
            $manualUrl = Storage::disk('s3')->temporaryUrl('public/boilermanual/'.$manual->boiler_new_brand_id.'/'.$manual->document, now()->addMinutes(30));
            return response()->json(['url' => $manualUrl], 200);
        else:
            return response()->json(['url' => ''], 200);
        endif;
    }
}
