<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Создание пользователя Админ
        $user = User::create([
            'name' => 'Админ',
            'email' => 'admin@test.ru',
            'password' => Hash::make('admin')
        ]);
        // Назначение роли admin пользователю
        if ($role = Role::where('code', 'admin')->first()) {
            $user->roles()->attach($role);
        }

        // Создание пользователя Наблюдатель
        $user = User::create([
            'name' => 'Наблюдатель',
            'email' => 'observer@test.ru',
            'password' => Hash::make('observer')
        ]);
        // Назначение роли observer пользователю
        if ($role = Role::where('code', 'observer')->first()) {
            $user->roles()->attach($role);
        }
    }
}
