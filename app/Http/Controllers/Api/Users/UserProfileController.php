<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserProfilePasswordUpdateRequest;
use App\Http\Requests\Api\UserProfileUpdateRequest;
use App\Models\User;
use Creagia\LaravelSignPad\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserProfileController extends Controller
{
    public function updateProfile(UserProfileUpdateRequest $request){
        $user = $request->user();

        $updateData = [];

        if($request->has('name')):
            $updateData['name'] = (isset($request->name) && !empty($request->name) ? $request->name : null);
        endif;

        if($request->has('email')):
            $updateData['email'] = (isset($request->email) && !empty($request->email) ? $request->email : null);
        endif;

        if($request->hasFile('photo')):
            if(isset($user->photo) && !empty($user->photo) && Storage::disk('public')->exists('public/users/'.$user->id.'/'.$user->photo)):
                Storage::disk('public')->delete('public/users/'.$user->id.'/'.$user->photo);
            endif;

            $photo = $request->file('photo');
            $imageName = 'Avatar_'.$user->id.'_'.time() . '.' . $request->photo->getClientOriginalExtension();
            $path = $photo->storeAs('users/'.$user->id, $imageName, 'public');
            $updateData['photo'] = $imageName;
        endif;

        if($request->has('gas_safe_id_card')):
            $updateData['gas_safe_id_card'] = (isset($request->gas_safe_id_card) && !empty($request->gas_safe_id_card) ? $request->gas_safe_id_card : null);
        endif;

         if($request->has('oil_registration_number')):
            $updateData['oil_registration_number'] = (isset($request->oil_registration_number) && !empty($request->oil_registration_number) ? $request->oil_registration_number : null);
        endif;

         if($request->has('installer_ref_no')):
            $updateData['installer_ref_no'] = (isset($request->installer_ref_no) && !empty($request->installer_ref_no) ? $request->installer_ref_no : null);
        endif;


        $user->update($updateData);

         return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo,
                'gas_safe_id_card' => $user->gas_safe_id_card,
                'oil_registration_number' => $user->oil_registration_number,
                'installer_ref_no' => $user->installer_ref_no,
            ],
        ]);
    }

    public function updatePassword(UserProfilePasswordUpdateRequest $request){
        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->validated('password'))
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
        ]);
    }
    
    public function updateDrawSignature(Request $request){
        $user = $request->user();

        $existingSignature = Signature::where('model_type', User::class)->where('model_id', $user->id)->first();

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

            return response()->json([
                'success' => true,
                'message' => 'Signature updated successfully',
                'filename' => $imageName
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Signature could not be updated'
        ], 400);

    }

    public function updateFileSignature(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'sign' => 'required|file|mimes:png,jpg,jpeg|max:2048',
        ]);

        $existingSignature = Signature::where('model_type', User::class)->where('model_id', $user->id)->first();

        if ($existingSignature) {
            $filePath = storage_path('app/public/' . $existingSignature->filename);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $existingSignature->delete();
        }

        if ($request->hasFile('sign')) {
            $file = $request->file('sign');
            $imageName = 'signatures/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put($imageName, file_get_contents($file));
            
            $signature = new Signature();
            $signature->model_type = User::class;
            $signature->model_id = $user->id;
            $signature->uuid = Str::uuid();
            $signature->filename = $imageName;
            $signature->document_filename = null;
            $signature->certified = false;
            $signature->from_ips = json_encode([$request->ip()]);
            $signature->save();

            return response()->json([
                'success' => true,
                'message' => 'Signature file uploaded successfully',
                'filename' => $imageName
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No signature file was uploaded'
        ], 400);
    }
}
