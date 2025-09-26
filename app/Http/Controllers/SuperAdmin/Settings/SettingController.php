<?php

namespace App\Http\Controllers\SuperAdmin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app.superadmin.settings.index', [
            'title' => 'Site Settings - Gas Certificate APP',
            'subtitle' => 'Site Settings',
            'breadcrumbs' => [
                ['label' => 'Site Settings', 'href' => 'javascript:void(0);']
            ],
            'opt' => Option::where('category', 'SITE_SETTINGS')->pluck('value', 'name')->toArray()
        ]);
    }

    public function update(Request $request){
        $category = $request->category;
        $allFields = $request->except(['file', 'site_logo', 'site_favicon', 'category']);

        
        if(isset($request->site_logo)):
            $siteLogoRow = Option::where('category', $category)->where('name', 'site_logo')->first();
            $site_logo_name = (isset($siteLogoRow->value) && !empty($siteLogoRow->value) ? $siteLogoRow->value : '');
            if($request->hasFile('site_logo')):
                if(isset($siteLogoRow->value) && !empty($siteLogoRow->value)):
                    if(Storage::disk('public')->exists($siteLogoRow->value)):
                        Storage::disk('public')->delete($siteLogoRow->value);
                    endif;
                endif;

                $site_logo = $request->file('site_logo');
                $imageName = 'company_logo.' . $site_logo->getClientOriginalExtension();
                $path = $site_logo->storeAs('/', $imageName, 'public');

                $site_logo_name = $imageName;
            endif;
            $allFields['site_logo'] = $site_logo_name;
            Cache::forever('site_logo', $site_logo_name);
        endif;

        if(isset($request->site_favicon)):
            $siteFaviconRow = Option::where('category', $category)->where('name', 'site_favicon')->first();
            $site_favicon_name = (isset($siteFaviconRow->value) && !empty($siteFaviconRow->value) ? $siteFaviconRow->value : '');
            if($request->hasFile('site_favicon')):
                if(isset($siteFaviconRow->value) && !empty($siteFaviconRow->value)):
                    if(Storage::disk('public')->exists($siteFaviconRow->value)):
                        Storage::disk('public')->delete($siteFaviconRow->value);
                    endif;
                endif;

                $site_favicon = $request->file('site_favicon');
                $imageName = 'company_favicon.' . $site_favicon->getClientOriginalExtension();
                $path = $site_favicon->storeAs('/', $imageName, 'public');

                $site_favicon_name = $imageName;
            endif;
            $allFields['site_favicon'] = $site_favicon_name;
            Cache::forever('site_favicon', $site_favicon_name);
        endif;

        foreach($allFields as $name => $value):
            $row = Option::updateOrCreate([ 'category' => $category, 'name' => $name ], [
                'category' => $category,
                'name' => $name,
                'value' => $value
            ]);
        endforeach;

        return response()->json(['msg' => 'Option value successfully updated', 'red' => route('superadmin.site.setting')], 200);
    }

    public function apiSettings(){
        return view('app.superadmin.settings.api', [
            'title' => 'Site Settings - Gas Certificate APP',
            'subtitle' => 'Site Settings',
            'breadcrumbs' => [
                ['label' => 'Site Settings', 'href' => route('superadmin.site.setting')],
                ['label' => 'API', 'href' => 'javascript:void(0);'],
            ],
            'opt' => Option::where('category', 'SITE_API')->pluck('value', 'name')->toArray()
        ]);
    }

    public function updateApi(Request $request){
        $category = $request->category;
        $allFields = $request->except(['category']);

        foreach($allFields as $name => $value):
            $row = Option::updateOrCreate([ 'category' => $category, 'name' => $name ], [
                'category' => $category,
                'name' => $name,
                'value' => $value
            ]);
        endforeach;

        return response()->json(['msg' => 'Option value successfully updated', 'red' => route('superadmin.site.setting.api')], 200);
    }
}
