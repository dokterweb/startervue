<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        //Reset Permission Cache
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        
        // Permissions
        $permissions = [

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Roles
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Permissions
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',

            // Transaksi
            'transaksi.view',
            'transaksi.create',
            'transaksi.edit',
            'transaksi.delete',

            // Pembayaran
            'pembayaran.view',
            'pembayaran.create',
            'pembayaran.edit',
            'pembayaran.delete',

            // Laporan
            'laporan.view',

            // Settings
            'settings.view',
        ];


        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }


        // Roles
        
        $superadmin = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $staff = Role::firstOrCreate([
            'name' => 'staff',
            'guard_name' => 'web',
        ]);

        //Admin Permissions
        $admin->syncPermissions(
            Permission::where('guard_name', 'web')->get()
        );

        //Staff Permissions
        $staff->syncPermissions([
            'users.view',
            'transaksi.view',
            'transaksi.create',
            'pembayaran.view',
            'pembayaran.create',
            'laporan.view',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Superadmin
        |--------------------------------------------------------------------------
        |
        | Superadmin sengaja TIDAK diberi permission.
        |
        | Superadmin akan menggunakan Gate::before()
        | untuk bypass seluruh permission.
        |
        */

        $superadmin->syncPermissions([]);
    }
}