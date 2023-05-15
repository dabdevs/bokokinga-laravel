<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        if (!session('cart')) return redirect('/');
        return view('web.order.create');
    }

    public function store(Request $request)
    {
        $customer = Customer::findOrFail($request->customer_id);
        $order = $customer->orders()->create([
            'total' => $request->total,
        ]);

        foreach ($request->items as $item) {
            $orderItem = new OrderItem([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
            $order->items()->save($orderItem);
        }

        return redirect()->route('orders.show', $order);
    }

    public function payment($customer_id, $order_id)
    {
        if (!session('cart')) return redirect('/');
        
        $customer = Customer::findOrFail($customer_id); 
        $order = Order::findOrFail($order_id);

        return view('web.order.payment', compact('customer', 'order'));
    }

    public function show(Order $order)
    {
        $orderItems = $order->items;

        return view('web.order.show', compact('order', 'orderItems'));
    }

    public function success()
    {
        return view('web.payment.success');
    }

    public function checkout()
    {
        if (!session('cart')) return redirect('/');
        
        return view('web.order.create');
    }

}
