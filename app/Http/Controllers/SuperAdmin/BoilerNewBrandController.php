<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\BoilerNewManualExport;
use App\Http\Controllers\Controller;
use App\Models\BoilerNewBrand;
use App\Models\BoilerNewManual;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BoilerNewBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('app.superadmin.boiler_new_brands.index',[
            'boilerBrands' => BoilerNewBrand::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    public function store(Request $request)
    {
        set_time_limit(0);

        $request->validate([
            'name' => 'required|string|max:255',
            'document' => 'required|file'
        ]);

        $boilerBrand = BoilerNewBrand::create([
            'name' => $request->input('name')
        ]);

        $csvPath = $request->file('document')->getRealPath();
        $results = [];
        $insertCount = 0;

        if (($handle = fopen($csvPath, "r")) !== false) {
            $row = 0;

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $row++;
                if ($row == 1) continue;

                $model = $data[0] ?? null;
                $gcNo  = $data[1] ?? null;
                $pdfUrl = $data[2] ?? null;

                if (empty($pdfUrl)) {
                    $results[] = "Skipped row {$row}: Invalid GC No or PDF URL";
                    continue;
                }

                $fileName = basename(parse_url($pdfUrl, PHP_URL_PATH));
                try {
                    $fileContents = file_get_contents($pdfUrl);

                    if ($fileContents === false) {
                        $results[] = "Skipped row {$row}: Invalid Pdf URL.";
                        continue;
                    }

                    Storage::disk('public')->put('boiler-new-brand/'.$boilerBrand->id.'/'.$fileName, $fileContents);
                    BoilerNewManual::create([
                        'boiler_new_brand_id' => $boilerBrand->id,
                        'gc_no' => $gcNo,
                        'url' => $pdfUrl,
                        'model' => $model,
                        'document' => $fileName
                    ]);
                    $results[] = "Downloaded: $fileName";
                    $insertCount += 1;
                } catch (Exception $e) {
                    $results[] = "Skipped row {$row}: Invalid Pdf URL.";
                    continue;
                }
            }

            fclose($handle);
        } else {
            return response()->json([
                'message' => 'Failed to read CSV file.',
                'results' => $results
            ], 422);
        }

        if ($insertCount === 0) {
            BoilerNewBrand::where('id', $boilerBrand->id)->forceDelete();
            return response()->json([
                'message' => 'No valid manuals found. Brand not created.',
                'results' => $results
            ], 422);
        }
        
        return response()->json([
            'message' => 'Brand and manuals created successfully',
            'results' => $results
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return view('app.superadmin.boiler_new_brands.show',[
            'boilerBrand' => BoilerNewBrand::find($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoilerNewBrand $boilerBrand)
    {
        return response()->json($boilerBrand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoilerNewBrand $boilerBrand)
    {

        $boilerBrand->name = $request->input('name');
        $boilerBrand->save();

        if($boilerBrand->wasChanged()) {
            return response()->json(['message' => 'Boiler Brand updated successfully'], 204);
        } else {
            return response()->json(['message' => 'Boiler Brand Couldn\'t Updated'], 304);
        }
      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoilerNewBrand $boilerBrand)
    {
        $boilerBrand->delete();
        return response()->json(['message' => 'Boiler Brand deleted successfully'], 200);

    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $boilerBrand = BoilerNewBrand::withTrashed()->find($id);
        $boilerBrand->restore();
        return response()->json(['message' => 'Boiler Brand restored successfully'], 200);  
    }


    public function list(Request $request) {

        $queryStr = (isset($request->queryStr) && !empty($request->queryStr) ? $request->queryStr : '');
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = BoilerNewBrand::orderByRaw(implode(',', $sorts));
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
                $data[] = [
                    'id' => $list->id,
                    'sl' => $i,
                    'name' => $list->name,
                    'manuals' => (isset($list->boilerNewManuals) && $list->boilerNewManuals->count() > 0 ? $list->boilerNewManuals->count() : ''),
                    'deleted_at' => isset($list->deleted_at) ? $list->deleted_at : NULL,
                ];
                $i++;
            endforeach;
        endif;
        
        return response()->json(['last_page' => $last_page, 'data' => $data , 'current_page' => $page * 1], 200);
    }

    public function downloadSample(Request $request){
        $fileName = 'boiler_new_manuals_sample.csv';
        return Excel::download(new BoilerNewManualExport(), $fileName);
    }


}
