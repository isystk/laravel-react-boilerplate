<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 管理画面
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\IndexController::class, 'index'])->name('admin');
    Route::get('login', [\App\Http\Controllers\Admin\LoginController::class, 'index'])->name('admin.login');
    Route::post('login', [\App\Http\Controllers\Admin\LoginController::class, 'login'])->name('admin.login');

    // ログイン後
    Route::group(['middleware' => 'auth:admin'], static function () {
        Route::post('logout', [\App\Http\Controllers\Admin\LoginController::class, 'logout'])->name('admin.logout');
        Route::get('home', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('admin.home');

        Route::get('passwordChange', [\App\Http\Controllers\Admin\PasswordChangeController::class, 'index'])->name('admin.passwordChange');
        Route::put('passwordChange/update', [\App\Http\Controllers\Admin\PasswordChangeController::class, 'update'])->name('admin.passwordChange.update');

        Route::get('user', [\App\Http\Controllers\Admin\User\ListController::class, 'index'])->name('admin.user');
        Route::get('user/{user}', [\App\Http\Controllers\Admin\User\DetailController::class, 'show'])->name('admin.user.show');
        Route::delete('user/{user}/destroy', [\App\Http\Controllers\Admin\User\DetailController::class, 'destroy'])->name('admin.user.destroy');
        Route::get('user/{user}/edit', [\App\Http\Controllers\Admin\User\EditController::class, 'edit'])->name('admin.user.edit');
        Route::put('user/{user}/update', [\App\Http\Controllers\Admin\User\EditController::class, 'update'])->name('admin.user.update');

        Route::get('stock', [\App\Http\Controllers\Admin\Stock\ListController::class, 'index'])->name('admin.stock');
        Route::get('stock/export', [\App\Http\Controllers\Admin\Stock\ListController::class, 'export'])->name('admin.stock.export');
        Route::get('stock/create', [\App\Http\Controllers\Admin\Stock\CreateController::class, 'create'])->name('admin.stock.create');
        Route::post('stock/store', [\App\Http\Controllers\Admin\Stock\CreateController::class, 'store'])->name('admin.stock.store');
        Route::get('stock/{stock}', [\App\Http\Controllers\Admin\Stock\DetailController::class, 'show'])->name('admin.stock.show');
        Route::delete('stock/{stock}/destroy', [\App\Http\Controllers\Admin\Stock\DetailController::class, 'destroy'])->name('admin.stock.destroy');
        Route::get('stock/{stock}/edit', [\App\Http\Controllers\Admin\Stock\EditController::class, 'edit'])->name('admin.stock.edit');
        Route::put('stock/{stock}/update', [\App\Http\Controllers\Admin\Stock\EditController::class, 'update'])->name('admin.stock.update');

        Route::get('order', [\App\Http\Controllers\Admin\Order\ListController::class, 'index'])->name('admin.order');
        Route::get('order/{order}', [\App\Http\Controllers\Admin\Order\DetailController::class, 'show'])->name('admin.order.show');

        Route::get('contact', [\App\Http\Controllers\Admin\ContactForm\ListController::class, 'index'])->name('admin.contact');
        Route::get('contact/{contactForm}', [\App\Http\Controllers\Admin\ContactForm\DetailController::class, 'show'])->name('admin.contact.show');
        Route::delete('contact/{contactForm}/destroy', [\App\Http\Controllers\Admin\ContactForm\DetailController::class, 'destroy'])->name('admin.contact.destroy');
        Route::get('contact/{contactForm}/edit', [\App\Http\Controllers\Admin\ContactForm\EditController::class, 'edit'])->name('admin.contact.edit');
        Route::put('contact/{contactForm}/update', [\App\Http\Controllers\Admin\ContactForm\EditController::class, 'update'])->name('admin.contact.update');

        Route::get('photo', [\App\Http\Controllers\Admin\Photo\ListController::class, 'index'])->name('admin.photo');
        Route::delete('photo/destroy', [\App\Http\Controllers\Admin\Photo\ListController::class, 'destroy'])->name('admin.photo.destroy');

        Route::get('staff', [\App\Http\Controllers\Admin\Staff\ListController::class, 'index'])->name('admin.staff');
        Route::get('staff/create', [\App\Http\Controllers\Admin\Staff\CreateController::class, 'create'])->name('admin.staff.create');
        Route::post('staff/store', [\App\Http\Controllers\Admin\Staff\CreateController::class, 'store'])->name('admin.staff.store');
        Route::get('staff/import', [\App\Http\Controllers\Admin\Staff\ImportController::class, 'index'])->name('admin.staff.import');
        Route::post('staff/import/store', [\App\Http\Controllers\Admin\Staff\ImportController::class, 'store'])->name('admin.staff.import.store');
        Route::get('staff/import/export', [\App\Http\Controllers\Admin\Staff\ImportController::class, 'export'])->name('admin.staff.import.export');
        Route::get('staff/import/import_file/{importHistoryId}', [\App\Http\Controllers\Admin\Staff\ImportController::class, 'importFile'])->name('admin.staff.import.import_file');
        Route::get('staff/{staff}', [\App\Http\Controllers\Admin\Staff\DetailController::class, 'show'])->name('admin.staff.show');
        Route::delete('staff/{staff}/destroy', [\App\Http\Controllers\Admin\Staff\DetailController::class, 'destroy'])->name('admin.staff.destroy');
        Route::get('staff/{staff}/edit', [\App\Http\Controllers\Admin\Staff\EditController::class, 'edit'])->name('admin.staff.edit');
        Route::put('staff/{staff}/update', [\App\Http\Controllers\Admin\Staff\EditController::class, 'update'])->name('admin.staff.update');
    });
});

