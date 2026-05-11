<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', auth()->id())
            ->with('product.category')
            ->latest()
            ->get();

        return Inertia::render('Wishlist/Index', [
            'wishlistItems' => $wishlistItems
        ]);
    }

    public function toggle(Product $product)
    {
        $user = auth()->user();
        
        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return back()->with('success', 'Removed from wishlist.');
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Added to wishlist.');
    }
}
