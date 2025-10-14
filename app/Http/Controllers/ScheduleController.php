<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Seat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    //
    public function index() {
        $schedules = Schedule::with([
            'movie.genre',    // movie + its genre
            'cinema',         // cinema info
            'city',           // city info
            'timeslot'        // time info
        ])->get();

        return Inertia::render('Schedule/Schedules', compact('schedules'));
    }

    public function book(Schedule $schedule) {
        $schedule->load([
            'movie.genre',
            'cinema',
            'city',
            'timeslot'
        ]);

        $seats = Seat::all();

        // Get all booked seat IDs for this schedule
        $bookedSeatIds = Booking::where('schedule_id', $schedule->id)
            ->pluck('seat_id')
            ->toArray();

        return Inertia::render('Schedule/Book', compact('schedule', 'seats', 'bookedSeatIds'));

    }
}
