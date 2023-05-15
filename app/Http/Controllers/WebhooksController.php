<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class WebhooksController extends Controller
{
    public function __invoke(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id); 
        $payment_id = $request->get('payment_id'); 
        $mp_token = env('MERCADOPAGO_SECRET');
        $response = Http::get("https://api.mercadopago.com/v1/payments/$payment_id?access_token=$mp_token");
        $response = json_decode($response);

        if ($response->status != null) {
            $order->status = $response->status;
            $order->payment_method = $response->payment_method_id;
            $order->paid_on = $response->date_created;
            $order->save();

            Session::forget(['cart', 'cartQuantity', 'totalPrice']);
        }

        if ($response->status == "rejected" || $response->status == "cancelled") return redirect()->route('web.payment.failure');

        return redirect()->route('web.payment.success');
    }
}
