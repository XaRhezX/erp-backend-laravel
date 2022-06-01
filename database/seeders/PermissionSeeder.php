<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actions = ['index', 'create', 'view', 'edit', 'delete'];
        $permissions = [
            'root.access', 'root.user', 'root.permission', 'root.role'
        ];
        foreach ($permissions as $permission) {
            foreach ($actions as $action) {
                $data = ['name' => $permission . '-' . $action];
                Permission::firstOrCreate($data);
            }
        }

        $roles = [
            'Super User'
        ];
        foreach ($roles as $role) {
            $data = ['name' => $role];
            Role::firstOrCreate($data);
        }

        $role = Role::findByName('Super User');
        $permission = Permission::select('name')->get()->toArray();
        $role->syncPermissions($permission);

        foreach (User::get() as $user) {
            $user->syncRoles(Role::get()->pluck('name')->toArray());
        }
    }
}
