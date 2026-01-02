<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Number;

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


    public function list(Request $request)
    {
               
        $queryStr = (isset($request->querystr) && !empty($request->querystr) ? $request->querystr : '');
        
        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = User::with('company', 'userpackage')->orderByRaw(implode(',', $sorts));
        if(!empty($queryStr)):
            $query->where('name','LIKE','%'.$queryStr.'%');
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
                        "name" => $list->name,
                        'company_name' => isset($list->company) ? $list->company->company_name : '',
                        'company_id' => isset($list->company) ? $list->company->id : '',
                        'email' => $list->email ?? '',
                        'mobile' => $list->mobile ?? '',
                        'status' => $list->active ?? 0,
                        'deleted_at' => isset($list->deleted_at) ? $list->deleted_at : NULL,
                        'package' => $list->userpackage->package->title ?? '',
                        'price' => isset($list->userpackage->price) && $list->userpackage->price > 0 ? Number::currency($list->userpackage->price, 'GBP') : Number::currency(0, 'GBP'),
                        'next_renew' => isset($list->userpackage->end) && !empty($list->userpackage->end) ? date('jS F, Y', strtotime($list->userpackage->end)) : '',
                        'impersonate_url' => route('impersonate', $list->id),
                    ];
                    $i++;
                

                    
            endforeach;
        endif;
        return response()->json(['last_page' => $last_page, 'data' => $data]); 
    }
}
