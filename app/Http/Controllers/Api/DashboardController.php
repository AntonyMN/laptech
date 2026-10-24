<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get aggregated admin dashboard analytics, alerts, and quicklinks.
     */
    public function index(Request $request): JsonResponse
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');

        $totalProducts = Product::active()->count();
        $archivedProducts = Product::archived()->count();
        $lowStockProducts = Product::active()->where('stock', '<=', 3)->get(['id', 'name', 'stock', 'price', 'image']);
        $outOfStockCount = Product::active()->where('stock', '<=', 0)->count();

        $totalServices = Service::count();
        $totalQuotes = Quote::count();
        $pendingQuotes = Quote::where('status', 'pending')->count();
        $totalUsers = User::count();

        $recentOrders = Order::latest()
            ->limit(6)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'total' => (float) $order->total,
                    'status' => $order->status,
                    'items_count' => is_array($order->items) ? count($order->items) : 0,
                    'customer_name' => $order->shipping_address['full_name'] ?? ($order->shipping_address['name'] ?? 'Guest Customer'),
                    'customer_email' => $order->shipping_address['email'] ?? null,
                    'created_at' => $order->created_at?->toIso8601String(),
                ];
            });

        // Generate actionable alerts
        $alerts = [];

        if ($lowStockProducts->count() > 0) {
            $alerts[] = [
                'id' => 'low_stock',
                'type' => 'warning',
                'title' => 'Low Inventory Warning',
                'message' => "{$lowStockProducts->count()} products have 3 or fewer units in stock.",
                'count' => $lowStockProducts->count(),
                'action_tab' => 'products',
                'items' => $lowStockProducts->take(3)->values(),
            ];
        }

        if ($pendingOrders > 0) {
            $alerts[] = [
                'id' => 'pending_orders',
                'type' => 'info',
                'title' => 'Pending Customer Orders',
                'message' => "{$pendingOrders} new order(s) require status review and dispatch.",
                'count' => $pendingOrders,
                'action_tab' => 'orders',
            ];
        }

        if ($pendingQuotes > 0) {
            $alerts[] = [
                'id' => 'pending_quotes',
                'type' => 'info',
                'title' => 'Unaddressed Quotations',
                'message' => "{$pendingQuotes} quote requests waiting for review.",
                'count' => $pendingQuotes,
                'action_tab' => 'quotes',
            ];
        }

        $quickLinks = [
            [
                'id' => 'add_product',
                'title' => 'New Product',
                'icon' => 'add_box',
                'category' => 'inventory',
                'target' => 'products_create',
            ],
            [
                'id' => 'add_service',
                'title' => 'New Service',
                'icon' => 'build_circle',
                'category' => 'services',
                'target' => 'services_create',
            ],
            [
                'id' => 'view_pending_orders',
                'title' => 'Pending Orders',
                'icon' => 'local_shipping',
                'category' => 'orders',
                'badge' => (string) $pendingOrders,
                'target' => 'orders_pending',
            ],
            [
                'id' => 'view_alerts',
                'title' => 'Low Stock Alerts',
                'icon' => 'notification_important',
                'category' => 'alerts',
                'badge' => (string) $lowStockProducts->count(),
                'target' => 'products_low_stock',
            ],
        ];

        return response()->json([
            'stats' => [
                'revenue' => (float) $totalRevenue,
                'total_orders' => $totalOrders,
                'pending_orders' => $pendingOrders,
                'delivered_orders' => $deliveredOrders,
                'total_products' => $totalProducts,
                'archived_products' => $archivedProducts,
                'low_stock_count' => $lowStockProducts->count(),
                'out_of_stock_count' => $outOfStockCount,
                'total_services' => $totalServices,
                'total_quotes' => $totalQuotes,
                'total_users' => $totalUsers,
            ],
            'alerts' => $alerts,
            'quick_links' => $quickLinks,
            'recent_orders' => $recentOrders,
            'server_time' => now()->toIso8601String(),
        ]);
    }
}
