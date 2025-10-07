<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('bookings',[BookingController::class, 'index'])->name('bookings');
    Route::get('movies', [MovieController::class, 'index'])->name('movies');
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
