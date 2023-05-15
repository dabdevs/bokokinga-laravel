<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ShowProductController extends Controller
{
    /**
     * Show a product
     */
    public function show($slug)
    {
        $product = Product::whereSlug($slug)->first();

        return view('web.product.show', compact('product'));
    }

    /**
     * Show a product in a modal
     */
    public function quickView($id)
    {
        
        $product = Product::with(['images', 'primaryImage'])
                            ->whereId($id)
                            ->first();
        return $product;
    }
}
