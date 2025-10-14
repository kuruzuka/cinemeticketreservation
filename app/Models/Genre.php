<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    /** @use HasFactory<\Database\Factories\GenreFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
    ] ;

    // A genre has many movies
    public function movies()
    {
        return $this->hasMany(Movie::class);
    }

}
