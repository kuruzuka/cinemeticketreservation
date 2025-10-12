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
        "genre"
    ] ;

    public function customers() {
        return $this->hasMany(Customer::class);
    }
}
