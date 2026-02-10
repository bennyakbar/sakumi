<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Permissions
        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_students',
            'manage_classes',
            'manage_fee_matrix',
            'manage_transactions',
            'generate_receipts',
            'view_transactions',
            'view_reports',
            'export_reports',
            'view_audit_log',
            'view_students', // Added to fix seeder error
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 2. Define Roles and assign permissions

        // Super Admin
        $superAdmin = Role::create([
            'name' => 'super_admin',
            'description' => 'Full system access'
        ]);
        // implicitly has all permissions via Gate::before rule usually, but here we can just give all
        $superAdmin->givePermissionTo(Permission::all());

        // Bendahara
        $bendahara = Role::create([
            'name' => 'bendahara',
            'description' => 'Treasurer - handle all financial transactions'
        ]);
        $bendahara->givePermissionTo([
            'view_dashboard',
            'manage_students',
            'manage_transactions',
            'view_reports',
            'export_reports',
            'manage_fee_matrix',
            'generate_receipts',
            'view_transactions',
        ]);

        // Kepala Sekolah
        $kepalaSekolah = Role::create([
            'name' => 'kepala_sekolah',
            'description' => 'Principal - view reports and dashboard only'
        ]);
        $kepalaSekolah->givePermissionTo([
            'view_dashboard',
            'view_students', // implied permission need
            'view_transactions',
            'view_reports',
            'export_reports',
        ]);

        // Ensure implied permissions exist if not in list
        // 'view_students' was not in main list, add it dynamically or update list
        if (Permission::where('name', 'view_students')->doesntExist()) {
            Permission::create(['name' => 'view_students']);
            $kepalaSekolah->givePermissionTo('view_students');
        }

        // Operator TU
        $operatorTU = Role::create([
            'name' => 'operator_tu',
            'description' => 'Administration staff - manage master data'
        ]);
        $operatorTU->givePermissionTo([
            'view_dashboard',
            'manage_students',
            'manage_classes',
            'view_transactions',
            'view_reports',
            // 'view_students' is implied by manage_students usually, but good to be explicit
        ]);

        // Auditor
        $auditor = Role::create([
            'name' => 'auditor',
            'description' => 'Auditor - read-only access to all data'
        ]);
        $auditor->givePermissionTo([
            'view_dashboard',
            'view_students',
            'view_transactions',
            'view_reports',
            'view_audit_log',
            'export_reports',
        ]);
    }
}
