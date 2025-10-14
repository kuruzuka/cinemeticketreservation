<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardRouter;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('bookings',[BookingController::class, 'index'])->name('bookings');
    Route::post('bookings',[BookingController::class, 'store'])->name('bookings.store');
    Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    Route::get('movies', [MovieController::class, 'index'])->name('movies');
    Route::get('movies/{movie}/edit', [MovieController::class, 'edit'])->name('movie.edit');
    Route::put('movies/{movie}', [MovieController::class, 'update'])->name('movie.update');
    Route::delete('movies/{movie}', [MovieController::class, 'destroy'])->name('movie.destroy');

    Route::get('dashboard', [DashboardRouter::class, 'index'])->name('dashboard');

    Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules');
    Route::get('schedules/{schedule}/book', [ScheduleController::class, 'book'])->name('schedules.book');
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
