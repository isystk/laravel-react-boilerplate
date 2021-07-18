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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['api']], function () {
    Route::resource('consts', App\Http\Controllers\Api\ConstController::class);
    Route::resource('likes', App\Http\Controllers\Api\LikeController::class);
    Route::post('/likes/store', [App\Http\Controllers\Api\LikeController::class, 'store']);
    Route::post('/likes/destroy/{id}', [App\Http\Controllers\Api\LikeController::class, 'destroy']);
    Route::resource('shops', App\Http\Controllers\Api\ShopController::class);
    Route::post('/contact/store', [App\Http\Controllers\Api\ContactFormController::class, 'store']);
});
