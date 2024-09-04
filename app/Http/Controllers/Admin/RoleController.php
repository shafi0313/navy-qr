<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::with(['createdBy:id,name'])->orderBy('name');
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('is_active', function ($row) {
                    return view('button', ['type' => 'is_active', 'route' => route('admin.roles.is_active', $row->id), 'row' => $row->is_active]);

                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.roles.edit', $row->id), 'row' => $row]);
                    $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.roles.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                    return $btn;
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }
        return view('admin.role.index');
    }

    function status(Role $role)
    {
        $role->is_active = $role->is_active  == 1 ? 0 : 1;
        try {
            $role->save();
            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = imgWebpStore($request->image, 'slider', [1920, 1080]);
        }

        try {
            Slider::create($data);
            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        if ($request->ajax()) {
            $modal = view('admin.slider.edit')->with(['slider' => $role])->render();
            return response()->json(['modal' => $modal], 200);
        }
        return abort(500);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $data = $role->validated();
        $image = $role->image;
        if ($request->hasFile('image')) {
            $data['image'] = imgWebpUpdate($request->image, 'user', [1920, 1080], $image);
        }
        try {
            $role->update($data);
            return response()->json(['message' => 'The information has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            imgUnlink('slider', $role->image);
            $role->delete();
            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
