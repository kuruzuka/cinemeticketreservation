<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory;

    protected $fillable = [
        "movie_id",
        "cinema_id",
        "city_id",
        "show_date",
        "timeslot_id",
    ] ;

    // Schedule belongs to a movie
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    // Schedule belongs to a cinema
    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    // Schedule belongs to a city
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Schedule belongs to a timeslot
    public function timeslot()
    {
        return $this->belongsTo(Timeslot::class);
    }

    // Schedule can have many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // All customers for this schedule
    public function customers()
    {
        return $this->hasManyThrough(Customer::class, Booking::class)
                    ->distinct();
    }

    // All seats booked in this schedule
    public function seats()
    {
        return $this->hasManyThrough(Seat::class, Booking::class)
                    ->distinct();
    }
}
