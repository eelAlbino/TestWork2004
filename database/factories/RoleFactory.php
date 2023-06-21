<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'code' => $this->faker->unique()->word,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Role $role) {
            $permissions = Permission::inRandomOrder()->limit(rand(1, 5))->get();
            $role->permissions()->attach($permissions);
        });
    }
}
