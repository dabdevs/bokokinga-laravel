<?php

namespace App\Http\Controllers;

use App\Events\PurchaseCompleted;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Mail\PurchaseSuccessfulMail;
use Illuminate\Support\Facades\Mail;

class WebhooksController extends Controller
{
    public function __invoke(Request $request, $order_id)
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($order_id);
            $payment_id = $request->get('payment_id');
            $mp_token = env('MERCADOPAGO_SECRET');
            $response = Http::get("https://api.mercadopago.com/v1/payments/$payment_id?access_token=$mp_token");
            $response = json_decode($response);

            if (!isset($response->status)) return redirect("/");

            
            if ($response->status != null) {
                $order->payment_status = $response->status;
                $order->payment_method = $response->payment_method_id;
                $order->payment_date = $response->date_created;
                $order->payment_id = $payment_id;
                $order->save();

                Session::forget(['cart', 'cartQuantity', 'subtotal']);
            }

            if ($response->status == "rejected" || $response->status == "cancelled") return redirect()->route('web.payment.failure');

            // foreach ($order->items as $product) {
            //    DB::select("CALL sp_update_products_quantity(?,?)", array($product->id, $product->quantity));
            // }

            // dd($order->items->toArray());

            // Mail::to($order->customer->email)->send(new PurchaseSuccessfulMail($order->items->toArray()));


            event(new PurchaseCompleted($order)); 

            DB::commit();

            return redirect()->route('web.payment.success');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
