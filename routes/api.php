<?php

use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\LoginCheckController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ConstController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\OrderHistoryController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StockController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {
    Route::get('/const', [ConstController::class, 'index'])->name('api.const');
    Route::resource('/like', LikeController::class);
    Route::post('/like/store', [LikeController::class, 'store']);
    Route::post('/like/destroy/{id}', [LikeController::class, 'destroy']);
    Route::get('/stock', [StockController::class, 'index'])->name('api.stock');
    Route::post('/contact/store', [ContactController::class, 'store'])->name('api.contact.store');

    // 認証 API
    Route::post('/login', LoginController::class)->middleware('throttle:login')->name('api.login');
    Route::post('/logout', LogoutController::class)->name('api.logout');
    Route::post('/register', RegisterController::class)->name('api.register');
    Route::post('/forgot-password', ForgotPasswordController::class)->name('api.forgot-password');
    Route::post('/reset-password', ResetPasswordController::class)->name('api.reset-password');

    Route::middleware(['auth:sanctum'])->group(function () {
        // メール認証
        Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->name('api.verification.resend');

        // ログイン後
        Route::get('/user', [LoginCheckController::class, 'index'])->name('api.login-check');
        Route::post('/mycart', [CartController::class, 'mycart'])->name('api.mycart');
        Route::post('/mycart/add', [CartController::class, 'addMycart'])->name('api.mycart.add');
        Route::post('/mycart/delete', [CartController::class, 'deleteCart'])->name('api.mycart.delete');
        Route::post('/mycart/payment', [CartController::class, 'createPayment'])->name('api.mycart.payment');
        Route::post('/mycart/checkout', [CartController::class, 'checkout'])->name('api.mycart.checkout');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('api.profile.update');
        Route::post('/profile/destroy', [ProfileController::class, 'destroy'])->name('api.profile.destroy');
        Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('api.order-history');
    });
});
