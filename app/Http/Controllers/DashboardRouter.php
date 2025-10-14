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
        $totalMovies = Movie::count();
        $totalBookings = \DB::table('bookings')
            ->select('customer_name', 'schedule_id')
            ->groupBy('customer_name', 'schedule_id')
            ->get()
            ->count();
        $totalSeatsSold = Booking::distinct('seat_id')->count('seat_id'); // unique seats booked

        return Inertia::render('Dashboard', [
            'totalMovies' => $totalMovies,
            'totalBookings' => $totalBookings,
            'totalSeatsSold' => $totalSeatsSold,
        ]);
    }
}
