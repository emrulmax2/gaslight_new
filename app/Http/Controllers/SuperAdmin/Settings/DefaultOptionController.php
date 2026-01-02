<?php

namespace App\Http\Controllers\SuperAdmin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;

class DefaultOptionController extends Controller
{
    public function index(){
        return view('app.superadmin.settings.default-options.index', [
            'title' => 'Site Settings - Gas Certificate APP',
            'subtitle' => 'Default Options',
            'breadcrumbs' => [
                ['label' => 'Site Settings', 'href' => route('superadmin.site.setting')],
                ['label' => 'Default Options', 'href' => 'javascript:void(0);'],
            ],
            'opt' => Option::where('category', 'DEFAULT_OPTIONS')->pluck('value', 'name')->toArray()
        ]);
    }

    public function update(Request $request){
        $category = $request->category;
        $allFields = $request->except(['category']);

        foreach($allFields as $name => $value):
            $row = Option::updateOrCreate([ 'category' => $category, 'name' => $name ], [
                'category' => $category,
                'name' => $name,
                'value' => $value
            ]);
        endforeach;

        return response()->json(['msg' => 'Option value successfully updated', 'red' => route('superadmin.site.setting.default.opt')], 200);
    }
}
