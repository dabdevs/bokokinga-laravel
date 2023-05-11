<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ShowProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($slug)
    {
        $product = Product::whereSlug($slug)->first();

        return view('web.product.show', compact('product'));
    }
}
