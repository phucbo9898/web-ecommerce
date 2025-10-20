<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\Auth\RegisterController;
use App\Http\Controllers\Frontend\Auth\LoginController;
use App\Http\Controllers\Frontend\Auth\ForgotPasswordController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ShoppingCartController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\HistoryController;
use App\Http\Controllers\Frontend\FavoriteProductController;
use App\Http\Controllers\Frontend\RatingController;
use App\Http\Controllers\Frontend\FeatureUserController;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about-us', 'aboutUs')->name('about-us');
    Route::get('/contact', 'contact')->name('contact');
});

Route::controller(SearchController::class)->group(function () {
    Route::prefix('search')->group(function () {
        Route::name('search.')->group(function () {
            Route::get('/', 'index')->name('index');
        });
    });
});

Route::group(['prefix'=>'auth'],function() {
    Route::get('/register',[RegisterController::class, 'getRegister'])->name('get.register');
    Route::post('/register',[RegisterController::class, 'postRegister']);
    Route::get('/login',[LoginController::class, 'getLogin'])->name('get.login');
    Route::post('/login',[LoginController::class, 'postLogin']);
    Route::get('/logout',[LoginController::class, 'getLogout'])->middleware('checkLoginUser')->name('get.logout');
    Route::post('/forgot-password',[ForgotPasswordController::class, 'postResetPassword'])->name('post.reset.password');
    Route::get('/reset-password',[ForgotPasswordController::class, 'getChangePasswordReset'])->name('get.change.reset.password');
    Route::post('/reset-password',[ForgotPasswordController::class, 'postChangePasswordReset']);
});

Route::controller(CategoryController::class)->group(function () {
    Route::prefix('category')->group(function () {
        Route::name('category.')->group(function () {
            Route::get('/{slug}/{id}', 'index')->name('index');
            Route::get('/{slug}/{id}/{order}', 'indexOrder')->name('index.order');
            Route::get('/{slug}/{id}/attribute/{at}', 'indexOrderAttribute')->name('index.order.attribute');
        });
    });
});

Route::controller(ProductController::class)->group(function () {
    Route::prefix('product')->group(function () {
        Route::name('product.')->group(function () {
            Route::get('/{slug}/{id}', 'index')->name('index');
        });
    });
});

Route::middleware('checkLoginUser')->group(function () {
    Route::prefix('profile')->group(function () {
        Route::name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::put('/update/{id}', [ProfileController::class, 'update'])->name('update');
        });
    });

    Route::prefix('shopping')->group(function () {
        Route::name('shopping.')->group(function () {
            Route::get('/', [ShoppingCartController::class, 'index'])->name('cart.index');
            Route::get('add/{id}', [ShoppingCartController::class, 'addProduct'])->name('add.product');
            Route::get('/delete/{key}', [ShoppingCartController::class, 'deleteProductItem'])->name('delete.product');
            Route::post('/edit', [ShoppingCartController::class, 'editProductItem'])->name('edit.product');
            Route::post('/payment-momo', [ShoppingCartController::class, 'paymentMomo'])->name('payment-momo');
            Route::post('/vnpay_payment', [ShoppingCartController::class, 'paymentVNPay'])->name('payment-vnpay');
            Route::get('/add-coupon', [ShoppingCartController::class, 'addCoupon'])->name('add-coupon');
        });
    });

    Route::prefix('feature-user')->group(function () {
        Route::name('feature-user.')->group(function () {
            Route::get('/checkout', [FeatureUserController::class, 'getFormPay'])->name('checkout');
            Route::post('/checkout', [FeatureUserController::class, 'saveInfoShoppingCart']);
            Route::get('/delete/notification/{id}', [FeatureUserController::class, 'deleteNotification'])->name('delete.notification');
            Route::get('/checkout/vnpay-check', [FeatureUserController::class, 'vnpayCheck'])->name('vnpay-check');
            Route::get('/checkout/momo-check', [FeatureUserController::class, 'momoCheck'])->name('momo-check');
        });
    });

    Route::prefix('rating')->group(function () {
        Route::post('/{id}', [RatingController::class, 'saveRating'])->name('post.rating.product');
        Route::get('/delete/{id}', [RatingController::class, 'deleteRating'])->name('get.delete.rating.product');
    });

    Route::prefix('favorite-product')->group(function () {
        Route::name('favorite-product.')->group(function () {
            Route::get('/', [FavoriteProductController::class, 'index'])->name('index');
            Route::get('add/{id}', [FavoriteProductController::class, 'addProduct'])->name('get.add');
            Route::get('delete/{id}', [FavoriteProductController::class, 'deleteProduct'])->name('get.delete');
        });
    });

    Route::prefix('history-user')->group(function () {
        Route::name('history-user.')->group(function () {
            Route::get('/', [HistoryController::class, 'index'])->name('index');
            Route::get('/get-order-item/{id}', [HistoryController::class, 'getOrderItem'])->name('get.order.item');
            Route::get('/paid/{action}/{id}', [HistoryController::class, 'transactionPaid'])->name('transaction.paid');
        });
    });
});

Route::controller(ArticleController::class)->group(function () {
    Route::prefix('article')->group(function () {
        Route::name('article.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/detail/{id}', 'getDetailArticle')->name('detail');
        });
    });
});
