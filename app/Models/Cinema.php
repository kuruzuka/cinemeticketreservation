<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    /** @use HasFactory<\Database\Factories\CinemaFactory> */
    use HasFactory;
    protected $fillable = [
        "cinema_number",
    ];

    public function movies() {
        return $this->hasMany(Movie::class);
    }

    public function seats() {
        return $this->hasMany(Seat::class);
    }
}
