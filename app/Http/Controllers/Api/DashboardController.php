<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Fakers\Countries;
use App\Models\Customer;
use App\Models\CustomerJob;

class DashboardController extends Controller
{
    public function index(Request $request) 
    {
        $user = $request->user();

        if($user->role != 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if the user is authenticated
        // Ensure the request is authenticated using the Bearer Token
        $theUser = $request->user(); // This retrieves the authenticated user via the token

        // Generate a unique cache key for the user
        $cacheKey = 'dashboard_data_user_' . $theUser->id;

        // Check if the data is already cached
        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($theUser) {
            return [
                'user' => $theUser,
                'countries' => Countries::fakeCountries(),
                'first_login' => $theUser->first_login,
                'recent_jobs' => CustomerJob::with('customer', 'property')
                    ->where('created_by', $theUser->id)
                    ->orderBy('id', 'DESC')
                    ->take(5)
                    ->get(),
                'user_jobs_count' => CustomerJob::where('created_by', $theUser->id)->count(),
                'user_customers_count' => Customer::where('created_by', $theUser->id)->count(),
            ];
        });

        // Return the data as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Dashboard data retrieved successfully.',
            'data' => $data,
        ], 200);
    }
}
