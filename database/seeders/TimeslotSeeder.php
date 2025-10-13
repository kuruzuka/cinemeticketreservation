<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Timeslot;
use DateTime;
use DateInterval;

class TimeslotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $timeslots = [
            ["start_time" => "08:00", "end_time" => "09:00"], 
            ["start_time" => "09:00", "end_time" => "10:30"],    
            ["start_time" => "10:30", "end_time" => "12:30"],    
            ["start_time" => "12:30", "end_time" => "13:30"],   
            ["start_time" => "13:30", "end_time" => "15:00"],    
            ["start_time" => "15:00", "end_time" => "17:00"],    
            ["start_time" => "17:00", "end_time" => "18:00"],    
            ["start_time" => "18:00", "end_time" => "19:30"],    
            ["start_time" => "19:30", "end_time" => "21:30"],    
            ["start_time" => "21:30", "end_time" => "23:30"],    
        ];

        foreach ($timeslots as $timeslot) {
            Timeslot::create([
                "start_time" => $timeslot["start_time"],
                "end_time" => $timeslot["end_time"],
            ]);
        }
        
    }
}
