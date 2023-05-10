<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function order()
    {
        $order = new Order;
        $order->total_price = session('totalPrice');
        $order->save();

        foreach (session('cart') as $product) {
            $order_item = new OrderItem;
            $order_item->order_id = $order->id;
            $order_item->product_id = $product['id'];
            $order_item->quantity = $product['quantity'];
            $order_item->price = $product['price'];
            $order_item->save();
        }

        return redirect()->route('orders.show', $order->id);
    }

    public function pay()
    {
        return 'pay';
    }

    public function success(Request $request)
    {
        dd($request->all());
        return 'success';
    }

    public function failure()
    {
        return 'failed';
    }

    public function pending()
    {
        return 'pending';
    }

    
}
