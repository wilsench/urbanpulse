<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEnvironmentalController;
use App\Http\Controllers\Admin\AdminLocationController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CityDashboardController;
use App\Http\Controllers\DataSourcesController;
use App\Http\Controllers\EcoActionController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PersonalImpactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/cities/select', [CityController::class, 'select'])->name('cities.select');

Route::get('/city', [CityDashboardController::class, 'index'])->name('city.dashboard');
Route::get('/map', [MapController::class, 'index'])->name('map');
Route::get('/locations/{location:slug}', [LocationController::class, 'show'])->name('locations.show');

Route::get('/recommend', [RecommendationController::class, 'index'])->name('recommend.index');
Route::post('/recommend', [RecommendationController::class, 'process'])->name('recommend.process');

Route::get('/assistant', [AssistantController::class, 'index'])->name('assistant.index');
Route::post('/assistant/query', [AssistantController::class, 'query'])->name('assistant.query');

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/data-sources', [DataSourcesController::class, 'index'])->name('data-sources');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/actions', [EcoActionController::class, 'index'])->name('actions.index');
    Route::post('/actions', [EcoActionController::class, 'store'])->name('actions.store');
    Route::get('/impact', [PersonalImpactController::class, 'index'])->name('impact.index');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/locations/sync', [AdminLocationController::class, 'syncLocations'])->name('locations.sync');
    Route::get('/locations/sync-status', [AdminLocationController::class, 'syncStatus'])->name('locations.sync-status');
    Route::resource('locations', AdminLocationController::class);
    Route::get('/environment', [AdminEnvironmentalController::class, 'index'])->name('environment.index');
    Route::post('/environment/sync', [AdminEnvironmentalController::class, 'syncNow'])->name('environment.sync');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
});
