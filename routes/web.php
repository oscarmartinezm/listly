<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ListShareController;
use Illuminate\Support\Facades\Route;

Route::get('/login', fn () => view('auth.login'))->name('login');
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

Route::middleware('auth')->group(function () {
  Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');
  Route::get('/', fn () => view('home'))->name('home');
  Route::get('/admin', fn () => view('admin'))->name('admin');
  Route::get('/share/{token}', [ListShareController::class, 'accept'])->name('share.accept');
});

Route::get('/offline', function () {
  return view('offline');
})->name('offline');
