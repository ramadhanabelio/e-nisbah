<?php

use App\Http\Controllers\ArsipSuratController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('guest')->group(function () {

    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');

    Route::post('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/change-password', [ProfileController::class, 'changePassword'])
        ->name('profile.change-password');

    Route::post('/change-password', [ProfileController::class, 'updatePassword'])
        ->name('profile.update-password');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // CS / CUSTOMER SERVICE
    Route::resource('surat-keluar', SuratKeluarController::class);

    // PINSI PILNAS, PINBAG OPERASIONAL, PINCAB
    Route::resource('surat-masuk', SuratMasukController::class);
    Route::post('/surat-masuk/{surat}/approve', [SuratMasukController::class, 'approve'])->name('surat-masuk.approve');
    Route::post('/surat-masuk/{surat}/reject', [SuratMasukController::class, 'reject'])->name('surat-masuk.reject');

    // ADMIN PUSAT
    Route::post('/surat-masuk/{surat}/direct', [SuratMasukController::class, 'direct'])->name('surat-masuk.direct');

    Route::get('/arsip-surat', [ArsipSuratController::class, 'index'])->name('arsip.index');
    Route::get('/arsip-surat/download/{id}', [ArsipSuratController::class, 'download'])->name('arsip.download');
});
