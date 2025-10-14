<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    /** @use HasFactory<\Database\Factories\CinemaFactory> */
    use HasFactory;
    protected $fillable = [
        "name",
    ];

    // A cinema can have many schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // All movies showing in this cinema
    public function movies()
    {
        return $this->hasManyThrough(Movie::class, Schedule::class);
    }

    // All bookings made in this cinema
    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Schedule::class);
    }

    // All customers who booked at this cinema
    public function customers()
    {
        return $this->hasManyThrough(Customer::class, Booking::class, 'schedule_id', 'id', 'id', 'customer_id')
                    ->distinct();
    }
}
