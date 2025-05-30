<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\BoilarManualExport;
use App\Http\Controllers\Controller;
use App\Models\BoilerManual;
use Illuminate\Http\Request;

use App\Imports\BoilarManualImport;
use App\Models\FileRecord;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BoilerManualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $boilerManual = BoilerManual::create([
            'boiler_brand_id' => $request->input('boiler_brand_id'),
            'gc_no' => $request->input('gc_no'),
            'url' => $request->input('url'),
            'model' => $request->input('model'),
            'fuel_type' => $request->input('fuel_type'),
            'year_of_manufacture' => $request->input('year_of_manufacture'),
        ]);
        if($boilerManual->id && $request->hasFile('document')):
            $document = $request->file('document');
            $documentName = $boilerManual->id.'_'.$document->getClientOriginalName();
            $path = $document->storeAs('boiler_manual/'.$boilerManual->id, $documentName, 'public');

            BoilerManual::where('id', $boilerManual->id)->update(['document' => $documentName]);
        endif;
        
        return response()->json(['message' => 'Boiler Manual created successfully', 'red' => ''], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BoilerManual $boilerManual)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoilerManual $boilerManual)
    {
        return response()->json($boilerManual);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoilerManual $boilerManual)
    {
        $documentName = $boilerManual->document;
        if($request->hasFile('document')):
            if (!empty($documentName) && Storage::disk('public')->exists('boiler_manual/'.$boilerManual->id.'/'.$documentName)):
                Storage::disk('public')->delete('boiler_manual/'.$boilerManual->id.'/'.$documentName);
            endif;
            $document = $request->file('document');
            $documentName = $boilerManual->id.'_'.$document->getClientOriginalName();
            $path = $document->storeAs('boiler_manual/'.$boilerManual->id, $documentName, 'public');
        endif;

        $boilerManual->boiler_brand_id = $request->input('boiler_brand_id');
        $boilerManual->gc_no = $request->input('gc_no');
        $boilerManual->url = $request->input('url');
        $boilerManual->model = $request->input('model');
        $boilerManual->fuel_type = $request->input('fuel_type');
        $boilerManual->year_of_manufacture = $request->input('year_of_manufacture');
        $boilerManual->document = $documentName;
        $boilerManual->save();

        if($boilerManual->wasChanged()) {
            return response()->json(['message' => 'Boiler Manual updated successfully', 'red' => ''], 200);
        } else {
            return response()->json(['message' => 'Boiler Manual Couldn\'t Updated'], 304);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoilerManual $boilerManual)
    {
        $boilerManual->delete();
        return response()->json(['message' => 'Boiler Manual deleted successfully'], 200);

    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $boilerManual = BoilerManual::withTrashed()->find($id);
        $boilerManual->restore();
        return response()->json(['message' => 'Boiler Manual restored successfully'], 200);
    }


    public function list(Request $request) {

        $queryStr = (isset($request->querystr) && !empty($request->querystr) ? $request->querystr : '');
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = BoilerManual::orderByRaw(implode(',', $sorts));
        $query->where('boiler_brand_id', $request->boiler_brand_id);
        if(!empty($queryStr)):
            $query->where(function($q) use ($queryStr){
                $q->where('name', 'LIKE', '%'.$queryStr.'%');
            });
        endif;
        if($status == 2):
            $query->onlyTrashed();
        endif;

        $total_rows = $query->count();
        $page = (isset($request->page) && $request->page > 0 ? $request->page : 0);
        $perpage = (isset($request->size) && $request->size == 'true' ? $total_rows : ($request->size > 0 ? $request->size : 10));
        $last_page = $total_rows > 0 ? ceil($total_rows / $perpage) : '';
        
        $limit = $perpage;
        $offset = ($page > 0 ? ($page - 1) * $perpage : 0);

        $Query= $query->skip($offset)
               ->take($limit)
               ->get();

        $data = array();

        if(!empty($Query)):
            $i = 1;
            foreach($Query as $list):
                $document_url = '';
                if (!empty($list->document) && Storage::disk('public')->exists('boiler_manual/'.$list->id.'/'.$list->document)):
                    $document_url = Storage::disk('public')->url('boiler_manual/'.$list->id.'/'.$list->document);
                endif;
                $data[] = [
                    'id' => $list->id,
                    'sl' => $i,
                    'gc_no' => $list->gc_no,
                    'url' => $list->url,
                    'model' => $list->model,
                    'fuel_type' => $list->fuel_type,
                    'year_of_manufacture' => $list->year_of_manufacture,
                    'document_url' => $document_url,
                    'created_at' => $list->created_at,
                    'deleted_at' => isset($list->deleted_at) ? $list->deleted_at : NULL,
                ];
                $i++;
            endforeach;
        endif;
        
        return response()->json(['last_page' => $last_page,"current_page"=> $page*1 , 'data' => $data]);
    }

    public function import(Request $request) {
        $boilerBrandId = $request->input('boiler_brand_id');

        $fileRecords = FileRecord::find($request->input('file_id'));

        Excel::import(new BoilarManualImport($boilerBrandId), storage_path('app/public/'.$fileRecords->path));
        $filePath = $fileRecords->path;
        // remove from file record
        if (Storage::disk('public')->exists($filePath)) {

            Storage::disk('public')->delete($filePath);

            $fileRecords->delete();

            return response()->json(['message' => "file cleared successfully with imported data "], 201);
        }

        return response()->json(['message' => "Imported Successfully without clearing garbage data"], 201);
    }

    public function export(Request $request) {
        $boilerBrandId = $request->id;
        $boilerManuals = [];
        $fileName = 'boiler_manuals_'.$boilerBrandId.'.xlsx';
        return Excel::download(new BoilarManualExport($boilerManuals), $fileName);
    }
}
