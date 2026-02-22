<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {
    Route::get('/const', [\App\Http\Controllers\Api\ConstController::class, 'index'])->name('api.const');
    Route::resource('/like', App\Http\Controllers\Api\LikeController::class);
    Route::post('/like/store', [\App\Http\Controllers\Api\LikeController::class, 'store']);
    Route::post('/like/destroy/{id}', [\App\Http\Controllers\Api\LikeController::class, 'destroy']);
    Route::get('/stock', [\App\Http\Controllers\Api\StockController::class, 'index'])->name('api.stock');
    Route::post('/contact/store', [\App\Http\Controllers\Api\ContactController::class, 'store'])->name('api.contact.store');

    // 認証 API
    Route::post('/login', \App\Http\Controllers\Api\Auth\LoginController::class)->middleware('throttle:login')->name('api.login');
    Route::post('/logout', \App\Http\Controllers\Api\Auth\LogoutController::class)->name('api.logout');
    Route::post('/register', \App\Http\Controllers\Api\Auth\RegisterController::class)->name('api.register');
    Route::post('/forgot-password', \App\Http\Controllers\Api\Auth\ForgotPasswordController::class)->name('api.forgot-password');
    Route::post('/reset-password', \App\Http\Controllers\Api\Auth\ResetPasswordController::class)->name('api.reset-password');

    Route::middleware(['auth:sanctum'])->group(function () {
        // メール認証
        Route::post('/email/verification-notification', [\App\Http\Controllers\Api\Auth\EmailVerificationController::class, 'resend'])->name('api.verification.resend');

        // ログイン後
        Route::get('/user', [\App\Http\Controllers\Api\SessionController::class, 'index'])->name('api.login-check');
        Route::post('/mycart', [\App\Http\Controllers\Api\CartController::class, 'mycart'])->name('api.mycart');
        Route::post('/mycart/add', [\App\Http\Controllers\Api\CartController::class, 'addMycart'])->name('api.mycart.add');
        Route::post('/mycart/delete', [\App\Http\Controllers\Api\CartController::class, 'deleteCart'])->name('api.mycart.delete');
        Route::post('/mycart/payment', [\App\Http\Controllers\Api\CartController::class, 'createPayment'])->name('api.mycart.payment');
        Route::post('/mycart/checkout', [\App\Http\Controllers\Api\CartController::class, 'checkout'])->name('api.mycart.checkout');
        Route::post('/profile/update', [\App\Http\Controllers\Api\ProfileController::class, 'update'])->name('api.profile.update');
        Route::post('/profile/destroy', [\App\Http\Controllers\Api\ProfileController::class, 'destroy'])->name('api.profile.destroy');
    });
});
