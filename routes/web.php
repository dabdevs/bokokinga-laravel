<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\ConfigurationsController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\ProductForm;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('front/index');
});

Route::get('/login', function () {
    return view('front/login');
});

Route::group(['prefix' => '/'], function () {
    Route::get('/', [WebController::class, 'index'])->name('web.index');
    Route::get('/login', [WebController::class, 'login'])->name('web.login');
    Route::get('/search', [WebController::class, 'search'])->name('web.search');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/authenticate', [UsersController::class, 'authenticate'])->name('admin.authenticate');

    Route::middleware(['admin'])->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/logout', [UsersController::class, 'logout'])->name('admin.logout');
        Route::get('/configurations', [ConfigurationsController::class, 'index'])->name('admin.configurations.index');
        Route::resource('configurations', ConfigurationsController::class);
        Route::resource('collections', CollectionsController::class);
        //Route::resource('products', ProductsController::class);
        Route::get('/products', ProductForm::class)->name('products.index');;
        //Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
    });
});