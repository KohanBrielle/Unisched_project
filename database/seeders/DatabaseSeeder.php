<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = now();

        DB::table('users')->updateOrInsert(
            ['email' => 'admin@unilsched.test'],
            [
                'name' => 'Master Admin',
                'student_id' => '00000000',
                'email_verified_at' => $now,
                'password' => Hash::make('Admin1234!'),
                'is_admin' => true,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'student_id' => '2024-00001',
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'is_admin' => false,
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->call(FacilitySeeder::class);
    }
}
