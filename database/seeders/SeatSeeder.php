<?php

namespace Database\Seeders;

use App\Models\Seat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $letters = range('A', 'F');
        $numbers = range(1, 20);

        $seats = [];

        foreach ($letters as $letter) {
            foreach ($numbers as $number) {
                $seats[] = $letter . $number;
            }
        }

        foreach ($seats as $seat) {
            Seat::create(["seat_number" => $seat]);
        }        

    }
}