/*
|--------------------------------------------------------------------------
| ソーシャルログイン
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->middleware('guest')->group(function () {
    Route::get('/{provider}', [\App\Http\Controllers\Front\Auth\OAuthController::class, 'socialOAuth'])
        ->where('provider', 'google')
        ->name('socialOAuth');

    Route::get('/{provider}/callback', [\App\Http\Controllers\Front\Auth\OAuthController::class, 'handleProviderCallback'])
        ->where('provider', 'google')
        ->name('oauthCallback');
});

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
Route::prefix('api')->middleware('api')->group(function () {
    Route::get('/const', [\App\Http\Controllers\Api\ConstController::class, 'index'])->name('api.const');
    Route::resource('/like', App\Http\Controllers\Api\LikeController::class);
    Route::post('/like/store', [\App\Http\Controllers\Api\LikeController::class, 'store']);
    Route::post('/like/destroy/{id}', [\App\Http\Controllers\Api\LikeController::class, 'destroy']);
    Route::get('/stock', [\App\Http\Controllers\Api\StockController::class, 'index'])->name('api.stock');
    Route::post('/contact/store', [\App\Http\Controllers\Api\ContactFormController::class, 'store'])->name('api.contact.store');

    Route::middleware(['auth:sanctum'])->group(function () {
        // ログイン後
        Route::post('/session', static function (Request $request) {
            return $request->user();
        });
        Route::post('/mycart', [\App\Http\Controllers\Api\CartController::class, 'mycart'])->name('api.mycart');
        Route::post('/mycart/add', [\App\Http\Controllers\Api\CartController::class, 'addMycart'])->name('api.mycart.add');
        Route::post('/mycart/delete', [\App\Http\Controllers\Api\CartController::class, 'deleteCart'])->name('api.mycart.delete');
        Route::post('/mycart/payment', [\App\Http\Controllers\Api\CartController::class, 'createPayment'])->name('api.mycart.payment');
        Route::post('/mycart/checkout', [\App\Http\Controllers\Api\CartController::class, 'checkout'])->name('api.mycart.checkout');
    });
});

/*
|--------------------------------------------------------------------------
| フロント
|--------------------------------------------------------------------------
*/
Route::get('/{any}', [\App\Http\Controllers\Front\ReactController::class, 'index'])->where('any', '.*')->name('react');
