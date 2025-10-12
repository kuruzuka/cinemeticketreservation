<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardRouter;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('bookings',[BookingController::class, 'index'])->name('bookings');
    Route::post('bookings',[BookingController::class, 'store'])->name('booking.store');
    Route::get('movies/{movie}/book', [MovieController::class, 'book'])->name('movie.book');
    Route::get('movies', [MovieController::class, 'index'])->name('movies');
    Route::get('movies/{movie}/edit', [MovieController::class, 'edit'])->name('movie.edit');
    Route::delete('movies/{movie}', [MovieController::class, 'destory'])->name('movie.destroy');
    Route::get('dashboard', [DashboardRouter::class, 'index'])->name('dashboard');
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
