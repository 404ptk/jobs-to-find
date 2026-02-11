<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobOfferController;

Route::get('/', function () {
    return view('home');
});

Route::get('/search', [JobOfferController::class, 'search'])->name('search');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::get('/tos', function () {
    return view('tos');
})->name('tos');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');

Route::get('/my-offers', [JobOfferController::class, 'myOffers'])
    ->middleware('auth')
    ->name('my-offers');