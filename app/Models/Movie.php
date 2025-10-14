<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    /** @use HasFactory<\Database\Factories\MovieFactory> */
    use HasFactory;
    protected $fillable = [
        "title",
        "author",
        "director",
        "genre_id"
    ] ;

    // A movie belongs to a genre
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    // A movie can appear in many schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // All bookings for this movie (via schedules)
    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Schedule::class);
    }

    // All customers who booked this movie
    public function customers()
    {
        return $this->hasManyThrough(Customer::class, Booking::class, 'schedule_id', 'id', 'id', 'customer_id')
                    ->distinct();
    }
}
