<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
//            ---------------------- admin Permissions Start------------------------
            ['admin_user-management_module-list'],
            ['admin_user-management_module-create'],
            ['admin_user-management_module-show'],
            ['admin_user-management_module-edit'],
            ['admin_user-management_module-delete'],
            ['admin_user-management_module-activity-log'],
            ['admin_user-management_module-activity-log-trash'],
            ['admin_user-management_permission-group-list'],
            ['admin_user-management_permission-group-create'],
            ['admin_user-management_permission-group-show'],
            ['admin_user-management_permission-group-edit'],
            ['admin_user-management_permission-group-activity-log'],
            ['admin_user-management_permission-group-activity-log-trash'],
            ['admin_user-management_permission-group-delete'],
            ['admin_user-management_permission-list'],
            ['admin_user-management_permission-create'],
            ['admin_user-management_permission-show'],
            ['admin_user-management_permission-edit'],
            ['admin_user-management_permission-delete'],
            ['admin_user-management_role-list'],
            ['admin_user-management_role-create'],
            ['admin_user-management_role-show'],
            ['admin_user-management_role-edit'],
            ['admin_user-management_role-delete'],
            ['admin_user-management_user-list'],
            ['admin_user-management_user-create'],
            ['admin_user-management_user-show'],
            ['admin_user-management_user-edit'],
            ['admin_user-management_user-activity-log'],
            ['admin_user-management_user-activity-log-trash'],
            ['admin_user-management_user-delete'],
            ['admin_user-management_backup-list'],
            ['admin_user-management_backup-create'],
            ['admin_user-management_backup-download'],
            ['admin_user-management_backup-delete'],
            ['admin_user-management_log-dashboard'],
            ['admin_user-management_log-list'],
            ['admin_user-management_log-show'],
            ['admin_user-management_log-download'],
            ['admin_user-management_log-delete'],
//            ---------------------- admin Permissions End------------------------

//            ---------------------- Manager Permissions Start------------------------
            ['manager_user-management_dashboard'],
//            ---------------------- Manager Permissions End------------------------
        ];
        $manager_permissions = [

            ['manager_user-management_dashboard'],

        ];


        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo($permissions);


        $manager_role = Role::create(['name' => 'manager']);
        $manager_role->givePermissionTo($manager_permissions);
    }
}
