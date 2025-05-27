<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPricingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Number;

class UserSubscriptionController extends Controller
{
    public function paymentHistory(Request $request, $id)
    {

        $user_id = $id;
        $query = UserPricingPackage::with('user', 'package');
        $status = ($request->has('status') && ($request->query('status') != '')) ? $request->query('status') : 1;
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'id';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? $request->query('order') : 'DESC';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';
        $searchableColumns = Schema::getColumnListing((new UserPricingPackage)->getTable());

        if ($user_id > 0):
             $query->where('user_id', $user_id); 
        endif;

        if ($status == 2):
            $query->onlyTrashed();
        else:
            $query->where('active', $status);
        endif;

        if (!empty($searchKey)) {
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'LIKE', '%' . $searchKey . '%');
                }
            });
        }

        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'asc';
        $query->orderBy($sortField, $sortOrder);
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);
        
        $pricing_packages = $query->paginate($limit, ['*'], 'page', $page);

       return response()->json([
            'data' => $pricing_packages->items(),
            'meta' => [
                'total' => $pricing_packages->total(),
                'per_page' => $pricing_packages->perPage(),
                'current_page' => $pricing_packages->currentPage(),
                'last_page' => $pricing_packages->lastPage(),
                'from' => $pricing_packages->firstItem(),
                'to' => $pricing_packages->lastItem(),
            ]
        ]);
    }


    public function userPlanDetails(Request $request, $id){
        $user = User::with('userpackage')->where('id', $id)->firstOrFail();
        
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
            ],
        ]);
    }
}
