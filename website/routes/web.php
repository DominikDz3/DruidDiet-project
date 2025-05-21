<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DietController;
use App\Http\Controllers\UserOrdersController;
use App\Http\Controllers\CateringController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController; // <--- DODAJ TEN IMPORT

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
    Route::get('/caterings', [CateringController::class, 'index'])->name('caterings.index');
});

// Ścieżka do wyświetlania listy diet
Route::get('/diety', [DietController::class, 'index'])->name('diets.index');

// Trasy dostępne tylko po zalogowaniu
Route::middleware(['auth'])->group(function () {
    // Panel użytkownika
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    Route::get('/user/orders', [UserOrdersController::class, 'index'])->name('user.orders.index');

    // Możesz tu później dodać trasę do szczegółów zamówienia
    // Route::get('/user/orders/{order}', [UserOrdersController::class, 'show'])->name('user.orders.show');

    
    Route::middleware(['role:admin']) 
        ->prefix('admin')          
        ->name('admin.')          
        ->group(function () {
        
        Route::get('/dashboard', function () {
            
            return view('admin.dashboard');
        })->name('dashboard'); 
        
        
        // CRUD dla Zamówień
        Route::resource('orders', AdminOrderController::class)->except([
            // 'create', 'store' // Odkomentuj, jeśli admin nie ma mieć możliwości tworzenia nowych zamówień
        ]);
        
    });
});