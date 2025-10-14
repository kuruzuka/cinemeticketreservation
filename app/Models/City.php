<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /** @use HasFactory<\Database\Factories\CityFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
    ] ;

    // A city can have many schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // All movies playing in this city
    public function movies()
    {
        return $this->hasManyThrough(Movie::class, Schedule::class);
    }

    // All bookings in this city (through schedules)
    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Schedule::class);
    }

    // All customers who booked in this city
    public function customers()
    {
        return $this->hasManyThrough(Customer::class, Booking::class, 'schedule_id', 'id', 'id', 'customer_id')
                    ->distinct();
    }
}
