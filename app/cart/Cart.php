<?php

namespace App\Cart;

class Cart
{
    public function getContent()
    {
        return session('cart');
    }
}
