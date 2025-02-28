<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\User;
use Creagia\LaravelSignPad\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Random\Engine;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('app.staffs.index', [
            'staffs' => Staff::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.staffs.create');
    }


    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'sign' => 'required',
        ]);
        $hashPassword = Hash::make($request->input('password'));
        $staff = Staff::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $hashPassword, 
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $hashPassword,
            'status' => 1,
        ]);


        $signatureData = str_replace('data:image/png;base64,', '', $validatedData['sign']);
        $signatureData = base64_decode($signatureData);
        $imageName = 'signatures/' . Str::uuid() . '.png';
        Storage::disk('public')->put($imageName, $signatureData);
        $signature = new Signature();
        $signature->model_type = Staff::class;
        $signature->model_id = $staff->id;
        $signature->uuid = Str::uuid();
        $signature->filename = $imageName;
        $signature->document_filename = null;
        $signature->certified = false;
        $signature->from_ips = json_encode([request()->ip()]);
        $signature->save();
        // Redirect back with success message.
        return redirect()->back()->with('success', 'Staff added successfully !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        return view('app.staffs.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        return view('app.staffs.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        $staff->update($request->validated());
        return redirect()->route('staff.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $staff = Staff::withTrashed()->find($id);
        $staff->restore();
        return redirect()->route('staff.index');
    }

    /**
     * List all staffs.
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
        
        $query = Staff::with('company')->orderByRaw(implode(',', $sorts));
        if(!empty($queryStr)):
            $query->where('name','LIKE','%'.$queryStr.'%');
        endif;
        $query->whereHas('company', function($query) use ($user) {

            $query->where('id', $user->company->id);

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
                        'delete_url' => route('staff.destroy', $list->id),
                        'restore_url' => route('staff.restore', $list->id),
                        'edit_url' => route('staff.edit', $list->id),
                    ];
                    $i++;
                
            endforeach;
        endif;
        return response()->json(['last_page' => $last_page, 'data' => $data]); 
    }
}