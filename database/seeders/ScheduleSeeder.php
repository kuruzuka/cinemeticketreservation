<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $schedules = collect();

        for ($city = 1; $city <= 500; $city++) {
            for ($cinema = 1; $cinema <= 5; $cinema++) {
                for ($slot = 1; $slot <= 10; $slot++) {
                    $schedules->push(Schedule::make([
                        'movie_id' => rand(1, 10000),
                        'cinema_id' => $cinema,
                        'city_id' => $city,
                        'timeslot_id' => $slot,
                    ])->getAttributes());
                }
            }
        }

        Schedule::insert($schedules->toArray());
    }
}
