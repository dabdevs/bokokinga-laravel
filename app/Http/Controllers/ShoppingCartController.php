<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    /**
     * Shooping cart
     */
    public function cart()
    {
        return view('web.cart.index');
    }

    /**
     * Add a product to cart
     *
     */
    public function add($id)
    {
        $product = Product::findOrFail($id); 

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->primaryImage->path
            ];
        }

        session()->put('cart', $cart);
        session()->put('products_quantity', $this->cartQuantity());
        
        return ['success' => 'Producto agregado!'];
    }

    /**
     * Update cart
     *
     */
    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->put('products_quantity', $this->cartQuantity());

            return view('web.cart.includes.products');
        }
    }

    /**
     * Remove product from cart
     *
     */
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
                session()->put('products_quantity', $this->cartQuantity());
            }

            return view('web.cart.includes.products');
        } 
    }

    private function cartQuantity() 
    {
        $count = 0;

        foreach (session('cart') as $product) {
            $count += $product['quantity'];
        }

        return $count;
    }
}
