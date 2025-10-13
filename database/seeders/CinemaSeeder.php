<?php

namespace Database\Seeders;

use App\Models\Cinema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CinemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $cinemas = [
            "Cinema 1",
            "Cinema 2",
            "Cinema 3",
            "Cinema 4",
            "Cinema 5",
        ];

        foreach ($cinemas as $cinema) {
            Cinema::create(["name" => $cinema]);  
        }
    }
}
