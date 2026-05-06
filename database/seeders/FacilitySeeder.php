<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            [
                'room_name' => 'Activity Center',
                'building' => 'Main Building',
                'capacity' => 200,
                'is_borrowable' => true,
                'current_occupancy' => 0,
            ],
            [
                'room_name' => 'Gym',
                'building' => 'Sports Complex',
                'capacity' => 100,
                'is_borrowable' => false,
                'current_occupancy' => 0,
            ],
            [
                'room_name' => 'Library',
                'building' => 'Academic Building',
                'capacity' => 150,
                'is_borrowable' => false,
                'current_occupancy' => 0,
            ],
            [
                'room_name' => 'Registrar',
                'building' => 'Admin Building',
                'capacity' => 50,
                'is_borrowable' => false,
                'current_occupancy' => 0,
            ],
            [
                'room_name' => 'BAO',
                'building' => 'Admin Building',
                'capacity' => 30,
                'is_borrowable' => false,
                'current_occupancy' => 0,
            ],
            [
                'room_name' => 'Canteen',
                'building' => 'Cafeteria',
                'capacity' => 80,
                'is_borrowable' => false,
                'current_occupancy' => 0,
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}
