<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreAdminUserRequest;
use App\Http\Requests\UpdateAdminUserRequest;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        if ($error = $this->authorize('admin-user-manage')) {
            return $error;
        }

        if ($request->ajax()) {
            $admin_users = User::whereIn('role', [1]);
            return DataTables::of($admin_users)
                ->addIndexColumn()
                ->addColumn('gender', function ($row) {
                    return gender($row->gender);
                })
                ->addColumn('permission', function ($row) {
                    return 'Admin';
                })
                ->addColumn('image', function ($row) {
                    $path = imagePath('user', $row->image);
                    return '<img src="' . $path . '" width="70px" alt="image">';
                })
                ->addColumn('is_active', function ($row) {
                    if (userCan('admin-user-edit')) {
                        return view('button', ['type' => 'is_active', 'route' => route('admin.admin_users.is_active', $row->id), 'row' => $row->is_active]);
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (userCan('admin-user-edit')) {
                        $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.admin-users.edit', $row->id), 'row' => $row]);
                    }
                    if (userCan('admin-user-delete')) {
                        $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.admin-users.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                    }
                    return $btn;
                })
                ->rawColumns(['image', 'is_active', 'action'])
                ->make(true);
        }
        // $roles = Role::all();
        $data['genders'] = config('datum.gender');
        return view('admin.user.admin.index', $data);
    }

    function status(User $user)
    {
        if ($error = $this->authorize('admin-user-edit')) {
            return $error;
        }
        $user->is_active = $user->is_active  == 1 ? 0 : 1;
        try {
            $user->save();
            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }


    public function store(StoreAdminUserRequest $request)
    {
        if ($error = $this->authorize('admin-user-add')) {
            return $error;
        }
        $data = $request->validated();
        $data['password'] = bcrypt($request->password);
        $data['role'] = 1;
        if ($request->hasFile('image')) {
            $data['image'] = imgWebpStore($request->image, 'user', [300, 300]);
        }
        try {
            $admin_user = User::create($data);
            // $admin_user->assignRole($request->role);
            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    public function edit(Request $request, User $admin_user)
    {
        if ($error = $this->authorize('admin-user-edit')) {
            return $error;
        }
        if ($request->ajax()) {
            $roles = Role::all();
            $genders = config('var.genders');
            $modal = view('admin.user.admin.edit')->with(['admin_user' => $admin_user, 'roles' => $roles, 'genders' => $genders])->render();
            return response()->json(['modal' => $modal], 200);
        }
        return abort(500);
    }

    public function update(Request $request, UpdateAdminUserRequest $adminRequest, User $admin_user)
    {
        if ($error = $this->authorize('admin-user-add')) {
            return $error;
        }
        $data = $adminRequest->validated();
        if ($request->password && !Hash::check($request->old_password, $admin_user->password)) {
            return response()->json(['message' => "Old Password Doesn't match!"], 500);
        }
        if (isset($request->password)) {
            $data['password'] = bcrypt($request->password);
        }
        $image = $admin_user->image;
        if ($request->hasFile('image')) {
            $data['image'] = imgWebpUpdate($request->image, 'user', [300, 300], $image);
        }
        try {
            $admin_user->update($data);
            // if($request->role){
            //     ModelHasRole::whereModel_id($admin_user->id)->update(['role_id'=>Role::whereName($request->role)->first()->id]);
            // }
            return response()->json(['message' => 'The information has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }

    public function destroy(User $admin_user)
    {
        if ($error = $this->authorize('admin-user-delete')) {
            return $error;
        }
        try {
            imgUnlink('brand', $admin_user->image);
            $admin_user->delete();
            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
