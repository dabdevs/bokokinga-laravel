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
            'email' => 'required|string|max:150',
            'telephone' => 'nullable|numeric',
            'cellphone' => 'required|numeric',
            'street' => 'required|string|max:150', 
            'number' => 'required|numeric',
            'postal_code' => 'required|string|max:150',
            'city_id' => 'required|numeric',
            'country_id' => 'required|numeric',
        ]); 

        $customer = Customer::whereEmail($data['email'])->first();

        if (!$customer) $customer = Customer::create($data);

        session()->put('customer', $customer);
        
        $customer->addresses()->create($data);

        // Create new order
        $order = new Order; 
        $order->total_price = session('subtotal');
        $order->save();

        foreach (session('cart') as $product) {
            $order_item = new OrderItem; 
            $order_item->order_id = $order->id;
            $order_item->product_id = $product['id'];
            $order_item->quantity = $product['quantity'];
            $order_item->price = $product['price'];
            $order_item->save();
        }

        $customer->orders()->firstOrNew($order->toArray());

        return redirect()->route('web.checkout.payment', [$customer->id, $order->id]);
    }
}
