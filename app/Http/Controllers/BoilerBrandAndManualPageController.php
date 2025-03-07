<?php

namespace App\Http\Controllers;

use App\Models\BoilerBrand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BoilerBrandAndManualPageController extends Controller
{
    public function index()
    {
        $boilerBrands = BoilerBrand::orderBy('name','asc')->get();
        return view('app.boiler-brand-and-manual.index',compact('boilerBrands'));
    }


    public function boilerBrandManualByBoilerBrandId($id)
    {
        $cacheKey = 'boiler_manuals_' . $id;
        $boilerManuals = Cache::remember($cacheKey, 60, function () use ($id) {
            $boilerBrand = BoilerBrand::find($id);
            return $boilerBrand->boilerManuals;
        });
        return response()->json($boilerManuals);
    }
}
