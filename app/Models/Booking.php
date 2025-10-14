<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    protected $fillable = [
        "customer_name",
        "schedule_id",
        "seat_id",
    ];

    // A booking belongs to a customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // A booking belongs to a schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // A booking belongs to a seat
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    // Quick access: get movie directly through schedule
    public function movie()
    {
        return $this->hasOneThrough(Movie::class, Schedule::class, 'id', 'id', 'schedule_id', 'movie_id');
    }

    // Quick access: get cinema directly through schedule
    public function cinema()
    {
        return $this->hasOneThrough(Cinema::class, Schedule::class, 'id', 'id', 'schedule_id', 'cinema_id');
    }

    // Quick access: get city directly through schedule
    public function city()
    {
        return $this->hasOneThrough(City::class, Schedule::class, 'id', 'id', 'schedule_id', 'city_id');
    }
    
}
