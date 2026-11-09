<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'login'])->name('login');
Route::post('/login', [PageController::class, 'processLogin'])->name('login.process');
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/jemaat', [PageController::class, 'jemaat'])->name('jemaat');
Route::post('/jemaat', [PageController::class, 'storeJemaat'])->name('jemaat.store');
Route::get('/kehadiran', [PageController::class, 'kehadiran'])->name('kehadiran');
Route::post('/kehadiran', [PageController::class, 'storeAttendance'])->name('kehadiran.store');
Route::get('/remaja', [PageController::class, 'remaja'])->name('remaja');
Route::get('/pemuda', [PageController::class, 'pemuda'])->name('pemuda');
Route::post('/{group}/transaksi', [PageController::class, 'storeGroupTransaction'])
    ->whereIn('group', ['remaja', 'pemuda'])
    ->name('group-cash.store');
Route::get('/kas', [PageController::class, 'kas'])->name('kas');
Route::post('/kas', [PageController::class, 'storeCash'])->name('kas.store');
Route::get('/persembahan', [PageController::class, 'persembahan'])->name('persembahan');
Route::post('/persembahan', [PageController::class, 'storeOffering'])->name('persembahan.store');
Route::get('/ibadah', [PageController::class, 'ibadah'])->name('ibadah');
Route::post('/ibadah', [PageController::class, 'storeService'])->name('ibadah.store');
Route::get('/profile', [PageController::class, 'profile'])->name('profile');
