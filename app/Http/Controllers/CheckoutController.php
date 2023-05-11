<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    public function order()
    {
        return view('web.order.create');
    }
}
