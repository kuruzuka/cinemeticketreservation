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

    public function cinemas() {
        return $this->belongsTo(Cinema::class);
    }
}
