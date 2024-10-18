<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        if ($request->ajax()) {
            $admin_users = User::with(['role:id,name'])->orderBy('name');
            return DataTables::of($admin_users)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $path = imagePath('user', $row->image);
                    return '<img src="' . $path . '" width="70px" alt="image">';
                })
                ->addColumn('is_active', function ($row) {
                    return view('button', ['type' => 'is_active', 'route' => route('admin.admin_users.is_active', $row->id), 'row' => $row->is_active]);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.admin-users.edit', $row->id), 'row' => $row]);

                    $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.admin-users.destroy', $row->id), 'row' => $row, 'src' => 'dt']);

                    return $btn;
                })
                ->rawColumns(['image', 'is_active', 'action'])
                ->make(true);
        }
        $data['roles'] = Role::select('id', 'name')->get();
        return view('admin.user.admin.index', $data);
    }

    function status(User $user)
    {
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
        $data = $request->validated();
        $data['user_name'] = explode('@', $request->email)[0];
        $data['password'] = bcrypt($request->password);
        if ($request->hasFile('image')) {
            $data['image'] = imgWebpStore($request->image, 'user', [300, 300]);
        }
        try {
            User::create($data);
            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    public function edit(Request $request, User $admin_user)
    {
        if ($request->ajax()) {
            $roles = Role::select('id', 'name')->get();
            $modal = view('admin.user.admin.edit')->with(['admin_user' => $admin_user, 'roles' => $roles])->render();
            return response()->json(['modal' => $modal], 200);
        }
        return abort(500);
    }

    public function update(Request $request, UpdateAdminUserRequest $adminRequest, User $admin_user)
    {
        $data = $adminRequest->validated();
        if ($request->filled('password')) {
            if (!Hash::check($request->old_password, $admin_user->password)) {
                return response()->json(['message' => "Old Password Doesn't match!"], 500);
            }
            $data['password'] = bcrypt($request->password);
        }
        $image = $admin_user->image;
        if ($request->hasFile('image')) {
            $data['image'] = imgWebpUpdate($request->file('image'), 'user', [300, 300], $image);
        }
        DB::beginTransaction();
        try {
            $admin_user->update($data);
            DB::commit();
            return response()->json(['message' => 'The information has been updated'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Oops, something went wrong. Please try again later.'], 500);
        }
    }


    public function destroy(User $admin_user)
    {
        try {
            imgUnlink('user', $admin_user->image);
            $admin_user->delete();
            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
