<?php

use App\Http\Controllers\CategoryCoaController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

Route::group(['prefix' => '/master', 'as' => 'master.', 'middleware' => ['auth']], function () {
    Route::resource('kategori', CategoryCoaController::class)->parameters([
        'kategori' => 'categoryCoa'
    ]);
    Route::resource('chartofaccount', ChartOfAccountController::class);
});

Route::group(['prefix' => '/laporan', 'as' => 'laporan.', 'middleware' => ['auth']], function () {
    Route::get('laba-rugi', [ReportController::class, 'profit'])->name('profit');
});

Route::resource('transaction', TransactionController::class)->middleware('auth');

Route::get('option-categories', [OptionsController::class, 'getCategories'])->name('option.category');
Route::get('option-coas', [OptionsController::class, 'getCoa'])->name('option.coa');
