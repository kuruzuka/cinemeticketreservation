<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MovieController extends Controller
{
    //
    public function index() {
        $movies = Movie::all();
        return Inertia::render("Movie/Movies", compact('movies'));
    }

    public function book(Movie $movie) {
        return Inertia::render('Movie/Book', compact('movie'));
    }

    public function edit(Movie $movie) {
        return Inertia::render('Movie/Edit', compact('movie'));
    }

    public function destory(Movie $movie) {
        $movie->delete();
        return redirect()->route('movies');
    }

}
