<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

class CheckoutController extends Controller
{
    public function order()
    {
        $order = new Order; 

        return view('web.checkout.order');
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
