<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\FileRecord;
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


    public function store(StoreStaffRequest $request)
    {
        $user_id = auth()->user()->id;

        $currentUser = User::find($user_id);
        $hashPassword = Hash::make($request->input('password'));

        $staff = Staff::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $hashPassword, 
            'gas_safe_id_card' => $request->input('gas_safe_id_card'),
            'oil_registration_number' => $request->input('oil_registration_number'),
            'installer_ref_no' => $request->input('installer_ref_no'),
            'status' => 1,
        ]);
        $staff->companies()->attach($currentUser->company->id);
        $staff->save();

        if($request->input('sign') !== null) {
            $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
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
        }
        // Redirect back with success message.
        return response()->json(['message' => 'Staff created successfully'], 201);
    }


    public function drawSignatureStore(Request $request)
    {
        $user_id = auth()->user()->id;

        $currentUser = User::find($user_id);

        $staff = Staff::find($request->edit_id);

        if($request->input('sign') !== null) {
            $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
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

            return response()->json(['message' => 'Signature created successfully'], 201);
        }
        return response()->json(['message' => 'Signature could not be created'], 400);
    }


    public function fileUploadStore(Request $request)
    {
        $user_id = auth()->user()->id;
        $currentUser = User::find($user_id);
        $staff = Staff::find($request->id);
    
        if ($request->input('file_id') !== null) {
            $fileRecord = FileRecord::find($request->input('file_id'));
    
            if ($fileRecord) {
                // Move the file to the signatures directory
                $newFilePath = 'signatures/' . Str::uuid() . '.' . pathinfo($fileRecord->name, PATHINFO_EXTENSION);
                Storage::disk('public')->move($fileRecord->path, $newFilePath);
    
                // Create a new signature record
                $signature = new Signature();
                $signature->model_type = Staff::class;
                $signature->model_id = $staff->id;
                $signature->uuid = Str::uuid();
                $signature->filename = $newFilePath;
                $signature->document_filename = null;
                $signature->certified = false;
                $signature->from_ips = json_encode([request()->ip()]);
                $signature->save();
    
                return response()->json(['message' => 'Signature uploaded successfully'], 201);
            }
        }
        return response()->json(['message' => 'Signature could not be uploaded'], 400);
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
        //password data will not passed here if it is empty
        if($request->input('password') !== null) {
            $hashPassword = Hash::make($request->input('password'));
            $staff->password = $hashPassword;
        } else {
            $staff->password = $staff->password;
        }
        //remove request password from request    
        $request->offsetUnset('password');

        $staff->update($request->all());
        if($staff->wasChanged()) {

            return response()->json(['message' => 'Staff updated successfully'], 200);
        }

        return response()->json(['message' => 'Staff Couldn\'t Updated'], 400);
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

        $query = Staff::leftJoin('company_staff', 'staff.id', '=', 'company_staff.staff_id')
                ->leftJoin('companies', 'company_staff.company_id', '=', 'companies.id')
                ->select('staff.*', 'companies.company_name as company_name','companies.id as company_id')
                ->orderByRaw(implode(',', $sorts));
        if(!empty($queryStr)):
            $query->where('name','LIKE','%'.$queryStr.'%');
        endif;
        $query->where(function($query) use ($user) {
            $query->where('companies.id', $user->company->id);
                  //->orWhereNull('companies.id');
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
        $Query->load(['signature' => function($query) {
                $query->orderBy('created_at', 'desc');
            }]);
        $data = array();

        if(!empty($Query)):
            $i = 1;
            foreach($Query as $list):
                $k =0;
                $nestedDataContainer = [];
                
                    $data[] = [
                        'id' => $list->id,
                        'sl' => $i,
                        "name" => $list->name,
                        'company_name' => $list->company_name,
                        'company_id' => $list->company_id,
                        'email' =>$list->email,
                        'status' => isset($list->status) ? 'Active' : 'Inactive',
                        'deleted_at' => isset($list->deleted_at) ? $list->deleted_at : NULL,
                        'delete_url' => route('staff.destroy', $list->id),
                        'restore_url' => route('staff.restore', $list->id),
                        'edit_url' => route('staff.edit', $list->id),
                        'gas_safe_id_card' => $list->gas_safe_id_card,
                        'oil_registration_number' => $list->oil_registration_number,
                        'installer_ref_no' => $list->installer_ref_no,

                        'signature' => $list->signature ? Storage::disk('public')->url($list->signature->filename) : null,
                    ];
                    $i++;
                
            endforeach;
        endif;
        return response()->json(['last_page' => $last_page, 'data' => $data]); 
    }
}