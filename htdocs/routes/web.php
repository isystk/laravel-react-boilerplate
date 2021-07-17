<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/welcome', function () {
//     return view('welcome');
// });

Auth::routes(['verify' => true]);

/*
|--------------------------------------------------------------------------
| Admin 認証不要
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin'], function () {
  Route::get('/',         function () {
    return redirect('/admin/home');
  });
  Route::get('login',     'Admin\LoginController@showLoginForm')->name('admin.login');
  Route::post('login',    'Admin\LoginController@login');
});

/*
|--------------------------------------------------------------------------
| Admin ログイン後
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
  Route::post('logout',   'Admin\LoginController@logout')->name('admin.logout');
  Route::get('home',      'Admin\HomeController@index')->name('admin.home');

  Route::get('user', 'Admin\UserController@index')->name('admin.user');
  Route::get('user/{id}', 'Admin\UserController@show')->name('admin.user.show');
  Route::get('user/{id}/edit', 'Admin\UserController@edit')->name('admin.user.edit');
  Route::post('user/{id}/update', 'Admin\UserController@update')->name('admin.user.update');
  Route::post('user/{id}/destroy', 'Admin\UserController@destroy')->name('admin.user.destroy');

  Route::get('stock', 'Admin\StockController@index')->name('admin.stock');
  Route::get('stock/downloadExcel', 'Admin\StockController@downloadExcel')->name('admin.stock.downloadExcel');
  Route::get('stock/downloadCsv', 'Admin\StockController@downloadCsv')->name('admin.stock.downloadCsv');
  Route::get('stock/downloadPdf', 'Admin\StockController@downloadPdf')->name('admin.stock.downloadPdf');
  Route::get('stock/create', 'Admin\StockController@create')->name('admin.stock.create');
  Route::post('stock/store', 'Admin\StockController@store')->name('admin.stock.store');
  Route::get('stock/{id}', 'Admin\StockController@show')->name('admin.stock.show');
  Route::get('stock/{id}/edit', 'Admin\StockController@edit')->name('admin.stock.edit');
  Route::post('stock/{id}/update', 'Admin\StockController@update')->name('admin.stock.update');
  Route::post('stock/{id}/destroy', 'Admin\StockController@destroy')->name('admin.stock.destroy');

  Route::get('order', 'Admin\OrderController@index')->name('admin.order');
  Route::get('order/{id}', 'Admin\OrderController@show')->name('admin.order.show');

  Route::get('contact', 'Admin\ContactFormController@index')->name('admin.contact');
  Route::get('contact/{id}', 'Admin\ContactFormController@show')->name('admin.contact.show');
  Route::get('contact/{id}/edit', 'Admin\ContactFormController@edit')->name('admin.contact.edit');
  Route::post('contact/{id}/update', 'Admin\ContactFormController@update')->name('admin.contact.update');
  Route::post('contact/{id}/destroy', 'Admin\ContactFormController@destroy')->name('admin.contact.destroy');

  Route::get('photo', 'Admin\PhotoController@index')->name('admin.photo');
  Route::post('photo/{id}/destroy', 'Admin\PhotoController@destroy')->name('admin.photo.destroy');
});

/*
|--------------------------------------------------------------------------
| ソーシャルログイン
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->middleware('guest')->group(function () {

  Route::get('/{provider}', 'Auth\OAuthController@socialOAuth')
    ->where('provider', 'google')
    ->name('socialOAuth');

  Route::get('/{provider}/callback', 'Auth\OAuthController@handleProviderCallback')
    ->where('provider', 'google')
    ->name('oauthCallback');
});

/*
|--------------------------------------------------------------------------
| React
|--------------------------------------------------------------------------
*/
Route::get('/', 'Front\ReactController@index')->name('front.react');
Route::get('/login', 'Front\ReactController@index')->name('login');
Route::get('/register', 'Front\ReactController@index')->name('register');
Route::get('password/reset', 'Front\ReactController@index')->name('password.request');
Route::get('password/reset/{token}', 'Front\ReactController@index')->name('password.reset');
Route::get('email/verify', 'Front\ReactController@index')->name('verification.notice');
Route::post('/session', 'Front\ReactController@session')->name('session');
Route::get('/{router}', 'Front\ReactController@index')->name('home');


/*
|--------------------------------------------------------------------------
| API  ログイン後
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth:user'], function () {
  Route::post('/api/mycart', 'Api\ShopController@mycart')->name('shop.mycart');
  Route::post('/api/addMycart', 'Api\ShopController@addMycart')->name('shop.addcart');
  Route::post('/api/cartdelete', 'Api\ShopController@deleteCart')->name('shop.delete');
  Route::post('/api/createPayment', 'Api\ShopController@createPayment')->name('shop.createPayment');
  Route::post('/api/checkout', 'Api\ShopController@checkout')->name('shop.check');
});
