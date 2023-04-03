<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['api']], function () {
    Route::resource('/consts', App\Http\Controllers\Api\ConstController::class);
    Route::resource('/likes', App\Http\Controllers\Api\LikeController::class);
    Route::post('/likes/store', [App\Http\Controllers\Api\LikeController::class, 'store']); // TODO Routeをweb.phpに記述しないと何故かクッキーに保存できないのでそのうち移動したい
    Route::post('/likes/destroy/{id}', [App\Http\Controllers\Api\LikeController::class, 'destroy']);
    Route::resource('/shops', App\Http\Controllers\Api\ShopController::class);
    Route::post('/contact/store', [App\Http\Controllers\Api\ContactFormController::class, 'store']);

    Route::middleware(['auth:sanctum'])->group(function () {
        // ログイン後
        Route::post('/session', function (Request $request) {
            return $request->user();
        });
        Route::post('/mycart', [App\Http\Controllers\Api\ShopController::class, 'mycart'])->name('shop.mycart');
        Route::post('/addMycart', [App\Http\Controllers\Api\ShopController::class, 'addMycart'])->name('shop.addcart');
        Route::post('/cartdelete', [App\Http\Controllers\Api\ShopController::class, 'deleteCart'])->name('shop.delete');
        Route::post('/createPayment', [App\Http\Controllers\Api\ShopController::class, 'createPayment'])->name('shop.createPayment');
        Route::post('/checkout', [App\Http\Controllers\Api\ShopController::class, 'checkout'])->name('shop.check');
    });
});
