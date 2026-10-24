<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Generate an executive business analytics report.
     */
    public function summary(Request $request): JsonResponse
    {
        // Financial & Orders
        $totalRevenue = (float) Order::where('status', 'delivered')->sum('total');
        $pendingRevenue = (float) Order::where('status', 'pending')->sum('total');
        $totalOrders = Order::count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        $avgOrderValue = $totalOrders > 0 ? round((float) Order::avg('total'), 2) : 0.0;

        // Inventory Analytics
        $totalProducts = Product::where('is_archived', false)->count();
        $archivedProducts = Product::where('is_archived', true)->count();
        $lowStock = Product::where('is_archived', false)->where('stock', '>', 0)->where('stock', '<=', 5)->count();
        $outOfStock = Product::where('is_archived', false)->where('stock', '<=', 0)->count();
        $estimatedInventoryValue = (float) Product::where('is_archived', false)->sum(DB::raw('price * stock'));

        // Quotes Pipeline Analytics
        $totalQuotes = Quote::count();
        $pendingQuotes = Quote::where('status', 'pending')->count();
        $respondedQuotes = Quote::where('status', 'responded')->count();
        $closedQuotes = Quote::where('status', 'closed')->count();
        $quoteResponseRate = $totalQuotes > 0 ? round((($respondedQuotes + $closedQuotes) / $totalQuotes) * 100, 1) : 100.0;

        // Content & Blog
        $totalPosts = BlogPost::count();
        $publishedPosts = BlogPost::where('is_published', true)->count();
        $draftPosts = BlogPost::where('is_published', false)->count();

        // System & Users
        $totalServices = Service::count();
        $totalUsers = User::count();
        $adminUsers = User::where('is_admin', true)->count();

        return response()->json([
            'generated_at' => now()->toIso8601String(),
            'financial' => [
                'total_revenue' => $totalRevenue,
                'pending_revenue' => $pendingRevenue,
                'average_order_value' => $avgOrderValue,
                'currency' => 'KES',
            ],
            'orders' => [
                'total' => $totalOrders,
                'delivered' => $deliveredOrders,
                'pending' => $pendingOrders,
                'processing' => $processingOrders,
                'shipped' => $shippedOrders,
                'cancelled' => $cancelledOrders,
                'fulfillment_rate' => $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 1) : 0.0,
            ],
            'inventory' => [
                'active_products' => $totalProducts,
                'archived_products' => $archivedProducts,
                'low_stock' => $lowStock,
                'out_of_stock' => $outOfStock,
                'estimated_value' => $estimatedInventoryValue,
            ],
            'quotes' => [
                'total' => $totalQuotes,
                'pending' => $pendingQuotes,
                'responded' => $respondedQuotes,
                'closed' => $closedQuotes,
                'response_rate' => $quoteResponseRate,
            ],
            'content' => [
                'total_posts' => $totalPosts,
                'published' => $publishedPosts,
                'drafts' => $draftPosts,
                'total_services' => $totalServices,
            ],
            'users' => [
                'total' => $totalUsers,
                'admins' => $adminUsers,
            ],
        ]);
    }
}
