<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('guest')->group(function () {

    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // CS / CUSTOMER SERVICE
    Route::resource('surat-keluar', SuratKeluarController::class);

    // PINSI PILNAS, PINBAG OPERASIONAL, PINCAB
    Route::get('/surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
    Route::get('/surat-masuk/{surat}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
    Route::post('/surat-masuk/{surat}/approve', [SuratMasukController::class, 'approve'])->name('surat-masuk.approve');
    Route::post('/surat-masuk/{surat}/reject', [SuratMasukController::class, 'reject'])->name('surat-masuk.reject');

    // ADMIN PUSAT
    Route::post('/surat-masuk/{surat}/approve-admin', [SuratMasukController::class, 'approveAdmin'])->name('surat-masuk.approveAdmin');
});
