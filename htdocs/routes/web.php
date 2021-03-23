<?php

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

  Route::get('user', 'Admin\UserController@index')->name('admin.user.index');
  Route::get('user/download', 'Admin\UserController@download')->name('admin.user.download');
  Route::get('user/show/{id}', 'Admin\UserController@show')->name('admin.user.show');
  Route::get('user/edit/{id}', 'Admin\UserController@edit')->name('admin.user.edit');
  Route::post('user/update/{id}', 'Admin\UserController@update')->name('admin.user.update');
  Route::post('user/destroy/{id}', 'Admin\UserController@destroy')->name('admin.user.destroy');

  Route::get('stock', 'Admin\StockController@index')->name('admin.stock.index');
  Route::get('stock/download', 'Admin\StockController@download')->name('admin.stock.download');
  Route::get('stock/create', 'Admin\StockController@create')->name('admin.stock.create');
  Route::post('stock/store', 'Admin\StockController@store')->name('admin.stock.store');
  Route::get('stock/show/{id}', 'Admin\StockController@show')->name('admin.stock.show');
  Route::get('stock/edit/{id}', 'Admin\StockController@edit')->name('admin.stock.edit');
  Route::post('stock/update/{id}', 'Admin\StockController@update')->name('admin.stock.update');
  Route::post('stock/destroy/{id}', 'Admin\StockController@destroy')->name('admin.stock.destroy');

  Route::get('order', 'Admin\OrderController@index')->name('admin.order.index');
  Route::get('order/download', 'Admin\OrderController@download')->name('admin.order.download');
  Route::get('order/show/{id}', 'Admin\OrderController@show')->name('admin.order.show');

  Route::get('contact', 'Admin\ContactFormController@index')->name('admin.contact.index');
  Route::get('contact/show/{id}', 'Admin\ContactFormController@show')->name('admin.contact.show');
  Route::get('contact/edit/{id}', 'Admin\ContactFormController@edit')->name('admin.contact.edit');
  Route::post('contact/update/{id}', 'Admin\ContactFormController@update')->name('admin.contact.update');
  Route::post('contact/destroy/{id}', 'Admin\ContactFormController@destroy')->name('admin.contact.destroy');
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
| フロント ログイン後
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth:user'], function () {
  Route::post('/mycart', 'Front\ShopController@mycart')->name('shop.mycart');
  Route::post('/addMycart', 'Front\ShopController@addMycart')->name('shop.addcart');
  Route::post('/cartdelete', 'Front\ShopController@deleteCart')->name('shop.delete');
  Route::post('/checkout', 'Front\ShopController@checkout')->name('shop.check');
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

