<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return 'Strona logowania (w przygotowaniu)';
})->name('login');

Route::get('/register', function () {
    return 'Strona rejestracji (w przygotowaniu)';
})->name('register');

