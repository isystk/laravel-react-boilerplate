<?php

use App\Http\Controllers\Front\Auth\EmailVerificationController;
use App\Http\Controllers\Front\Auth\GoogleLoginController;
use App\Http\Controllers\Front\ReactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 管理画面
|--------------------------------------------------------------------------
*/
require __DIR__ . '/admin.php';

/*
|--------------------------------------------------------------------------
| Googleログイン
|--------------------------------------------------------------------------
*/
Route::get('auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| メール認証
|--------------------------------------------------------------------------
*/
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

/*
|--------------------------------------------------------------------------
| フロント
|--------------------------------------------------------------------------
*/
Route::get('/login', [ReactController::class, 'index'])->where('any', '.*')->name('login');
Route::get('/{any}', [ReactController::class, 'index'])->where('any', '.*')->name('react');
