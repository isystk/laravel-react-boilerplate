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

// Route::get('/', function () {
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
    Route::get('login', [App\Http\Controllers\Admin\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [App\Http\Controllers\Admin\LoginController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Admin ログイン後
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
    Route::post('logout', [App\Http\Controllers\Admin\LoginController::class, 'logout'])->name('admin.logout');
    Route::get('home', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('admin.home');

    Route::get('user', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.user');
    Route::get('user/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.user.show');
    Route::get('user/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.user.edit');
    Route::post('user/{id}/update', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.user.update');
    Route::post('user/{id}/destroy', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.user.destroy');

    Route::get('stock', [App\Http\Controllers\Admin\StockController::class, 'index'])->name('admin.stock');
    Route::get('stock/downloadExcel', [App\Http\Controllers\Admin\StockController::class, 'downloadExcel'])->name('admin.stock.downloadExcel');
    Route::get('stock/downloadCsv', [App\Http\Controllers\Admin\StockController::class, 'downloadCsv'])->name('admin.stock.downloadCsv');
    Route::get('stock/downloadPdf', [App\Http\Controllers\Admin\StockController::class, 'downloadPdf'])->name('admin.stock.downloadPdf');
    Route::get('stock/create', [App\Http\Controllers\Admin\StockController::class, 'create'])->name('admin.stock.create');
    Route::post('stock/store', [App\Http\Controllers\Admin\StockController::class, 'store'])->name('admin.stock.store');
    Route::get('stock/{id}', [App\Http\Controllers\Admin\StockController::class, 'show'])->name('admin.stock.show');
    Route::get('stock/{id}/edit', [App\Http\Controllers\Admin\StockController::class, 'edit'])->name('admin.stock.edit');
    Route::post('stock/{id}/update', [App\Http\Controllers\Admin\StockController::class, 'update'])->name('admin.stock.update');
    Route::post('stock/{id}/destroy', [App\Http\Controllers\Admin\StockController::class, 'destroy'])->name('admin.stock.destroy');

    Route::get('order', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.order');
    Route::get('order/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.order.show');

    Route::get('contact', [App\Http\Controllers\Admin\ContactFormController::class, 'index'])->name('admin.contact');
    Route::get('contact/{id}', [App\Http\Controllers\Admin\ContactFormController::class, 'show'])->name('admin.contact.show');
    Route::get('contact/{id}/edit', [App\Http\Controllers\Admin\ContactFormController::class, 'edit'])->name('admin.contact.edit');
    Route::post('contact/{id}/update', [App\Http\Controllers\Admin\ContactFormController::class, 'update'])->name('admin.contact.update');
    Route::post('contact/{id}/destroy', [App\Http\Controllers\Admin\ContactFormController::class, 'destroy'])->name('admin.contact.destroy');

    Route::get('photo', [App\Http\Controllers\Admin\PhotoController::class, 'index'])->name('admin.photo');
    Route::post('photo/{id}/destroy', [App\Http\Controllers\Admin\PhotoController::class, 'destroy'])->name('admin.photo.destroy');
});

/*
|--------------------------------------------------------------------------
| ソーシャルログイン
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->middleware('guest')->group(function () {

Route::get('/{provider}', [App\Http\Controllers\Auth\OAuthController::class, 'socialOAuth'])
    ->where('provider', 'google')
    ->name('socialOAuth');

Route::get('/{provider}/callback', [App\Http\Controllers\Auth\OAuthController::class, 'handleProviderCallback'])
    ->where('provider', 'google')
    ->name('oauthCallback');
});

/*
|--------------------------------------------------------------------------
| React
|--------------------------------------------------------------------------
*/
Route::get('/', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('front.react');
Route::get('/login', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('login');
Route::get('/register', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('register');
Route::get('password/reset', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('password.request');
Route::get('password/reset/{token}', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('password.reset');
Route::get('email/verify', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('verification.notice');
Route::post('/session', [App\Http\Controllers\Front\ReactController::class, 'session'])->name('session');
Route::get('/{router}', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('home');


/*
|--------------------------------------------------------------------------
| API  ログイン後
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth:user'], function () {
    Route::post('/api/mycart', [App\Http\Controllers\Api\ShopController::class, 'mycart'])->name('shop.mycart');
    Route::post('/api/addMycart', [App\Http\Controllers\Api\ShopController::class, 'addMycart'])->name('shop.addcart');
    Route::post('/api/cartdelete', [App\Http\Controllers\Api\ShopController::class, 'deleteCart'])->name('shop.delete');
    Route::post('/api/createPayment', [App\Http\Controllers\Api\ShopController::class, 'createPayment'])->name('shop.createPayment');
    Route::post('/api/checkout', [App\Http\Controllers\Api\ShopController::class, 'checkout'])->name('shop.check');
});
