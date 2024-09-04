<?php

namespace App\Http\Controllers\Setting\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use UxWeb\SweetAlert\SweetAlert;
use Auth;

class PermissionController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:permission-manage']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($error = $this->authorize('permission-manage')) {
            return $error;
        }
        $permissions = Permission::all();
        return view('setting.permission.index')->with('permissions', $permissions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($error = $this->authorize('permission-create')) {
            return $error;
        }
        return view('setting.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($error = $this->authorize('permission-create')) {
            return $error;
        }
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->validate($request, [
            'name' => 'required|regex:/^[a-zA-Z0-9\-_\.]+$/|unique:permissions',
            'module' => 'required|regex:/^[a-zA-Z0-9\-_\.]+$/|string',
        ], [
            'regex' => 'Invalid Entry! Only letters,underscores,hypens and numbers are allowed',
        ]);

        $permission =  Permission::create([
            'name' => str_replace(' ', '-', strtolower($request->name)),
            'module' => str_replace(' ', '-', strtolower($request->module)),
        ]);

        // Logging activity for created role
        // activity()->performedOn($permission)->withProperties(['name' => $permission->name, 'by' => user()->username])->causedBy(user())->log('Permission was created');

        return redirect()->back()->with('success', 'Permission Created Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Permission $permission)
    {
        if ($error = $this->authorize('permission-edit')) {
            return $error;
        }
        if ($request->ajax()) {
            $modal = view('setting.permission.edit')->with('permission', $permission)->render();
            return response()->json(['modal' => $modal], 200);
        }
        return abort(500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        if ($error = $this->authorize('permission-edit')) {
            return $error;
        }
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        $this->validate($request, [
            'name' => 'required|regex:/^[a-zA-Z0-9\-_\.]+$/|unique:permissions,name,' . $permission->id,
            'module' => 'required|regex:/^[a-zA-Z0-9\-_\.]+$/|string',
        ], [
            'regex' => 'Invalid Entry! Only letters,underscores,hypens and numbers are allowed',
        ]);

        $permission->name   = str_replace(' ', '-', strtolower($request->name));
        $permission->module = str_replace(' ', '-', strtolower($request->module));

        // Logging activity for created role
        // activity()->performedOn($permission)->withProperties(['name' => $permission->name, 'by' => user()->username])->causedBy(user())->log('Permission was updated');

        $permission->save();

        return redirect()->back()->with('success', 'Permission Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        if ($error = $this->authorize('permission-delete')) {
            return $error;
        }
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        //default permission cannot be deleted
        if (!$permission->removable) {
            return redirect()->back()->withErrors('This permission cannot be deleted');
        }

        // Logging activity for created role
        // activity()->performedOn($permission)->withProperties(['name' => $permission->name, 'by' => user()->username])->causedBy(user())->log('Permission was deleted');

        $permission->delete();

        return response()->json(['message'=> 'Permission Deleted Successfully'], 200);
    }
}
