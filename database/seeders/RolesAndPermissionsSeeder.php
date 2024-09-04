<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $pers = [
            'dashboard' => [
                'access-dashboard',
                'dashboard-manage',
            ],
            'user' => [
                'user-manage',
                'user-add',
                'user-edit',
                'user-delete',
                'user-impersonate',
                'user-access-dashboard',
            ],
            // 'activity' => [
            //     'activity-manage',
            //     'activity-add',
            //     'activity-edit',
            //     'activity-delete'
            // ],
            'setting' => [
                'setting-manage',
                'language-manage',
            ],
            // 'visitor-info' => [
            //     'visitor-info-manage',
            //     'visitor-info-delete'
            // ],
            'roles-&-permission' => [
                'roles-&-permission-manage',
            ],
            'role' => [
                'role-manage',
                'role-add',
                'role-edit',
                'role-delete',
            ],
            'permission' => [
                'permission-manage',
                'permission-add',
                'permission-edit',
                'permission-delete',
            ],
            'app-backup' => [
                'app-backup-manage',
                'app-backup-delete'
            ],

        ];
        foreach ($pers as $per => $val) {
            foreach ($val as $name) {
                Permission::create([
                    'module' => $per,
                    'name' => $name,
                    'removable' => 0,
                ]);
            }
        }

        $superadmin = Role::create(['name' => 'superadmin', 'removable' => 0]);
        $admin = Role::create(['name' => 'admin', 'removable' => 0]);
        $admin->givePermissionTo(Permission::all());
    }
}
