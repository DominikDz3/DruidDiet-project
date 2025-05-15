<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DietController;
use App\Http\Controllers\UserOrdersController;




// Strona główna
Route::get('/', function () {
    return view('main');
})->name('home');

// Trasy autoryzacyjne
Route::controller(AuthController::class)->group(function () {
    Route::get('/auth/login', 'login')->name('login');
    Route::post('/auth/login', 'authenticate')->name('login.authenticate');
    Route::post('/auth/logout', 'logout')->name('logout');
    Route::get('/auth/register', 'register')->name('register');
    Route::post('/auth/register', 'store')->name('register.store');
});

// Ścieżka do wyświetlania listy diet
Route::get('/diety', [DietController::class, 'index'])->name('diets.index');

// Trasy dostępne tylko po zalogowaniu
Route::middleware(['auth'])->group(function () {
    // Panel użytkownika
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    // Historia zamówień użytkownika
    Route::get('/user/orders', [UserOrdersController::class, 'index'])->name('user.orders.index');

    // Możesz tu później dodać trasę do szczegółów zamówienia
    // Route::get('/user/orders/{order}', [UserOrdersController::class, 'show'])->name('user.orders.show');

    // Panel admina — z dodatkowym sprawdzeniem roli
    Route::get('/admin/dashboard', function () {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');
});