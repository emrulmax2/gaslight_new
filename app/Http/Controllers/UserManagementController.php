<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;
use App\Models\FileRecord;
use Creagia\LaravelSignPad\Signature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{

    public function index()
    {
        
        return view('app.users.index', [
            'title' => 'Users List - Gas Certificate App',
            'users' => User::all(),
        ]);
    }


    public function store(UserStoreRequest $request)
    {

        $hashPassword = Hash::make($request->input('password'));

        $user = User::create([
            'parent_id' => Auth::user()->id,
            'role' => 'staff',
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $hashPassword, 
            'gas_safe_id_card' => $request->input('gas_safe_id_card'),
            'oil_registration_number' => $request->input('oil_registration_number'),
            'installer_ref_no' => $request->input('installer_ref_no'),
            'active' => 1,
            'fast_login' => 0
        ]);
        $user->companies()->attach(Auth::user()->company->id);
        $user->save();
        return response()->json(['message' => 'User created successfully'], 201);
    }


    public function drawSignatureStore(Request $request)
    {
  
        $user = User::find($request->edit_id);

        $existingSignature = Signature::where('model_type', User::class)
            ->where('model_id', $user->id)
            ->first();

        if($request->sign !== null) {

            if ($existingSignature) {
                $filePath = storage_path('app/public/' . $existingSignature->filename);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $existingSignature->delete();
            }

            $signatureData = str_replace('data:image/png;base64,', '', $request->sign);
            $signatureData = base64_decode($signatureData);
            if(strlen($signatureData) > 2621) {
                $imageName = 'signatures/' . Str::uuid() . '.png';
                Storage::disk('public')->put($imageName, $signatureData);
                $signature = new Signature();
                $signature->model_type = User::class;
                $signature->model_id = $user->id;
                $signature->uuid = Str::uuid();
                $signature->filename = $imageName;
                $signature->document_filename = null;
                $signature->certified = false;
                $signature->from_ips = json_encode([request()->ip()]);
                $signature->save();
                return response()->json(['message' => 'Signature created successfully'], 201);
            }
        }
        return response()->json(['message' => 'Signature could not be created'], 400);
    }


    public function fileUploadStore(Request $request)
    {

        $user = User::find($request->id);
    
        if ($request->input('file_id') !== null) {
            $fileRecord = FileRecord::find($request->input('file_id'));
    
            if ($fileRecord) {
                $newFilePath = 'signatures/' . Str::uuid() . '.' . pathinfo($fileRecord->name, PATHINFO_EXTENSION);
                Storage::disk('public')->move($fileRecord->path, $newFilePath);
    
                $signature = new Signature();
                $signature->model_type = User::class;
                $signature->model_id = $user->id;
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
    
    public function show(User $user)
    {
        return view('app.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load(['signature' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $signature = $user->signature ? Storage::disk('public')->url($user->signature->filename) : '';

        return view('app.users.edit', [
            'user' => $user,
            'signature' => $signature,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, $user_id)
    {
        $user = User::FindOrFail($user_id);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'gas_safe_id_card' => $request->gas_safe_id_card,
            'oil_registration_number' => $request->oil_registration_number,
            'installer_ref_no' => $request->installer_ref_no,
        ];

        if($request->password !== null) {
            $hashPassword = Hash::make($request->password);
            $updateData['password'] = $hashPassword;
        }

        $user->update($updateData);

        return response()->json(['message' => 'User updated successfully'], 200);
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $user = User::withTrashed()->find($id);
        $user->restore();
        return redirect()->route('users.index');
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $user_company_id = (isset($user->companies[0]->id) && $user->companies[0]->id > 0 ? $user->companies[0]->id : 0);

        $queryField = isset($request->queryField) ? $request->queryField : 'name';
        $queryType = isset($request->queryType) ? $request->queryType : 'like';
        $queryValue = isset($request->queryValue) ? $request->queryValue : '';
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);

        
        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = User::leftJoin('company_staff', 'users.id', '=', 'company_staff.user_id')
                ->leftJoin('companies', 'company_staff.company_id', '=', 'companies.id')
                ->select('users.*', 'companies.company_name as company_name','companies.id as company_id')
                ->where('company_staff.company_id', $user_company_id)
                ->orderByRaw(implode(',', $sorts));

        

        if (!empty($queryField)):
            if ($queryType === 'like') {
                $query->where($queryField, 'LIKE', '%' . $queryValue . '%');
            } else {
                $query->where($queryField, $queryType, $queryValue);
            }
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
                        'email' =>$list->email,
                        'status' => isset($list->status) ? 'Active' : 'Inactive',
                        'deleted_at' => isset($list->deleted_at) ? $list->deleted_at : NULL,
                        'delete_url' => route('users.destroy', $list->id),
                        'restore_url' => route('users.restore', $list->id),
                        'edit_url' => route('users.edit', $list->id),
                        'gas_safe_id_card' => $list->gas_safe_id_card,
                        'oil_registration_number' => $list->oil_registration_number,
                        'installer_ref_no' => $list->installer_ref_no,
                        'visibility_control' => $user->parent_id,
                        'signature' => $list->signature ? Storage::disk('public')->url($list->signature->filename) : null,
                        'package_id' => (isset($list->userpackage->pricing_package_id) && !empty($list->userpackage->pricing_package_id) ? $list->userpackage->pricing_package_id : ''),
                        'package' => (isset($list->userpackage->package->title) && !empty($list->userpackage->package->title) ? $list->userpackage->package->title : ''),
                        'package_start' => (isset($list->userpackage->start) && !empty($list->userpackage->start) ? date('jS F, Y', strtotime($list->userpackage->start)) : ''),
                        'package_end' => (isset($list->userpackage->end) && !empty($list->userpackage->end) ? date('jS F, Y', strtotime($list->userpackage->end)) : ''),
                    ];
                    $i++;
                
            endforeach;
        endif;
        return response()->json(['last_page' => $last_page,'current_page'=> $page*1 , 'data' => $data]); 
    }
}
