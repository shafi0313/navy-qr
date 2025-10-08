<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminUserRequest;
use App\Http\Requests\UpdateAdminUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class AdminUserController extends Controller
{
    public function __construct()
    {
        if (! in_array(user()->role_id, [1])) {
            abort(403, 'You are not authorized to perform this action');
        }
    }
    

    public function index(Request $request)
    {
        if (! in_array(user()->role_id, [1])) {
            Alert::error('You are not authorized to perform this action');

            return back();
        }
        if ($request->ajax()) {
            $admin_users = User::with(['role:id,name'])->whereExamType(user()->exam_type)->orderBy('name');

            if (user()->role_id == 2) {
                $admin_users->where('team', user()->team);
            } elseif (user()->role_id != 1) {
                $admin_users->where('id', user()->id);
            }

            return DataTables::of($admin_users)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $path = imagePath('user', $row->image);

                    return '<img src="'.$path.'" width="70px" alt="image">';
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

    public function status(User $user)
    {
        if (! in_array(user()->role_id, [1, 2])) {
            return response()->json(['message' => 'You can not edit'], 500);
        }
        $user->is_active = $user->is_active == 1 ? 0 : 1;
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
        $data['exam_type'] = user()->exam_type;
        $data['password'] = bcrypt($request->password);

        // if ($request->hasFile('image')) {
        //     $data['image'] = imgWebpStore($request->image, 'user', [300, 300]);
        // }
        try {
            User::create($data);

            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    public function edit(Request $request, User $admin_user)
    {
        if (! in_array(user()->role_id, [1, 2])) {
            return response()->json(['message' => 'You cannot edit'], 403);
        }

        if ($request->ajax()) {
            $roles = Role::select('id', 'name')->get();
            $modal = view('admin.user.admin.edit', ['admin_user' => $admin_user, 'roles' => $roles])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(403, 'Unauthorized action.');
    }

    public function update(Request $request, UpdateAdminUserRequest $adminRequest, User $admin_user)
    {
        if (! in_array(user()->role_id, [1, 2])) {
            return response()->json(['message' => 'You can not edit'], 500);
        }
        $data = $adminRequest->validated();

        if ($request->filled('password')) {
            // if (! Hash::check($request->old_password, $admin_user->password)) {
            //     return response()->json(['message' => "Old Password Doesn't match!"], 500);
            // }
            $data['password'] = bcrypt($request->password);
        }
        // $image = $admin_user->image;
        // if ($request->hasFile('image')) {
        //     $data['image'] = imgWebpUpdate($request->file('image'), 'user', [300, 300], $image);
        // }
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
        if (! in_array(user()->role_id, [1, 2])) {
            return response()->json(['message' => 'You can not edit'], 500);
        }
        try {
            imgUnlink('user', $admin_user->image);
            $admin_user->delete();

            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
