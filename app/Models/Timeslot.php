<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    /** @use HasFactory<\Database\Factories\TimeslotFactory> */
    use HasFactory;

    protected $fillable = [
        "start_time",
        "end_time",
    ] ;

    // A timeslot can belong to many schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // All movies playing in this timeslot
    public function movies()
    {
        return $this->hasManyThrough(Movie::class, Schedule::class);
    }

    // All bookings during this timeslot
    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Schedule::class);
    }
}
