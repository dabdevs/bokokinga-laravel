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

        $cart = session('cart', []);  

        if (isset($cart["items"][$id])) {
            $quantity == null ? $cart["items"][$id]['quantity']++ : $cart["items"][$id]['quantity'] += $quantity;
        } else {
            $cart["items"][$id] = [
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
        $cart_data = $this->cartCount();

        return [
            'success' => 'Producto agregado!',
            'cart' => $cart,
            'cartCount' => $cart_data['cartCount'],
            'subtotal' => $cart_data['subtotal'],
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
            $cart["items"][$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            $cart_data = $this->cartCount(); 

            return [
                'success' => 'Carrito actualizado!',
                'cartCount' => $cart_data['cartCount'],
                'subtotal' => $cart_data['subtotal'],
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
            if (isset($cart["items"][$request->id])) {
                unset($cart["items"][$request->id]);
                session()->put('cart', $cart);
                $cart_data = $this->cartCount();
            }

            return [
                'cartCount' => $cart_data['cartCount'],
                'subtotal' => $cart_data['subtotal'],
                'success' => 'Producto eliminado!'
            ];
        }
    }

    private function cartCount()
    {
        $count = 0;
        $subtotal = 0;

        if (isset(session('cart')['items'])) {
            foreach (session('cart')['items'] as $product) {
                $count += (int)$product['quantity'];
                $subtotal += (float)($product['price'] * $product['quantity']);
            }
        }

        session()->put('cartCount', $count);
        session()->put('subtotal', $subtotal);

        return ['cartCount' => $count, 'subtotal' => $subtotal];
    }
}
