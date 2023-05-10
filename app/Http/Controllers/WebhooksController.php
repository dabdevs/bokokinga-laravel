<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebhooksController extends Controller
{
    public function __invoke(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id); 
        $payment_id = $request->get('payment_id'); 
        $mp_token = env('MERCADOPAGO_SECRET');
        $response = Http::get("https://api.mercadopago.com/v1/payments/$payment_id?access_token=$mp_token");
        $response = json_decode($response);

        if ($response->status) {
            $order->status = $response->status;
            $order->save();
        }

        return $response;
    }
}
