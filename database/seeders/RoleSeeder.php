<?php

namespace Database\Seeders;

use App\Modules\Auth\Infrastructure\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use upsert to avoid race conditions when multiple containers run seeders concurrently.
        $now = now();
        Role::upsert([
            ['id' => (string) Str::uuid(), 'title' => 'ADMIN', 'description' => 'Administrator role', 'created_at' => $now, 'updated_at' => $now],
            ['id' => (string) Str::uuid(), 'title' => 'CLIENT', 'description' => 'Client role', 'created_at' => $now, 'updated_at' => $now],
        ], ['title'], ['description', 'updated_at']);
    }
}
