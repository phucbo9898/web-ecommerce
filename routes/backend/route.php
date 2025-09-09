<?php

use App\Http\Controllers\Backend\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\AttributeController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\RatingController;
use App\Http\Controllers\Backend\SlideController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\VoucherController;
use App\Http\Controllers\Backend\WarehouseController;

// Route::get('/', [AuthController::class, 'test'])->name('index');

Route::get('/login', [AuthController::class, 'showFormLogin']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'CheckAdminLogin'], function () {
    Route::get('/logout', [AuthController::class, 'getLogout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/update/{id}', [AuthController::class, 'update'])->name('update');

    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::group(['prefix' => 'categories', 'as' => 'category.'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/create', [CategoryController::class, 'store'])->name('store');
        Route::get('/update/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::post('/{action}/{id}', [CategoryController::class, 'handle'])->name('handle');
    });

    Route::group(['prefix' => 'attribute', 'as' => 'attribute.'], function () {
        Route::get('/', [AttributeController::class, 'index'])->name('index');
        Route::get('/create', [AttributeController::class, 'create'])->name('create');
        Route::post('/create', [AttributeController::class, 'store'])->name('store');
        Route::get('/update/{id}', [AttributeController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [AttributeController::class, 'update'])->name('update');
        Route::post('/{action}/{id}', [AttributeController::class, 'handle'])->name('handle');
    });

    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/create', [ProductController::class, 'store'])->name('store');
        Route::get('/update/{id}', [ProductController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ProductController::class, 'update'])->name('update');
        Route::get('/{action}/{id}', [ProductController::class, 'handle'])->name('handle');
        Route::get('/getAttribute', [ProductController::class, 'getAttribute'])->name('ajax.get.attribute');
    });

    Route::group(['prefix' => 'article', 'as' => 'article.'], function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->name('create');
        Route::post('/create', [ArticleController::class, 'store'])->name('store');
        Route::get('/update/{id}', [ArticleController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ArticleController::class, 'update'])->name('update');
        Route::get('/{action}/{id}', [ArticleController::class, 'handle'])->name('handle');
    });
    //
    //    Route::group(['prefix' => 'transaction'], function () {
    //        Route::get('/', [TransactionController::class, 'index'])->name('admin.transaction.index');
    //        Route::get('/orderItem/{id}', [TransactionController::class, 'getOrderItem'])->name('admin.get.order.item');
    //        Route::get('/paid/{id}', [TransactionController::class, 'transactionPaid'])->name('admin.transaction.paid');
    //        Route::get('/{action}/{id}', [TransactionController::class, 'handle'])->name('admin.transaction.handle');
    //        Route::get('/export/transaction-pdf/{id}', [TransactionController::class, 'exportTransactionPdf'])->name('admin.get.export.transaction');
    //    });
    //
    Route::group(['prefix' => 'comment', 'as' => 'comment.'], function () {
        Route::get('/', [RatingController::class, 'index'])->name('index');
        Route::get('/{action}/{id}', [RatingController::class, 'action'])->name('action');
    });

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/create', [UserController::class, 'store'])->name('store');
        Route::get('/update/{id}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::post('/change-password/{id}', [UserController::class, 'changePassword'])->name('change.password');
        Route::get('/{action}/{id}', [UserController::class, 'action'])->name('action');
    });
    //
    //    Route::group(['prefix' => 'statistics'], function () {
    //        Route::get('/', [StatisticsController::class, 'index'])->name('admin.statistics.index');
    //        Route::get('/list', [StatisticsController::class, 'getStatistics'])->name('admin.get.list.statistical');
    //        Route::get('/export-pdf', [StatisticsController::class, 'exportPdf'])->name('admin.get.export.statistical');
    //        Route::get('/export-excel', [StatisticsController::class, 'exportExcel'])->name('admin.get.export.excel');
    //    });
    Route::group(['prefix' => 'slide', 'as' => 'slide.'], function () {
        Route::get('/', [SlideController::class, 'index'])->name('index');
        Route::get('/create', [SlideController::class, 'create'])->name('create');
        Route::post('/create', [SlideController::class, 'store'])->name('store');
        Route::get('/update/{id}', [SlideController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SlideController::class, 'update'])->name('update');
        Route::get('/{action}/{id}', [SlideController::class, 'handle'])->name('handle');
    });
    Route::group(['prefix' => 'warehouse', 'middleware' => 'CheckRole', 'as' => 'warehouse.'], function () {
        Route::get('/', [WarehouseController::class, 'import'])->name('import');
        Route::get('/import/{id}', [WarehouseController::class, 'importProduct'])->name('import.product');
        Route::get('/export/{id}', [WarehouseController::class, 'exportProduct'])->name('export.product');
        Route::get('/history-import', [WarehouseController::class, 'historyImport'])->name('history-import');
        Route::get('/history-export', [WarehouseController::class, 'historyExport'])->name('history-export');
    });

    Route::group(['prefix' => 'voucher', 'middleware' => 'CheckRole', 'as' => 'voucher.'], function () {
        Route::get('/', [VoucherController::class, 'index'])->name('index');
        Route::get('/create', [VoucherController::class, 'create'])->name('create');
        Route::post('/create', [VoucherController::class, 'store'])->name('store');
        Route::get('/update/{id}', [VoucherController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [VoucherController::class, 'update'])->name('update');
        Route::get('/{action}/{id}', [VoucherController::class, 'action'])->name('action');
    });
});
