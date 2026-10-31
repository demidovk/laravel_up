<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\authController;
use App\Http\Controllers\userController;
use App\Http\Controllers\adminController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [authController::class, 'showRegister'])->name('show.register');
Route::post('/register', [authController::class, 'storeRegister'])->name('store.register');

Route::get('/login', [authController::class, 'showLogin'])->name('show.login');
Route::post('/login', [authController::class, 'storeLogin'])->name('store.login');

Route::get('/user', [userController::class, 'showUser'])->name('show.user')->middleware('auth');
Route::get('/user/create', [userController::class, 'showCreateApplication'])->name('show.createApplication')->middleware('auth');
Route::post('/user/create', [userController::class, 'storeCreateApplication'])->name('store.createApplication')->middleware('auth');

Route::get('/user/update/{id}', [userController::class, 'UpdateApplication'])->name('show.updateApplication')->middleware('auth');
Route::patch('/user/update{id}', [userController::class, 'storeUpdateApp'])->name('store.updateApplication')->middleware('auth');
Route::post('/application/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy')->middleware(['auth']);
// Route::delete('/user/delete/{application}', [userController::class, 'deleteApp'])->name('deleteApp')->middleware('auth');

// Route::get('/profile/', [userController::class, 'storeUpdateApp'])->name('store.updateApplication')->middleware('auth');
// Route::post('/profile/', [userController::class, 'storeUpdateApp'])->name('store.updateApplication')->middleware('auth');

Route::post('/logout', [authController::class, 'showLogout'])->name('logout')->middleware('auth');

Route::get('/admin', [adminController::class, 'showAdmin'])->name('show.admin')->middleware('auth');