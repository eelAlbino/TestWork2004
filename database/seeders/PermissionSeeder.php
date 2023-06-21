<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Посты
            ['name' => 'Создание постов', 'code' => 'post_create'],
            ['name' => 'Обновление постов', 'code' => 'post_update'],
            ['name' => 'Удаление постов', 'code' => 'post_delete'],
            ['name' => 'Просмотр постов', 'code' => 'post_view'],
            
            // Роли
            ['name' => 'Создание ролей', 'code' => 'role_create'],
            ['name' => 'Обновление ролей', 'code' => 'role_update'],
            ['name' => 'Удаление ролей', 'code' => 'role_delete'],
            ['name' => 'Просмотр ролей', 'code' => 'role_view'],
        ];
        
        foreach ($permissions as $permissionData) {
            Permission::create($permissionData);
        }
    }
}
