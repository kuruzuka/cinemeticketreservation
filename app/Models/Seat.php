<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    /** @use HasFactory<\Database\Factories\SeatFactory> */
    use HasFactory;
    protected $fillable = [
        "seat_number",
    ] ;

    // A seat can be booked many times (across different schedules)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Customers who have ever used this seat (across all schedules)
    public function customers()
    {
        return $this->hasManyThrough(Customer::class, Booking::class)
                    ->distinct();
    }
}
