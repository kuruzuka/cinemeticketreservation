<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $genres = [
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Horror',
            'Science Fiction',
            'Fantasy',
            'Thriller',
            'Romance',
            'Mystery',
            'Animation',
            'Documentary',
            'Musical',
            'Crime',
            'Historical',
        ];

        foreach ($genres as $genre) {
            Genre::create(["name" => $genre]);
        }

    }
}
