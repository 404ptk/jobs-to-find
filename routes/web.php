<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobOfferController;
use App\Models\Category;
use App\Models\Location;

Route::get('/', function () {
    $categories = Category::whereHas('jobOffers', function($q) {
        $q->where('is_active', true);
    })->orderBy('name')->get();
    
    $countries = Location::whereHas('jobOffers', function($q) {
        $q->where('is_active', true);
    })->select('country')->distinct()->orderBy('country')->pluck('country');
    
    $cities = Location::whereHas('jobOffers', function($q) {
        $q->where('is_active', true);
    })->select('city')->distinct()->orderBy('city')->pluck('city');
    
    $employmentTypes = \App\Models\JobOffer::where('is_active', true)
        ->select('employment_type')
        ->distinct()
        ->orderBy('employment_type')
        ->pluck('employment_type');
    
    $stats = [
        'activeJobs' => \App\Models\JobOffer::where('is_active', true)->count(),
        'companies' => \App\Models\JobOffer::where('is_active', true)->distinct('company_name')->count('company_name'),
        'categories' => Category::whereHas('jobOffers', function($q) {
            $q->where('is_active', true);
        })->count(),
    ];
    
    return view('home', compact('categories', 'countries', 'cities', 'employmentTypes', 'stats'));
});

Route::get('/search', [JobOfferController::class, 'search'])->name('search');

Route::get('/job/{id}', [JobOfferController::class, 'show'])->name('job.show');

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

Route::get('/offer/create', [JobOfferController::class, 'create'])
    ->middleware('auth')
    ->name('offer.create');

Route::post('/offer/store', [JobOfferController::class, 'store'])
    ->middleware('auth')
    ->name('offer.store');

Route::get('/my-applications', [JobOfferController::class, 'myApplications'])
    ->middleware('auth')
    ->name('my-applications');