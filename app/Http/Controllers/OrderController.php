<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
        $data = $request->validate([
            'firstname' => 'required|string|max:150',
            'lastname' => 'required|string|max:150',
            'email' => 'required|string|max:150',
            'cellphone' => 'required|numeric',
            'province' => 'required',
            'city' => 'required|string',
            'street' => 'required|string|max:150',
            'number' => 'required|numeric',
            'apt' => 'required',
            'postal_code' => 'required|string|max:150',
            'total_price' => 'required|numeric'
        ]);  
        
        try {
            DB::beginTransaction();

            $customer = session('customer') == null ? Customer::whereEmail($data['email'])->first() : Customer::find(session('customer')->id);

            // Create customer if not exists
            if ($customer == null && isset($data['email'])) $customer = Customer::create($data);
            // Create customer address
            $new_address = $customer->addresses()->firstOrCreate([
                "city" => $data["city"],
                "province" => $data["province"],
                "street" => $data["street"],
                "number" => $data["number"],
                "apt" => $data["apt"],
                "postal_code" => $data["postal_code"],
                "cellphone" => $data["cellphone"],
            ]); 
            
            // Create new order
            $order = $customer->orders()->firstOrCreate([
                'subtotal' => session('subtotal'),
                'shipping_price' => $request->total_price - session('subtotal'),
                'total_price' => $request->total_price,
                'address_id' => $new_address->id 
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

        // Provincias
        $provincias = Cache::rememberForever('provincias', function () {
            $json = File::get(database_path().'/json/provincias.json');
            return json_decode($json, true);
        });

        $shipping_cost = Cache::get('configurations')->where('name', 'shipping_cost')->pluck('value')->first();  

        return view('web.order.checkout', compact('shipping_cost', 'provincias'));
    }
}
