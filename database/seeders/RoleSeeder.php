<?php

namespace Database\Seeders;

use App\Modules\Auth\Infrastructure\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'ADMIN',
            'description' => 'Administrator role',
        ]);

        Role::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'CLIENT',
            'description' => 'Client role',
        ]);
    }
}
