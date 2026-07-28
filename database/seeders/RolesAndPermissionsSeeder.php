<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\Permissions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('permission:cache-reset');

        foreach (Permissions::ALL as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // admin: everything
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permissions::ALL);

        // manager: everything except managing users
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->syncPermissions(array_diff(Permissions::ALL, ['manage users']));

        // staff: access the panel + manage the catalog only
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->syncPermissions(['access admin', 'manage products']);

        // Backfill: existing admins (is_admin) become full admins.
        User::where('is_admin', true)->get()->each(function (User $user) use ($admin) {
            if (! $user->hasRole($admin)) {
                $user->assignRole($admin);
            }
        });

        Artisan::call('permission:cache-reset');
    }
}
