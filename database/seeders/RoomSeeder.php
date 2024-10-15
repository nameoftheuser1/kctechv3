<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Room data
        $rooms = [
            ['room_type' => 'Open Cottage', 'room_number' => 'Open Cottage', 'price' => 2000, 'pax' => '10', 'stay_type' => 'day tour'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Kubo - 1', 'price' => 4500, 'pax' => '20', 'stay_type' => 'day tour'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Gazebo', 'price' => 4500, 'pax' => '20', 'stay_type' => 'day tour'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Bamboo 1', 'price' => 4500, 'pax' => '20', 'stay_type' => 'day tour'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Bamboo 2', 'price' => 4500, 'pax' => '20', 'stay_type' => 'day tour'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Bamboo 3', 'price' => 4500, 'pax' => '20', 'stay_type' => 'day tour'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Bamboo 4', 'price' => 4500, 'pax' => '20', 'stay_type' => 'day tour'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Bamboo 5', 'price' => 4500, 'pax' => '20', 'stay_type' => 'day tour'],
            ['room_type' => 'Rooms', 'room_number' => 'Apartelle 1', 'price' => 4000, 'pax' => '8', 'stay_type' => 'day tour'],
            ['room_type' => 'Rooms', 'room_number' => 'Apartelle 2', 'price' => 4000, 'pax' => '8', 'stay_type' => 'day tour'],
            ['room_type' => 'Rooms', 'room_number' => 'Apartelle 3', 'price' => 4000, 'pax' => '8', 'stay_type' => 'day tour'],
            ['room_type' => 'Rooms', 'room_number' => 'Apartelle 4', 'price' => 4500, 'pax' => '12', 'stay_type' => 'day tour'],

            ['room_type' => 'Open Cottage', 'room_number' => 'Open Cottage', 'price' => 2000, 'pax' => '10', 'stay_type' => 'overnight'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Kubo - 1', 'price' => 6500, 'pax' => '20', 'stay_type' => 'overnight'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Gazebo', 'price' => 6500, 'pax' => '20', 'stay_type' => 'overnight'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Bamboo 1', 'price' => 6500, 'pax' => '20', 'stay_type' => 'overnight'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Bamboo 2', 'price' => 6500, 'pax' => '20', 'stay_type' => 'overnight'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Bamboo 3', 'price' => 6500, 'pax' => '20', 'stay_type' => 'overnight'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Bamboo 4', 'price' => 6500, 'pax' => '20', 'stay_type' => 'overnight'],
            ['room_type' => 'Open Cottage', 'room_number' => 'Bamboo 5', 'price' => 6500, 'pax' => '20', 'stay_type' => 'overnight'],
            ['room_type' => 'Rooms', 'room_number' => 'Apartelle 1', 'price' => 7500, 'pax' => '8', 'stay_type' => 'overnight'],
            ['room_type' => 'Rooms', 'room_number' => 'Apartelle 2', 'price' => 7500, 'pax' => '8', 'stay_type' => 'overnight'],
            ['room_type' => 'Rooms', 'room_number' => 'Apartelle 3', 'price' => 7500, 'pax' => '8', 'stay_type' => 'overnight'],
            ['room_type' => 'Rooms', 'room_number' => 'Apartelle 4', 'price' => 9000, 'pax' => '12', 'stay_type' => 'overnight'],
        ];

        for ($i = 5; $i <= 9; $i++) {
            $rooms[] = [
                'room_type' => 'Rooms',
                'room_number' => "Apartelle $i",
                'price' => 4500,
                'pax' => '12',
                'stay_type' => 'day tour',
            ];
        }

        for ($i = 5; $i <= 9; $i++) {
            $rooms[] = [
                'room_type' => 'Rooms',
                'room_number' => "Apartelle $i",
                'price' => 9000,
                'pax' => '12',
                'stay_type' => 'overnight',
            ];
        }

        for ($i = 10; $i <= 14; $i++) {
            $rooms[] = [
                'room_type' => 'Rooms',
                'room_number' => "Apartelle $i",
                'price' => 4000,
                'pax' => '9',
                'stay_type' => 'day tour',
            ];
        }

        for ($i = 10; $i <= 14; $i++) {
            $rooms[] = [
                'room_type' => 'Rooms',
                'room_number' => "Apartelle $i",
                'price' => 8000,
                'pax' => '9',
                'stay_type' => 'overnight',
            ];
        }

        $rooms[] = [
            'room_type' => 'Rooms',
            'room_number' => 'Apartelle 15',
            'price' => 3000,
            'pax' => '3',
            'stay_type' => 'day tour',
        ];

        $rooms[] = [
            'room_type' => 'Rooms',
            'room_number' => 'Apartelle 15',
            'price' => 2000,
            'pax' => '3',
            'stay_type' => 'day tour',
        ];

        $rooms[] = [
            'room_type' => 'Rooms',
            'room_number' => 'Apartelle 15',
            'price' => 3000,
            'pax' => '3',
            'stay_type' => 'overnight',
        ];

        for ($i = 16; $i <= 19; $i++) {
            $rooms[] = [
                'room_type' => 'Rooms',
                'room_number' => "Apartelle $i",
                'price' => 3000,
                'pax' => '5',
                'stay_type' => 'day tour',
            ];
        }
        for ($i = 16; $i <= 19; $i++) {
            $rooms[] = [
                'room_type' => 'Rooms',
                'room_number' => "Apartelle $i",
                'price' => 5000,
                'pax' => '5',
                'stay_type' => 'overnight',
            ];
        }

        // Insert data into the rooms table
        DB::table('rooms')->insert($rooms);
    }
}
