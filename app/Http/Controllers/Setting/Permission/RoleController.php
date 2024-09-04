<?php

namespace App\Http\Controllers\Setting\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use UxWeb\SweetAlert\SweetAlert;
use Auth;

class RoleController extends Controller
{
    private $role;

    public function __construct()
    {
        // if (setting('email_verification')) {
        //     $this->middleware(['verified']);
        // }
        // $this->middleware(['permission:role-manage']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($error = $this->authorize('role-manage')) {
            return $error;
        }
        $roles = Role::with('users')->get();
        return view('setting.roles.index')->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($error = $this->authorize('role-add')) {
            return $error;
        }
        return view('setting.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($error = $this->authorize('role-add')) {
            return $error;
        }
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->validate($request, [
            'name' => 'required|min:4|max:20|regex:/^[a-zA-Z0-9\-_\.]+$/|unique:roles,name',
        ], [
          'regex' => 'Invalid Entry! Only letters,underscores, hype\'s and numbers are allowed',
        ]);

        $role = Role::create([
            'name' => str_replace(' ', '-', strtolower($request->name))
        ]);


        // Logging activity for created role
        // activity()
        //     ->performedOn($role)
        //     ->withProperties(['name'=>$role->name,'by'=>user()->username])
        //     ->causedBy(user())
        //     ->log('Role was created');
        return redirect()->back()->with('success', 'Role Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        if ($error = $this->authorize('permission-change')) {
            return $error;
        }
        if ($role->id == 1) {
            return redirect()->back()->withErrors('Supper Admin permissions can not be assigned.');
        }
        $roles          = Role::where('id', '!=', 1)->get(['id','name']);
        $perms          = $role->permissions()->pluck('name')->toArray();
        $allpermissions = Permission::pluck('name')->toArray();
        $permissions    = [];
        foreach ($allpermissions as $permission) {
            $permissions[$permission] = in_array($permission, $perms) ? 1 : 0;
        }
        // return $permissions;
        return view('setting.roles.show', compact(['role','permissions','roles']));
    }

    /**
     * Assign permissions to the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignPermission(Request $request, Role $role)
    {
        if ($error = $this->authorize('permission-change')) {
            return $error;
        }
        // return $request;
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = $role->permissions()->get();
        $role->revokePermissionTo($permissions);
        $role->givePermissionTo($request->permissions);

        // Logging activity for created role
        // activity()
        //     ->performedOn($role)
        //     ->withProperties(['name'=>$role->name,'by'=>user()->username])
        //     ->causedBy(user())
        //     ->log('Permission was assigned to Role '. $role->name);

        $role->syncPermissions($request->permissions);
        return redirect()->back()->with('success', 'Permissions assigned to Role successfully');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Role $role)
    {
        if ($error = $this->authorize('role-edit')) {
            return $error;
        }
        if ($request->ajax()) {
            $modal = view('setting.roles.edit')->with('role', $role)->render();
            return response()->json(['modal'=> $modal], 200);
        }
        return abort(500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        if ($error = $this->authorize('role-edit')) {
            return $error;
        }

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->validate($request, [
            'name' => 'required|regex:/^[a-zA-Z0-9\-_\.]+$/|min:3|max:20|unique:roles,name,'.$role->id
        ], [
          'regex' => 'Invalid Entry! Only letters,underscores,hypens and numbers are allowed',
        ]);

        $role->name = str_replace(' ', '-', strtolower($request->name));
        $role->save();

        // Logging activity for created role
        // activity()
        //     ->performedOn($role)
        //     ->withProperties(['name'=>$role->name,'by'=>user()->username])
        //     ->causedBy(user())
        //     ->log('Role was updated');

        return redirect()->back()->with('success', 'Role Updated Sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if ($error = $this->authorize('role-delete')) {
            return $error;
        }
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //Default role cannot be deleted
        if (!$role->removable) {
            return redirect()->back()->withErrors('This role cannot be deleted');
        }

        // Logging activity for created role
        // activity()
        //     ->performedOn($role)
        //     ->withProperties(['name'=>$role->name,'by'=>user()->username])
        //     ->causedBy(user())
        //     ->log('Role was deleted');

        $role->revokePermissionTo($role->permissions()->get());
        $role->delete();
        return response()->json(['message'=> 'Role Deleted Successfully'], 200);
    }
}
