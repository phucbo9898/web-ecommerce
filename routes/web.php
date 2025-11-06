<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\LocaleController;

// Switch between the included languages
Route::get('lang/{lang}', [LocaleController::class, 'change'])->name('locale.change');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    includeRouteFiles(__DIR__.'/backend/');
});

Route::group(['as' => 'frontend.'], function () {
    includeRouteFiles(__DIR__.'/frontend/');
});
