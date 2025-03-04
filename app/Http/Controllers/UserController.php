<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function update(UpdateUserRequest $request, User $user)
    {
        //password data will not passed here if it is empty
        if($request->input('password') !== null) {
            $hashPassword = Hash::make($request->input('password'));
            $user->password = $hashPassword;
        } else {
            $user->password = $user->password;
        }
        //remove request password from request    
        $request->offsetUnset('password');

        $user->update($request->all());
        if($user->wasChanged()) {

            return response()->json(['message' => 'User updated successfully'], 200);
        }

        return response()->json(['message' => 'User Couldn\'t Updated'], 304);
    }
}
