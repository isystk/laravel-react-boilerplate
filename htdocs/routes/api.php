<?php

use Illuminate\Http\Request;

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
  Route::resource('consts', 'Api\ConstController');
    Route::resource('likes', 'Api\LikeController');
    Route::post('/likes/store', 'Api\LikeController@store');
    Route::post('/likes/destroy/{id}', 'Api\LikeController@destroy');
    Route::resource('shops', 'Api\ShopController');
    Route::post('/contact/store', 'Api\ContactFormController@store');
});
