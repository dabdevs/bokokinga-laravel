<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\ConfigurationsController;
use Illuminate\Support\Facades\Route;

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
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/authenticate', [UsersController::class, 'authenticate'])->name('admin.authenticate');
    Route::post('/logout', [UsersController::class, 'logout'])->name('admin.logout');
    Route::get('/configurations', [ConfigurationsController::class, 'index'])->name('admin.configurations.index');
    Route::resource('configurations', ConfigurationsController::class);
    Route::resource('collections', CollectionsController::class);
});





