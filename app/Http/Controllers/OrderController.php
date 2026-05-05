<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create()
    {
        return Inertia::render('Checkout/Index');
    }

    public function show(Order $order)
    {
        // Ensure user can only see their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Orders/Show', [
            'order' => $order,
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'total' => 'required|numeric',
            'shipping_address' => 'required|array',
            'shipping_address.name' => 'required|string',
            'shipping_address.email' => 'required|email',
            'shipping_address.phone' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.address' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        $order = Order::create($validated);

        return redirect()->route('welcome')->with('success', 'Order placed successfully! Order ID: ' . $order->id);
    }
}

