<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminTUSeeder extends Seeder
{
    /**
     * Create 3 Admin TU users for transaction traceability.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create admin_tu role if it doesn't exist
        $role = Role::firstOrCreate(
            ['name' => 'admin_tu'],
            ['description' => 'Admin TU - manage financial transactions and master data']
        );

        // Assign relevant permissions to admin_tu role
        $permissions = Permission::whereIn('name', [
            'view_dashboard',
            'manage_students',
            'manage_classes',
            'manage_fee_matrix',
            'manage_transactions',
            'generate_receipts',
            'view_transactions',
            'view_reports',
            'export_reports',
            'view_students',
        ])->get();

        $role->syncPermissions($permissions);

        // Create 3 Admin TU users
        $admins = [
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@skmi.sch.id',
            ],
            [
                'name' => 'Ahmad Ridwan',
                'email' => 'ahmad@skmi.sch.id',
            ],
            [
                'name' => 'Dewi Rahmawati',
                'email' => 'dewi@skmi.sch.id',
            ],
        ];

        foreach ($admins as $admin) {
            $user = User::firstOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => bcrypt('password123'),
                    'position' => 'Admin TU',
                    'is_active' => true,
                ]
            );
            $user->assignRole('admin_tu');
        }

        $this->command->info('3 Admin TU users created successfully.');
    }
}
