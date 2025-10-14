<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MovieController extends Controller
{
    //
    public function index() {
        $movies = Movie::with([
            'genre',
        ])->inRandomOrder()->get();
        return Inertia::render("Movie/Movies", compact("movies"));
    }

    public function edit(Movie $movie) {
        $movie->load("genre");
        $genres = Genre::all();
        return Inertia::render("Movie/Edit", compact("movie", "genres"));
    }

    public function update(Request $request, Movie $movie) {
        $request->validate([
            "title" => "required|min:10|max:255",
            "author" => "required|min:10|max:255",
            "director"=> "required|min:10|max:255",
        ], [
            "title.required" => "Title cannot be blank.",
            "title.min" => "Title must be at least 10 characters.",
            "author.required" => "Author cannot be blank.",
            "author.min" => "Author name must be at least 10 characters.",
            "director.required" => "Director cannot be blank.",
            "director.min" => "Director name must be at least 10 characters.",
        ]);

        $movie->update([
            "title"=> $request->input("title"),
            "author"=> $request->input("author"),
            "director"=> $request->input("director"),
            "genre_id"=> $request->input("genre_id"),

        ]);

        return redirect()->route("movies")->with("message","Movie updated successfully.");
    }

    public function destroy(Movie $movie) {
        $movie->delete();
        return redirect()->route("movies")->with("message","Deleted Successfully!");
    }

}
