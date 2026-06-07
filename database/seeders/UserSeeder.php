<?php

namespace Database\Seeders;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Auth\Infrastructure\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('title', 'ADMIN')->first();
        if (! $adminRole) {
            $this->command?->info('ADMIN role not found, skipping admin user seeder.');
            return;
        }

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'id' => (string) Str::uuid(),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'birth_date' => '1990-01-01',
                'role_id' => $adminRole->id,
                'password' => Hash::make('password123'),
                'address' => 'Admin Address',
                'status' => 'ACTIVE',
            ]
        );
    }
}
