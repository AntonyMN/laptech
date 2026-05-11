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
            'password' => 'nullable|string|min:8',
            'create_account' => 'boolean',
        ]);

        $userId = Auth::id();

        // Guest Registration
        if (!$userId && $request->create_account && $request->password) {
            // Check if user already exists
            $user = \App\Models\User::where('email', $request->shipping_address['email'])->first();
            if (!$user) {
                $user = \App\Models\User::create([
                    'name' => $request->shipping_address['name'],
                    'email' => $request->shipping_address['email'],
                    'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                ]);
                Auth::login($user);
                $userId = $user->id;
            } else {
                return back()->withErrors(['shipping_address.email' => 'Email already registered. Please login or use a different email.']);
            }
        }

        $order = Order::create([
            'user_id' => $userId,
            'items' => $validated['items'],
            'total' => $validated['total'],
            'status' => 'pending',
            'shipping_address' => $validated['shipping_address'],
        ]);

        return redirect()->route('welcome')->with('success', 'Order placed successfully! Order ID: ' . $order->id);
    }
}

