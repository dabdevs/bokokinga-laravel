<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
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

Route::post('/login', [UserController::class, 'login'])->name('login');

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');