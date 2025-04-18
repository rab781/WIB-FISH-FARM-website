<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Add a product to the cart
     */
    public function addToCart(Request $request)
    {
        // Validate request
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        // Get cart from session or create a new cart array
        $cart = Session::get('cart', []);

        $productId = $request->product_id;
        $quantity = $request->quantity;

        // If product already in cart, update quantity
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
            // Ensure ID exists for existing items
            if (!isset($cart[$productId]['id'])) {
                $cart[$productId]['id'] = $productId;
            }
        } else {
            // Add product to cart with basic info
            $cart[$productId] = [
                'id' => $productId,
                'quantity' => $quantity,
                'name' => $request->name ?? 'Product #' . $productId,
                'price' => $request->price ?? 0,
                'image' => $request->image ?? null,
            ];
        }

        // Store updated cart in session
        Session::put('cart', $cart);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Display cart contents
     */
    public function viewCart()
    {
        $cart = Session::get('cart', []);
        $total = 0;

        // Ensure all cart items have an id field and calculate total
        foreach($cart as $productId => &$item) {
            // Fix for undefined array key "id"
            if (!isset($item['id'])) {
                $item['id'] = $productId;
            }
            $total += $item['price'] * $item['quantity'];
        }

        // Update the cart in session with fixed data
        Session::put('cart', $cart);

        return view('cart', compact('cart', 'total'));
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $productId = $request->product_id;
        $cart = Session::get('cart', []);

        if(isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }

        return redirect()->route('cart.view')->with('success', 'Item removed from cart');
    }

    /**
     * Get cart item count for notification badge
     */
    public function getCartCount()
    {
        $cart = Session::get('cart', []);
        $count = 0;

        foreach($cart as $item) {
            $count += $item['quantity'];
        }

        return response()->json(['count' => $count]);
    }
}
