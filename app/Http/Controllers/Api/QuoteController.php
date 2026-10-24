<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    /**
     * Display a listing of quotes with status breakdown.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Quote::with('service')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%");
            });
        }

        $quotes = $query->get();

        $stats = [
            'total' => Quote::count(),
            'pending' => Quote::where('status', 'pending')->count(),
            'responded' => Quote::where('status', 'responded')->count(),
            'closed' => Quote::where('status', 'closed')->count(),
        ];

        return response()->json([
            'quotes' => $quotes,
            'stats' => $stats,
        ]);
    }

    /**
     * Display the specified quote.
     */
    public function show($id): JsonResponse
    {
        $quote = Quote::with('service')->findOrFail($id);

        return response()->json([
            'quote' => $quote,
        ]);
    }

    /**
     * Update quote status (pending, responded, closed).
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,responded,closed',
        ]);

        $quote = Quote::with('service')->findOrFail($id);
        $quote->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Quote status updated successfully.',
            'quote' => $quote,
        ]);
    }

    /**
     * Remove the specified quote.
     */
    public function destroy($id): JsonResponse
    {
        $quote = Quote::findOrFail($id);
        $quote->delete();

        return response()->json([
            'message' => 'Quote inquiry removed successfully.',
        ]);
    }
}
