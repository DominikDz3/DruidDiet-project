<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DietController;
use App\Http\Controllers\UserOrdersController;
use App\Http\Controllers\CateringController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\AdminUsersController as AdminUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserProfileController;

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


//Trasy koszyka
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{cartItemId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Trasy dostępne tylko po zalogowaniu
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserProfileController::class, 'edit'])->name('user.dashboard');
    Route::get('/user/orders', [UserOrdersController::class, 'index'])->name('user.orders.index');
    Route::put('/user/profile/update', [UserProfileController::class, 'update'])->name('user.profile.update');

    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

        Route::get('/dashboard', function () { 
            return view('admin.dashboard');
        })->name('dashboard');


        // CRUD dla Zamówień
        Route::resource('orders', AdminOrderController::class)->except([
        ]);

        // CRUD dla Użytkowników
        Route::resource('users', AdminUserController::class);

    });
});
