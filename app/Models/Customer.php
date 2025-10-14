<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = [
        "name"
    ] ;

    // A customer can have many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // All movies a customer has booked
    public function movies()
    {
        return $this->hasManyThrough(Movie::class, Booking::class, 'customer_id', 'id', 'id', 'schedule_id')
                    ->distinct();
    }

    // All cinemas where the customer has booked
    public function cinemas()
    {
        return $this->hasManyThrough(Cinema::class, Booking::class, 'customer_id', 'id', 'id', 'schedule_id')
                    ->distinct();
    }

    // All cities where the customer has booked
    public function cities()
    {
        return $this->hasManyThrough(City::class, Booking::class, 'customer_id', 'id', 'id', 'schedule_id')
                    ->distinct();
    }
}
