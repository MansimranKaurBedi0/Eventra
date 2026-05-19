<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/events/create', [App\Http\Controllers\EventController::class, 'create'])->name('events.create');
    Route::post('/events', [App\Http\Controllers\EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [App\Http\Controllers\EventController::class, 'show'])->name('events.show');
    
    // Vendor Search
    Route::get('/vendors', [App\Http\Controllers\VendorProfileController::class, 'index'])->name('vendors.index');
    Route::post('/vendors/profile', [App\Http\Controllers\VendorProfileController::class, 'storeOrUpdate'])->name('vendors.profile.store');

    // Guests
    Route::post('/events/{event}/guests', [App\Http\Controllers\GuestController::class, 'store'])->name('guests.store');
    Route::delete('/guests/{guest}', [App\Http\Controllers\GuestController::class, 'destroy'])->name('guests.destroy');

    // Expenses
    Route::post('/events/{event}/expenses', [App\Http\Controllers\ExpenseController::class, 'store'])->name('expenses.store');
    Route::delete('/expenses/{expense}', [App\Http\Controllers\ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Bookings
    Route::post('/bookings', [App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/{booking}', [App\Http\Controllers\BookingController::class, 'update'])->name('bookings.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Temporary route to see registered users
Route::get('/users', function () {
    return App\Models\User::all();
});

require __DIR__.'/auth.php';
