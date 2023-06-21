<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Админ',
                'code' => 'admin'
            ],
            [
                'name' => 'Менеджер',
                'code' => 'manager',
                'allow_permissions' => [
                    'post_create',
                    'post_update',
                    'post_delete',
                    'post_view',
                    'role_view'
                ]
            ],
            [
                'name' => 'Наблюдатель',
                'code' => 'observer',
                'allow_permissions' => [
                    'post_view',
                    'role_view'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            $role = Role::create([
                'name' => $roleData['name'],
                'code' => $roleData['code']
            ]);

            if (!isset($roleData['allow_permissions'])) {
                $permissions = Permission::pluck('id');
            }
            else {
                $permissions = Permission::whereIn('code', $roleData['allow_permissions'])->pluck('id');
            }

            $role->permissions()->attach($permissions);
        }
    }
}
