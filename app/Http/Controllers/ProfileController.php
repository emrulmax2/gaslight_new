<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\FileRecord;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Creagia\LaravelSignPad\Signature;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    
    public function index()
    {
        return view('app.profile.index', [
            'title' => 'Profile - Gas Certificate APP',
            'user' => User::find(auth()->user()->id)
        ]);
    }

    public function update(ProfileUpdateRequest $request){
        $user = User::find(auth()->user()->id);
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'gas_safe_id_card' => $request->gas_safe_id_card,
            'oil_registration_number' => $request->oil_registration_number,
            'installer_ref_no' => $request->installer_ref_no,
        ];
        
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->input('password'));
        }
        $user->update($updateData);
        return response()->json(['message' => 'Profile updated successfully'], 200);
    }


    public function drawSignatureStore(Request $request)
    {

        $user = User::find(auth()->user()->id);

        $existingSignature = Signature::where('model_type', User::class)
            ->where('model_id', $user->id)
            ->first();

        if($request->input('sign') !== null) {
            if ($existingSignature) {
                $filePath = storage_path('app/public/' . $existingSignature->filename);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $existingSignature->delete();
            }

            $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
            $signatureData = base64_decode($signatureData);
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
        return response()->json(['message' => 'Signature could not be created'], 400);
    }


    public function fileUploadStore(Request $request)
    {
        $user = User::find(auth()->user()->id);
    
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


}

