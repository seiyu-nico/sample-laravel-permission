<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/select-company', [\App\Http\Controllers\SelectCompanyController::class, 'show'])->middleware(['auth', 'verified'])->name('select-company');
Route::post('/select-company', [\App\Http\Controllers\SelectCompanyController::class, 'select'])->middleware(['auth', 'verified'])->name('select-company');

Route::middleware([\App\Http\Middleware\SetPermissionTeam::class])->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
