<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 管理画面
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/',         static function () {
        return redirect('/admin/home');
    });
    Route::get('login', [App\Http\Controllers\Admin\LoginBaseController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [App\Http\Controllers\Admin\LoginBaseController::class, 'login']);

    // ログイン後
    Route::group(['middleware' => 'auth:admin'], static function () {
        Route::post('logout', [App\Http\Controllers\Admin\LoginBaseController::class, 'logout'])->name('admin.logout');
        Route::get('home', [App\Http\Controllers\Admin\HomeBaseController::class, 'index'])->name('admin.home');

        Route::get('user', [App\Http\Controllers\Admin\UserBaseController::class, 'index'])->name('admin.user');
        Route::get('user/{user}', [App\Http\Controllers\Admin\UserBaseController::class, 'show'])->name('admin.user.show');
        Route::get('user/{user}/edit', [App\Http\Controllers\Admin\UserBaseController::class, 'edit'])->name('admin.user.edit');
        Route::post('user/{user}/update', [App\Http\Controllers\Admin\UserBaseController::class, 'update'])->name('admin.user.update');
        Route::post('user/{user}/destroy', [App\Http\Controllers\Admin\UserBaseController::class, 'destroy'])->name('admin.user.destroy');

        Route::get('stock', [App\Http\Controllers\Admin\StockBaseController::class, 'index'])->name('admin.stock');
        Route::get('stock/downloadExcel', [App\Http\Controllers\Admin\StockBaseController::class, 'downloadExcel'])->name('admin.stock.downloadExcel');
        Route::get('stock/downloadCsv', [App\Http\Controllers\Admin\StockBaseController::class, 'downloadCsv'])->name('admin.stock.downloadCsv');
        Route::get('stock/downloadPdf', [App\Http\Controllers\Admin\StockBaseController::class, 'downloadPdf'])->name('admin.stock.downloadPdf');
        Route::get('stock/create', [App\Http\Controllers\Admin\StockBaseController::class, 'create'])->name('admin.stock.create');
        Route::post('stock/store', [App\Http\Controllers\Admin\StockBaseController::class, 'store'])->name('admin.stock.store');
        Route::get('stock/{stock}', [App\Http\Controllers\Admin\StockBaseController::class, 'show'])->name('admin.stock.show');
        Route::get('stock/{stock}/edit', [App\Http\Controllers\Admin\StockBaseController::class, 'edit'])->name('admin.stock.edit');
        Route::post('stock/{stock}/update', [App\Http\Controllers\Admin\StockBaseController::class, 'update'])->name('admin.stock.update');
        Route::post('stock/{stock}/destroy', [App\Http\Controllers\Admin\StockBaseController::class, 'destroy'])->name('admin.stock.destroy');

        Route::get('order', [App\Http\Controllers\Admin\OrderBaseController::class, 'index'])->name('admin.order');
        Route::get('order/{order}', [App\Http\Controllers\Admin\OrderBaseController::class, 'show'])->name('admin.order.show');

        Route::get('contact', [App\Http\Controllers\Admin\ContactFormBaseController::class, 'index'])->name('admin.contact');
        Route::get('contact/{contact}', [App\Http\Controllers\Admin\ContactFormBaseController::class, 'show'])->name('admin.contact.show');
        Route::get('contact/{contact}/edit', [App\Http\Controllers\Admin\ContactFormBaseController::class, 'edit'])->name('admin.contact.edit');
        Route::post('contact/{contact}/update', [App\Http\Controllers\Admin\ContactFormBaseController::class, 'update'])->name('admin.contact.update');
        Route::post('contact/{contact}/destroy', [App\Http\Controllers\Admin\ContactFormBaseController::class, 'destroy'])->name('admin.contact.destroy');

        Route::get('photo', [App\Http\Controllers\Admin\PhotoBaseController::class, 'index'])->name('admin.photo');
        Route::post('photo/destroy', [App\Http\Controllers\Admin\PhotoBaseController::class, 'destroy'])->name('admin.photo.destroy');
    });
});


/*
|--------------------------------------------------------------------------
| ソーシャルログイン
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->middleware('guest')->group(function () {

    Route::get('/{provider}', [App\Http\Controllers\Auth\OAuthBaseController::class, 'socialOAuth'])
        ->where('provider', 'google')
        ->name('socialOAuth');

    Route::get('/{provider}/callback', [App\Http\Controllers\Auth\OAuthBaseController::class, 'handleProviderCallback'])
        ->where('provider', 'google')
        ->name('oauthCallback');
});
/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
Route::prefix('api')->middleware('api')->group(function () {
    Route::resource('/consts', App\Http\Controllers\Api\ConstControllerBase::class);
    Route::resource('/likes', App\Http\Controllers\Api\LikeControllerBase::class);
    Route::post('/likes/store', [App\Http\Controllers\Api\LikeControllerBase::class, 'store']);
    Route::post('/likes/destroy/{id}', [App\Http\Controllers\Api\LikeControllerBase::class, 'destroy']);
    Route::resource('/shops', App\Http\Controllers\Api\ShopControllerBase::class);
    Route::post('/contact/store', [App\Http\Controllers\Api\ContactFormControllerBase::class, 'store']);

    Route::middleware(['auth:sanctum'])->group(function () {
        // ログイン後
        Route::post('/session', function (Request $request) {
            return $request->user();
        });
        Route::post('/mycart', [App\Http\Controllers\Api\ShopControllerBase::class, 'mycart'])->name('shop.mycart');
        Route::post('/addMycart', [App\Http\Controllers\Api\ShopControllerBase::class, 'addMycart'])->name('shop.addcart');
        Route::post('/cartdelete', [App\Http\Controllers\Api\ShopControllerBase::class, 'deleteCart'])->name('shop.delete');
        Route::post('/createPayment', [App\Http\Controllers\Api\ShopControllerBase::class, 'createPayment'])->name('shop.createPayment');
        Route::post('/checkout', [App\Http\Controllers\Api\ShopControllerBase::class, 'checkout'])->name('shop.check');
    });
});

/*
|--------------------------------------------------------------------------
| フロント
|--------------------------------------------------------------------------
*/
Route::get('/', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('front.react');
Route::get('/login', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('login');
Route::get('/register', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('register');
Route::get('password/reset', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('password.request');
Route::get('password/reset/{token}', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('password.reset');
Route::get('email/verify', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('verification.notice');
Route::get('/{router}', [App\Http\Controllers\Front\ReactController::class, 'index'])->name('home');
