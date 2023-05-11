<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     *  Create a new customer
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:150',
            'lastname' => 'required|string|max:150',
            'email' => 'required|string|unique:customers|max:150',
            'telephone' => 'required|string|max:150',
            'address' => 'required|string|max:150',
            'postal_code' => 'required|string|max:150',
            'city' => 'required|string|max:150',
            'province' => 'required|string|max:150',
        ]); 

        // Create new customer
        $customer = Customer::create($data);
        session()->put('customer', $customer);

        // Create new order
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

        $customer->orders()->create($order->toArray());

        return redirect()->route('web.checkout.payment', [$customer->id, $order->id]);
    }
}
