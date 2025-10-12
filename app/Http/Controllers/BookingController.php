<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    //
    public function index() {
        $bookings = Booking::all();
        return Inertia::render('Booking/Bookings', compact('bookings'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'customer_fname' => 'required|string|max:255',
            'customer_midname' => 'required|string|max:255',
            'customer_lname' => 'required|string|max:255',
            'title' => 'required',
            'cinema' => 'required',
            'timeslot' => 'required',
            'seats' => 'required|array|min:1',
            'seats.*' => 'string',
        ]);

        foreach ($data["seats"] as $seat) {
            Seat::create(['seat_number' => $seat]);
        }

        $data['seats'] = implode(', ', $data['seats']);

        Booking::create($data);
        $request->session()->flash('toast.success','Booking added successfully');
        return redirect()->route('bookings');;
    }
}
