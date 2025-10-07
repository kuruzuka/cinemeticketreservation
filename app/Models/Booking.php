<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    protected $fillable = [
        "customer_id",
        "movie_id",
        "cinema_id",
        "seat",
        "quantity",
        "agent",
        "total_price",
    ] ;

    public function customers() {
        return $this->hasMany(Customer::class);
    }

    public function movies() {
        return $this->hasMany(Movie::class);
    }

    public function cinemas() {
        return $this->hasMany(Cinema::class);
    }

    public function seats() {
        return $this->hasMany(Seat::class);
    }
    
}
