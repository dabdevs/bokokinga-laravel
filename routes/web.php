<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\ConfigurationsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ShowProductController;
use App\Http\Controllers\ShowCollectionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WebhooksController;
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
    Route::get('', [WebController::class, 'index'])->name('web.index');
    Route::get('login', [WebController::class, 'login'])->name('web.login');
    Route::get('buscar', [WebController::class, 'search'])->name('web.search');
    Route::get('producto/{slug}', [ShowProductController::class, 'show'])->name('web.product.show');
    Route::get('producto/{id}/quick-view', [ShowProductController::class, 'quickView'])->name('web.product.quick_view');
    Route::get('colecciones/{slug}', ShowCollectionController::class)->name('web.collection.show');
    Route::get('cart', [ShoppingCartController::class, 'cart'])->name('web.cart');
    Route::post('add-to-cart/{id}', [ShoppingCartController::class, 'add'])->name('web.add_to_cart');
    Route::patch('update-cart', [ShoppingCartController::class, 'update'])->name('web.update_cart');
    Route::delete('remove-from-cart', [ShoppingCartController::class, 'remove'])->name('web.remove_from_cart');
    Route::get('order/checkout', [OrderController::class, 'checkout'])->name('web.order.checkout');
    Route::get('{customer_id}/order/{order}/payment', [OrderController::class, 'payment'])->name('web.order.payment');
    Route::get('order/{id}/checkout/payment/webhook', WebhooksController::class)->name('web.payment.webhook');
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);
    Route::get('order/payment/success', [OrderController::class, 'success'])->name('web.payment.success');
    Route::get('order/payment/failed', [OrderController::class, 'failure'])->name('web.payment.failure');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/authenticate', [UsersController::class, 'authenticate'])->name('admin.authenticate');

    Route::middleware(['admin'])->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/logout', [UsersController::class, 'logout'])->name('admin.logout');
        Route::get('/configuraciones', [ConfigurationsController::class, 'index'])->name('admin.configurations.index');
        Route::resource('configurations', ConfigurationsController::class);
        Route::resource('collections', CollectionsController::class);
        Route::resource('products', ProductsController::class);
    });
});