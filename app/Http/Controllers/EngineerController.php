<?php

namespace App\Http\Controllers;

use App\Models\Engineer;
use App\Http\Requests\StoreEngineerRequest;
use App\Http\Requests\UpdateEngineerRequest;
use App\Models\User;
use Creagia\LaravelSignPad\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Random\Engine;

class EngineerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('app.engineers.index', [
            'engineers' => Engineer::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.engineers.create');
    }


    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:engineers',
            'password' => 'required|string|min:8',
            'sign' => 'required',
        ]);

        $user = Engineer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')), 
        ]);

        $user->users()->attach($user_id);


        $signatureData = str_replace('data:image/png;base64,', '', $validatedData['sign']);
        $signatureData = base64_decode($signatureData);
        $imageName = 'signatures/' . Str::uuid() . '.png';
        Storage::disk('public')->put($imageName, $signatureData);
        $signature = new Signature();
        $signature->model_type = Engineer::class;
        $signature->model_id = $user->id;
        $signature->uuid = Str::uuid();
        $signature->filename = $imageName;
        $signature->document_filename = null;
        $signature->certified = false;
        $signature->from_ips = json_encode([request()->ip()]);
        $signature->save();
        // Redirect back with success message.
        return redirect()->back()->with('success', 'Engineer added successfully !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Engineer $engineer)
    {
        return view('app.engineers.show', compact('engineer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Engineer $engineer)
    {
        return view('app.engineers.edit', compact('engineer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEngineerRequest $request, Engineer $engineer)
    {
        $engineer->update($request->validated());
        return redirect()->route('engineer.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Engineer $engineer)
    {
        $engineer->delete();
        return redirect()->route('engineer.index');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $engineer = Engineer::withTrashed()->find($id);
        $engineer->restore();
        return redirect()->route('engineer.index');
    }

    /**
     * List all engineers.
     */
    public function list(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $queryStr = (isset($request->querystr) && !empty($request->querystr) ? $request->querystr : '');
        
        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;
        
        $query = Engineer::with('users')->orderByRaw(implode(',', $sorts));
        if(!empty($queryStr)):
            $query->where('name','LIKE','%'.$queryStr.'%');
        endif;
        $query->whereHas('users', function($query) use ($user) {

            $query->where('users.id',"=",$user->id);  

        });
        
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
                $k =0;
                $nestedDataContainer = [];
                
                    $data[] = [
                        'id' => $list->id,
                        'sl' => $i,
                        "name" => $list->first_name." ".$list->last_name,
                        'email' =>$list->email,
                        'status' => isset($list->status) ? 'Active' : 'Inactive',
                        'deleted_at' => isset($list->deleted_at) ? $list->deleted_at : NULL,
                        'delete_url' => route('engineer.destroy', $list->id),
                        'restore_url' => route('engineer.restore', $list->id),
                        'edit_url' => route('engineer.edit', $list->id),
                    ];
                    $i++;
                
            endforeach;
        endif;
        return response()->json(['last_page' => $last_page, 'data' => $data]); 
    }
}