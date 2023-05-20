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
        $quantity = request('quantity');

        $cart = session()->get('cart', []); 

        if (isset($cart[$id])) {
            $quantity == null ? $cart[$id]['quantity']++ : $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "id" => $product->id,
                "name" => $product->name,
                "slug" => $product->slug,
                "description" => $product->description,
                "quantity" => $quantity == null ? 1 : $quantity,
                "price" => $product->price,
                "image" => $product->primaryImage->path,
                "product_quantity" => $product->quantity
            ];
        }

        session()->put('cart', $cart); 
        $cartQuantity = $this->cartQuantity(); 

        return [
            'success' => 'Producto agregado!',
            'cartQuantity' => $cartQuantity
        ];
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
            $cartQuantity = $this->cartQuantity();

            return [
                'html' => view('web.cart.includes.items', compact('cartQuantity'))->render(),
                'cartQuantity' => $cartQuantity,
                'subtotal' => session('subtotal'),
                'success' => 'Carrito actualizado!'
            ];
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
                $cartQuantity = $this->cartQuantity();
            }

            return [
                'html' => view('web.cart.includes.items', compact('cartQuantity'))->render(),
                'cartQuantity' => $cartQuantity,
                'success' => 'Producto eliminado!'
            ];
        }
    }

    private function cartQuantity()
    {
        $count = 0;
        $total_price = 0;

        foreach (session('cart') as $product) {
            $count += (int)$product['quantity'];
            $total_price += (float)($product['price'] * $product['quantity']);
        }

        session()->put('cartQuantity', $count);
        session()->put('subtotal', $total_price);

        return $count;
    }
}
