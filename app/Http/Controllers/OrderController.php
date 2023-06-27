<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

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
        if (!$request->has("address_id")) {
            $validateData = [
                'firstname' => 'required|string|max:150',
                'lastname' => 'required|string|max:150',
                'email' => 'required|string|max:150',
                'telephone' => 'nullable|numeric',
                'cellphone' => 'required|numeric',
                'street' => 'required|string|max:150',
                'number' => 'required|numeric',
                'postal_code' => 'required|string|max:150',
                'is_billing_address' => 'nullable',
                'city_id' => 'required|numeric',
                'country_id' => 'required|numeric',
                'total_price' => 'required|numeric'
            ];
        } else {
            $validateData = ["address_id" => "required"];
        }

        $data = $request->validate($validateData); 
        
        try {
            DB::beginTransaction();

            $customer = session('customer') == null ? Customer::whereEmail($data['email'])->first() : Customer::find(session('customer')->id);

            // Create customer if not exists
            if ($customer == null && isset($data['email'])) $customer = Customer::create($data);

            // If new address
            if (isset($data["country_id"])) {
                $address = [
                    "country_id" => $data["country_id"],
                    "city_id" => $data["city_id"],
                    "street" => $data["street"],
                    "number" => $data["number"],
                    "postal_code" => $data["postal_code"],
                    "telephone" => $data["telephone"],
                ];

                if (isset($data['is_billing_address'])) $address['is_billing_address'] = true;

                // Create customer address
                $new_address = $customer->addresses()->firstOrCreate($address); 
            }
            
            // Create new order
            $order = $customer->orders()->firstOrCreate([
                'subtotal' => session('subtotal'),
                'shipping_price' => $request->total_price - session('subtotal'),
                'total_price' => $request->total_price,
                'address_id' => isset($new_address) ? $new_address->id : $request->address_id
            ]); 

            foreach (session('cart')['items'] as $product) {
                $order_item = new OrderItem;
                $order_item->order_id = $order->id;
                $order_item->product_id = $product['id'];
                $order_item->quantity = $product['quantity'];
                $order_item->price = $product['price'];
                $order_item->save();
            }
           
            session()->put('customer', $customer);

            DB::commit();

            return redirect()->route('web.order.payment', [$customer->id, $order->id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function payment($customer_id, $order_id)
    {
        if (!session('cart')) return redirect('/');

        $customer = Customer::findOrFail($customer_id);
        $order = Order::findOrFail($order_id);
        $shipping_cost = Configuration::whereName('shipping_cost')->pluck('value')->first();

        return view('web.order.payment', compact('customer', 'order', 'shipping_cost'));
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

        $shipping_cost = Configuration::whereName('shipping_cost')->pluck('value')->first();  

        return view('web.order.checkout', compact('shipping_cost'));
    }
}
