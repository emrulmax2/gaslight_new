<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BoilerNewBrand;
use Illuminate\Support\Facades\Cache;

class BoilerBrandAndManualPageController extends Controller
{
    public function index()
    {

        $boilerBrands = BoilerNewBrand::orderBy('name', 'asc')->get();

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


    public function brand_manual($id)
    {

        $boilerBrands = BoilerNewBrand::with('boilerNewManuals')->find($id);
        $boilerManuals = (isset($boilerBrands->boilerNewManuals) ? $boilerBrands->boilerNewManuals : FALSE);

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
}
