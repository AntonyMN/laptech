<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * List orders with optional status filter, search, and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $status = $request->input('status');
        $search = trim((string) $request->input('search', ''));
        $perPage = min(max((int) $request->input('per_page', 15), 5), 100);

        $query = Order::query();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('shipping_address', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate($perPage);

        return response()->json($orders);
    }

    /**
     * Get a single order detail.
     */
    public function show(int $id): JsonResponse
    {
        $order = Order::findOrFail($id);

        return response()->json([
            'order' => $order,
        ]);
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:pending,paid,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Order status updated successfully.',
            'order' => $order,
        ]);
    }

    /**
     * Get summary counts of orders grouped by status.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return response()->json([
            'stats' => $stats,
        ]);
    }
}
