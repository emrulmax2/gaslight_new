<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BoilerBrand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BoilerBrandAndManualPageController extends Controller
{
    public function index()
    {
        // Define a cache key for boiler brands
        $cacheKey = 'boiler_brands';

        // Use Cache::remember to store/retrieve the data
        $boilerBrands = Cache::remember($cacheKey, 60, function () {
            return BoilerBrand::orderBy('name', 'asc')->get();
        });

        // Check if the collection is empty
        if ($boilerBrands->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No data available',
            ], 404);
        }

        // Return the data if not empty
        return response()->json([
            'success' => true,
            'data' => $boilerBrands,
        ], 200);
    }


    public function boilerBrandManualByBoilerBrandId($id)
    {

        // Define a cache key for boiler manuals by boiler brand ID
        $cacheKey = 'boiler_manuals_' . $id;

        // Use Cache::remember to store/retrieve the data
        $boilerManuals = Cache::remember($cacheKey, 60, function () use ($id) {
            $boilerBrand = BoilerBrand::find($id);

            // Return null if the boiler brand does not exist
            if (!$boilerBrand) {
                return null;
            }

            return $boilerBrand->boilerManuals;
        });

        // Check if the data is empty or the boiler brand does not exist
        if (!$boilerManuals || $boilerManuals->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No data available for the specified boiler brand',
            ], 404);
        }

        // Return the data if not empty
        return response()->json([
            'success' => true,
            'data' => $boilerManuals,
        ], 200);
    }
}
