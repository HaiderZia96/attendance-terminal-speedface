<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'admin_user-management_module-list', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_module-create', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_module-show', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_module-edit', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_module-delete', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_module-activity-log', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_module-activity-log-trash', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-list', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-create', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-show', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-edit', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-activity-log', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-activity-log-trash', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-group-delete', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-list', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-create', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-show', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-edit', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_permission-delete', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_role-list', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_role-create', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_role-show', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_role-edit', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_role-delete', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-list', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-create', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-show', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-edit', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-activity-log', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-activity-log-trash', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_user-delete', 'group_id' => 1, 'module_id'=>1],
            ['name' => 'admin_user-management_backup-list', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_backup-create', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_backup-download', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_backup-delete', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_log-dashboard', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_log-list', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_log-show', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_log-download', 'group_id' => 2, 'module_id'=>1],
            ['name' => 'admin_user-management_log-delete', 'group_id' => 2, 'module_id'=>1],

            ['name' => 'manager_user-management_dashboard', 'group_id' => 1, 'module_id'=>2],
            // Attendance
            ['name' => 'manager_attendance_attendance-list', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_attendance-create', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_attendance-show', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_attendance-pg-attendance-sync', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_attendance-erp-attendance-sync', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_attendance-edit', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_attendance-delete', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_attendance-delete-all', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_attendance-activity-log', 'group_id' => 3, 'module_id'=>2],
            // Config
            ['name' => 'manager_attendance_config-list', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_config-create', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_config-show', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_config-edit', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_config-delete', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_config-activity-log', 'group_id' => 3, 'module_id'=>2],
            // Employee
            ['name' => 'manager_attendance_employee-list', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_employee-create', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_employee-show', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_employee-sync', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_employee-edit', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_employee-delete', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_employee-sync-to-erp', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_employee-activity-log', 'group_id' => 3, 'module_id'=>2],

            // Get Employee
            ['name' => 'manager_attendance_get-employee-list', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_get-employee-create', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_get-employee-show', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_get-employee-edit', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_get-employee-delete', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_get-employee-activity-log', 'group_id' => 3, 'module_id'=>2],
            // Get Employee History
            ['name' => 'manager_attendance_get-employee-history-list', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_get-employee-history-delete', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_get-employee-history-delete-all', 'group_id' => 3, 'module_id'=>2],
            // Profile
            ['name' => 'manager_attendance_profile-edit', 'group_id' => 3, 'module_id'=>2],
            // Screen
            ['name' => 'manager_attendance_screen-list', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_screen-create', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_screen-show', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_screen-screen', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_screen-refresh', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_screen-edit', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_screen-delete', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_screen-activity-log', 'group_id' => 3, 'module_id'=>2],
            // Screen IP
            ['name' => 'manager_attendance_screen-ip-list', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_screen-ip-create', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_screen-ip-show', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_screen-ip-edit', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_screen-ip-delete', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_screen-ip-activity-log', 'group_id' => 3, 'module_id'=>2],
            //  Set Attendance
            ['name' => 'manager_attendance_set-attendance-histories-list', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_set-attendance-histories-delete', 'group_id' => 3, 'module_id'=>2],
            ['name' => 'manager_attendance_set-attendance-histories-delete-all', 'group_id' => 3, 'module_id'=>2],

        ];
        foreach ($permissions as $permission){
            Permission::create($permission);
        }
    }
}
