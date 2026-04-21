<?php

namespace App\Http\Controllers\SuperAdmin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\UpdateSuperAdminRequest;
use App\Models\SuperAdmin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(){ 
        return view('app.superadmin.settings.admin-user.index', [ 
            'title' => 'Site Settings - Gas Certificate APP',
            'subtitle' => 'Manage Users',
            'breadcrumbs' => [
                ['label' => 'Site Settings', 'href' => route('superadmin.site.setting')],
                ['label' => 'Manage Users', 'href' => 'javascript:void(0);'],
            ],
        ]);
    }

    public function list(Request $request) {
        $queryStr = (isset($request->queryStr) && !empty($request->queryStr) ? $request->queryStr : '');
        $status = (isset($request->status) && $request->status > 0 ? $request->status : 1);

        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'ASC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = SuperAdmin::orderByRaw(implode(',', $sorts))->whereNot('id', 1);
        if(!empty($queryStr)):
            $query->where(function($q) use ($queryStr){
                $q->where('name', 'LIKE', '%'.$queryStr.'%')->orWhere('email', '%'.$queryStr.'%')
                   ->orWhere('mobile', '%'.$queryStr.'%');
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
                    'email' => $list->email,
                    'mobile' => $list->mobile,
                    'photo_url' => $list->photo_url,
                    'status' => $list->status,
                    'deleted_at' => isset($list->deleted_at) ? $list->deleted_at : NULL,
                ];
                $i++;
            endforeach;
        endif;
        
        return response()->json(['last_page' => $last_page, 'data' => $data , 'current_page' => $page * 1], 200);
    }

    public function store(AddUserRequest $request){
        $user = SuperAdmin::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'email_verified_at' => Carbon::now(),
            'last_login_ip' => $request->getClientIp(),
            'last_login_at' => Carbon::now(),
            'mobile' => $request->input('mobile') ?? null,
            'password' => Hash::make($request->input('password')),
            'role' => 'admin',
            'status' => (isset($request->status) && $request->status > 0 ? $request->status : 0)
        ]);
        if($user->id):
            if ($request->hasFile('photo')):
                // if (!empty($company->company_logo) && Storage::disk('public')->exists('companies/'.$company->id.'/'.$company->company_logo)):
                //     Storage::disk('public')->delete('companies/'.$company->id.'/'.$company->company_logo);
                // endif;

                $photo = $request->file('photo');
                $imageName = time().'_'.$user->id.'.'.$photo->getClientOriginalExtension();
                $path = $photo->storeAs('super-admins/', $imageName, 'public');
                
                $user->update(['photo' => $imageName]);
            endif;
            return response()->json(['success' => 'User successfully added!'], 200);
        else:
            return response()->json(['success' => 'Something went wrong. Please try again later'], 404);
        endif;
    }

    public function update(UpdateSuperAdminRequest $request){
        $user = SuperAdmin::findOrFail($request->id);

        $user->update([
            'name'   => $request->input('name'),
            'email'  => $request->input('email'),
            'mobile' => $request->input('mobile') ?? null,
            'status' => ($request->status > 0 ? $request->status : 0),
        ]);

        // Update password if provided
        if (!empty($request->input('password'))) {
            $user->update([
                'password' => Hash::make($request->input('password'))
            ]);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {

            if (!empty($user->photo) && Storage::disk('public')->exists('super-admins/'.$user->photo)) {
                Storage::disk('public')->delete('super-admins/'.$user->photo);
            }

            $photo = $request->file('photo');
            $imageName = time().'_'.$user->id.'.'.$photo->getClientOriginalExtension();

            $photo->storeAs('super-admins/', $imageName, 'public');

            $user->update([
                'photo' => $imageName
            ]);
        }

        return response()->json(['success' => 'User successfully updated!'], 200);
    }

    public function destroy($pack_id){
        $package = SuperAdmin::find($pack_id)->delete();

        return response()->json(['msg' => 'User successfully deleted.', 'red' => ''], 200);
    }

    public function restore($pack_id){
        $package = SuperAdmin::where('id', $pack_id)->withTrashed()->restore();

        return response()->json(['msg' => 'User Successfully Restored!', 'red' => ''], 200);
    }
}
