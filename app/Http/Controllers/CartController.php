<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart($id)
    {
        $cart = session()->get('cart', []);

        $product = Product::findOrFail($id);

        $cart[$id] = [
            "name" => $product->product_name,
            "price" => $product->price,
            "qty" => ($cart[$id]['qty'] ?? 0) + 1
        ];

        session()->put('cart', $cart);

        return back()->with('success', 'Product added to cart');
    }

    public function checkout()
    {
        $cart = session('cart');

        if (!$cart) {
            return back()->with('error', 'Cart is empty');
        }

        foreach ($cart as $id => $item) {

            $product = Product::findOrFail($id);

            $product->stock -= $item['qty'];
            $product->save();

            StockMovement::create([
                'product_id' => $id,
                'user_id' => Auth::id(),
                'quantity' => -$item['qty'],
                'type' => 'out'
            ]);
        }

        session()->forget('cart');

        return redirect()->back()->with('success', 'Checkout successful');
    }
}

