<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Foods;
use App\Models\PosTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PointOfSaleController extends Controller
{
    public function index()
    {
        $foods = Foods::with('category')->get();
        $cart = Session::get('pos_cart', []);
        $subtotal = $this->calculateSubtotal($cart);
        $tax = $subtotal * 0.12; // 12% tax
        $total = $subtotal + $tax;

        return view('admin.pos.index', compact('foods', 'cart', 'subtotal', 'tax', 'total'));
    }

    public function addToCart(Request $request)
    {
        $foodId = $request->input('food_id');
        $quantity = $request->input('quantity', 1);

        $food = Foods::findOrFail($foodId);
        $cart = Session::get('pos_cart', []);

        if (isset($cart[$foodId])) {
            $cart[$foodId]['quantity'] += $quantity;
        } else {
            $cart[$foodId] = [
                'id' => $food->id,
                'name' => $food->name,
                'price' => $food->price,
                'quantity' => $quantity,
            ];
        }

        Session::put('pos_cart', $cart);

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function updateCart(Request $request)
    {
        $foodId = $request->input('food_id');
        $quantity = $request->input('quantity');

        $cart = Session::get('pos_cart', []);

        if ($quantity <= 0) {
            unset($cart[$foodId]);
        } else {
            if (isset($cart[$foodId])) {
                $cart[$foodId]['quantity'] = $quantity;
            }
        }

        Session::put('pos_cart', $cart);

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function removeFromCart(Request $request)
    {
        $foodId = $request->input('food_id');
        $cart = Session::get('pos_cart', []);

        unset($cart[$foodId]);
        Session::put('pos_cart', $cart);

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function checkout(Request $request)
    {
        $cart = Session::get('pos_cart', []);
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty']);
        }

        $subtotal = $this->calculateSubtotal($cart);
        $tax = $subtotal * 0.12;
        $total = $subtotal + $tax;

        $transaction = PosTransaction::create([
            'items' => $cart,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $request->input('payment_method'),
            'status' => 'completed',
        ]);

        Session::forget('pos_cart');

        return response()->json(['success' => true, 'transaction' => $transaction]);
    }

    public function transactions()
    {
        $transactions = PosTransaction::latest()->paginate(20);
        return view('admin.pos.transactions', compact('transactions'));
    }

    public function showTransaction($id)
    {
        $transaction = PosTransaction::findOrFail($id);
        return view('admin.pos.show', compact('transaction'));
    }

    private function calculateSubtotal($cart)
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }

    public function processTransaction(Request $request)
    {
        // Legacy method, can be removed or kept for compatibility
        return $this->checkout($request);
    }
}
