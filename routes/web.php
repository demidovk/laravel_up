<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\authController;
use App\Http\Controllers\userController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\publicController;
use App\Http\Controllers\screeningController;

Route::get('/', [publicController::class, 'showHome'])->name('show.home');
Route::get('/catalog', [publicController::class, 'showCatalog'])->name('show.catalog');
Route::get('/movie/{id}', [publicController::class, 'showMovie'])->name('show.movie');
Route::get('/screening/{id}', [screeningController::class, 'showScreening'])->name('show.screening');

Route::middleware('guest')->group(function () {
    Route::get('/register', [authController::class, 'showRegister'])->name('show.register');
    Route::post('/register', [authController::class, 'storeRegister'])->name('store.register');

    Route::get('/login', [authController::class, 'showLogin'])->name('login');
    Route::post('/login', [authController::class, 'storeLogin'])->name('store.login');
});

Route::post('/logout', [authController::class, 'showLogout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/user', [userController::class, 'showUser'])->name('show.user');
    Route::get('/user/create', [userController::class, 'showCreateApplication'])->name('show.createApplication');
    Route::post('/user/create', [userController::class, 'storeCreateApplication'])->name('store.createApplication');

    Route::get('/user/update/{id}', [userController::class, 'UpdateApplication'])->name('show.updateApplication');
    Route::patch('/user/update/{id}', [userController::class, 'storeUpdateApplication'])->name('store.updateApplication');
    Route::post('/application/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
    
    Route::post('/screening/{id}/book', [screeningController::class, 'storeBooking'])->name('store.booking');
    Route::get('/checkout/{tickets}', [screeningController::class, 'showCheckout'])->name('show.checkout');
    Route::post('/checkout', [screeningController::class, 'processPayment'])->name('process.payment');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    Route::get('/', [adminController::class, 'showAdmin'])->name('show.admin');
    
    Route::post('/genres', [adminController::class, 'storeGenre']);
    Route::delete('/genres/{id}', [adminController::class, 'destroyGenre']);
    
    Route::post('/halls', [adminController::class, 'storeHall']);
    Route::delete('/halls/{id}', [adminController::class, 'destroyHall']);
    
    Route::post('/movies', [adminController::class, 'storeMovie']);
    Route::delete('/movies/{id}', [adminController::class, 'destroyMovie']);
    
    Route::post('/screenings', [adminController::class, 'storeScreening']);
    Route::delete('/screenings/{id}', [adminController::class, 'destroyScreening']);
});
