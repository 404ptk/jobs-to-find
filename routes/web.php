<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobOfferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicationController;
use App\Models\Category;
use App\Models\Location;

Route::get('/', function () {
    $categories = Category::whereHas('jobOffers', function ($q) {
        $q->where('is_active', true);
    })->orderBy('name')->get();

    $countries = Location::whereHas('jobOffers', function ($q) {
        $q->where('is_active', true);
    })->select('country')->distinct()->orderBy('country')->pluck('country');

    $cities = Location::whereHas('jobOffers', function ($q) {
        $q->where('is_active', true);
    })->select('city')->distinct()->orderBy('city')->pluck('city');

    $employmentTypes = \App\Models\JobOffer::where('is_active', true)
        ->select('employment_type')
        ->distinct()
        ->orderBy('employment_type')
        ->pluck('employment_type');

    $stats = [
        'activeJobs' => \App\Models\JobOffer::where('is_active', true)->where('is_approved', true)->count(),
        'companies' => \App\Models\JobOffer::where('is_active', true)->where('is_approved', true)->distinct('company_name')->count('company_name'),
        'categories' => Category::whereHas('jobOffers', function ($q) {
            $q->where('is_active', true)->where('is_approved', true);
        })->count(),
    ];

    return view('public.home', compact('categories', 'countries', 'cities', 'employmentTypes', 'stats'));
});

Route::get('/search', [JobOfferController::class, 'search'])->name('search');

Route::get('/job/{id}', [JobOfferController::class, 'show'])->name('job.show');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::get('/tos', function () {
    return view('public.tos');
})->name('tos');

Route::get('/privacy', function () {
    return view('public.privacy');
})->name('privacy');

Route::get('/profile', function () {
    $availableSkills = \App\Models\Skill::orderBy('name')->get();
    return view('auth.profile', [
        'user' => Auth::user(),
        'availableSkills' => $availableSkills
    ]);
})->middleware('auth')->name('profile');

Route::get('/profile/edit', function () {
    return view('auth.edit-profile');
})->middleware('auth')->name('profile.edit');

Route::put('/profile/update', [AuthController::class, 'updateProfile'])
    ->middleware('auth')
    ->name('profile.update');

Route::get('/my-offers', [JobOfferController::class, 'myOffers'])
    ->middleware('auth')
    ->name('my-offers');

Route::get('/offer/create', [JobOfferController::class, 'create'])
    ->middleware('auth')
    ->name('offer.create');

Route::post('/offer/store', [JobOfferController::class, 'store'])
    ->middleware('auth')
    ->name('offer.store');

Route::get('/offer/{id}/edit', [JobOfferController::class, 'edit'])
    ->middleware('auth')
    ->name('offer.edit');

Route::put('/offer/{id}/update', [JobOfferController::class, 'update'])
    ->middleware('auth')
    ->name('offer.update');

Route::delete('/offer/{id}/delete', [JobOfferController::class, 'destroy'])
    ->middleware('auth')
    ->name('offer.delete');

Route::get('/favorites', function () {
    return view('job-seeker.favorites');
})->middleware('auth')->name('favorites');

Route::get('/my-applications', [ApplicationController::class, 'myApplications'])
    ->middleware('auth')
    ->name('my-applications');

Route::post('/job/{id}/apply', [ApplicationController::class, 'apply'])
    ->middleware('auth')
    ->name('job-offers.apply');

Route::get('/offer/{id}/applications', [ApplicationController::class, 'offerApplications'])
    ->middleware('auth')
    ->name('offer.applications');

Route::get('/application/{id}/download-cv', [ApplicationController::class, 'downloadCv'])
    ->middleware('auth')
    ->name('application.download-cv');

Route::get('/admin/accept-offers', [App\Http\Controllers\AdminController::class, 'acceptOffers'])
    ->middleware('auth')
    ->name('admin.accept-offers');

Route::post('/admin/approve-offer/{id}', [App\Http\Controllers\AdminController::class, 'approveOffer'])
    ->middleware('auth')
    ->name('admin.approve-offer');

Route::post('/admin/reject-offer/{id}', [App\Http\Controllers\AdminController::class, 'rejectOffer'])
    ->middleware('auth')
    ->name('admin.reject-offer');

Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'users'])
    ->middleware('auth')
    ->name('admin.users');

Route::get('/admin/offers', [App\Http\Controllers\AdminController::class, 'offers'])
    ->middleware('auth')
    ->name('admin.offers');

Route::get('/admin/offer/{id}/partial', [\App\Http\Controllers\AdminController::class, 'offerPartial'])->name('admin.offer.partial');

Route::get('/admin/user/{id}/partial', [\App\Http\Controllers\AdminController::class, 'userPartial'])->name('admin.user.partial');

Route::get('/profile/{username}', [UserController::class, 'show'])
    ->middleware('auth')
    ->name('user.show');