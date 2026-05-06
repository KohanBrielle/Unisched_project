<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a master admin account
        User::create([
            'name' => 'Master Admin',
            'student_id' => '00000000',
            'email' => 'admin@unilsched.test',
            'password' => Hash::make('Admin1234!'),
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'student_id' => '2024-00001',
            'email' => 'test@example.com',
        ]);

        $this->call(FacilitySeeder::class);
    }
}
