<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {
    Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get('/authenticate', [\App\Http\Controllers\Api\AuthController::class, 'authenticate'])->middleware('auth:api');
    Route::get('/const', [\App\Http\Controllers\Api\ConstController::class, 'index'])->name('api.const');
    Route::resource('/like', App\Http\Controllers\Api\LikeController::class);
    Route::post('/like/store', [\App\Http\Controllers\Api\LikeController::class, 'store']);
    Route::post('/like/destroy/{id}', [\App\Http\Controllers\Api\LikeController::class, 'destroy']);
    Route::get('/stock', [\App\Http\Controllers\Api\StockController::class, 'index'])->name('api.stock');
    Route::post('/contact/store', [\App\Http\Controllers\Api\ContactController::class, 'store'])->name('api.contact.store');

    Route::middleware([\App\Http\Middleware\AuthWebOrApi::class])->group(function () {
        // ログイン後
        Route::post('/session', [\App\Http\Controllers\Api\SessionController::class, 'index'])->name('api.session');
        Route::post('/mycart', [\App\Http\Controllers\Api\CartController::class, 'mycart'])->name('api.mycart');
        Route::post('/mycart/add', [\App\Http\Controllers\Api\CartController::class, 'addMycart'])->name('api.mycart.add');
        Route::post('/mycart/delete', [\App\Http\Controllers\Api\CartController::class, 'deleteCart'])->name('api.mycart.delete');
        Route::post('/mycart/payment', [\App\Http\Controllers\Api\CartController::class, 'createPayment'])->name('api.mycart.payment');
        Route::post('/mycart/checkout', [\App\Http\Controllers\Api\CartController::class, 'checkout'])->name('api.mycart.checkout');
        Route::post('/profile/update', [\App\Http\Controllers\Api\ProfileController::class, 'update'])->name('api.profile.update');
    });
});
