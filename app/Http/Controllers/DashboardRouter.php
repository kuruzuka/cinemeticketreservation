<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Movie;
use App\Models\Seat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardRouter extends Controller
{
    public function index() {
        $movies = Movie::all();
        $bookings = Booking::all();
        $seats = Seat::all();
        return Inertia::render("Dashboard", compact("movies", "bookings", "seats"));
    }
}
