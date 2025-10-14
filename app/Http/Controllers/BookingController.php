<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    //
    public function index()
    {
        $bookings = Booking::with([
            'schedule.movie',
            'schedule.city',
            'schedule.cinema',
            'schedule.timeslot',
            'seat',
        ])->latest()->get();

        $groupedBookings = $bookings->groupBy(function($booking) {
            return $booking->customer_name . '-' . $booking->schedule_id;
        })->map(function($group) {
            $first = $group->first();
            return [
                'id' => $first->id,
                'customer_name' => $first->customer_name,
                'schedule' => $first->schedule,
                'seats' => $group->pluck('seat.seat_number')->toArray(),
                'seat_ids' => $group->pluck('seat_id')->toArray(),
                'booking_count' => $group->count(),
                'created_at' => $first->created_at,
            ];
        })->values();

        $perPage = 10;
        $page = request()->input('page', 1);
        $paged = $groupedBookings->forPage($page, $perPage);

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $paged,
            $groupedBookings->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return Inertia::render('Booking/Bookings', [
            'bookings' => $paginated,
        ]);
    }


    public function store(Request $request) {
        $data = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'customer_name' => 'required|string|min:2',
            'seat_id' => 'required|array|min:1',
            'seat_id.*' => 'exists:seats,id',
        ], [
            // Custom messages here
            'customer_name.required' => 'Please enter your name',
            'customer_name.min' => 'Name must be at least 2 characters',
            'seat_id.required' => 'Please select at least one seat',
            'seat_id.min' => 'You must select at least one seat',
            'seat_id.*.exists' => 'One or more selected seats are invalid',
            'schedule_id.required' => 'Schedule is required',
            'schedule_id.exists' => 'Invalid schedule selected',
        ]);
        
        foreach ($data['seat_id'] as $seatId) {
            Booking::create([
                'customer_name' => $data['customer_name'],
                'schedule_id' => $data['schedule_id'],
                'seat_id' => $seatId,  // Individual seat ID
            ]);
        }

        return redirect()->route('bookings')->with('message','Booking Successful!');
    }

    public function destroy(Booking $booking)
    {
        // Delete ALL bookings for this customer and schedule
        Booking::where('customer_name', $booking->customer_name)
            ->where('schedule_id', $booking->schedule_id)
            ->delete();

        return redirect()->route('bookings')->with('message', 'Booking deleted successfully.');
    }


}
