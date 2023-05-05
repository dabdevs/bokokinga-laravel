<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ShowProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($id, $slug)
    {
        $product = Product::find($id);

        return view('web.product.show', compact('product'));
    }
}
