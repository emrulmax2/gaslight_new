<?php

namespace App\Http\Controllers\Api\Pricing;

use App\Http\Controllers\Controller;
use App\Models\PricingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PricingPackageController extends Controller
{
    public function list(Request $request){
        $query = PricingPackage::query();

        $status = ($request->has('status') && $request->query('status') != '') ? $request->query('status') : 1;
        $sortField = ($request->has('sort') && !empty($request->query('sort'))) ? $request->query('sort') : 'title';
        $sortOrder = ($request->has('order') && !empty($request->query('order'))) ? $request->query('order') : 'asc';
        $searchKey = ($request->has('search') && !empty($request->query('search'))) ? $request->query('search') : '';

        $searchableColumns = Schema::getColumnListing((new PricingPackage())->getTable());

        if ($status == 2):
            $query->onlyTrashed();
        else:
            $query->where('active', $status);
        endif;
        
        if (!empty($searchKey)):
            $query->where(function($q) use ($searchableColumns, $searchKey) {
                foreach ($searchableColumns as $field) {
                    $q->orWhere($field, 'like', '%' . $searchKey . '%');
                }
            });
        endif;
        
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'asc';
        $query->orderBy($sortField, $sortOrder);
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);
        
        $titles = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $titles->items(),
            'meta' => [
                'total' => $titles->total(),
                'per_page' => $titles->perPage(),
                'current_page' => $titles->currentPage(),
                'last_page' => $titles->lastPage(),
                'from' => $titles->firstItem(),
                'to' => $titles->lastItem(),
            ]
        ]);
    }
}
