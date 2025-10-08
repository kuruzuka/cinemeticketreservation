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
        "customer_fname",
        "customer_midname",
        "customer_lname",
        "movie",
        "cinema",
        "seat",
        "price"
    ];
    
}
