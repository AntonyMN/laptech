<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Quote;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    public function create()
    {
        return \Inertia\Inertia::render('Quotes/Create', [
            'services' => \App\Models\Service::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'details' => 'required|string',
        ]);

        $validated['status'] = 'pending';
        $validated['user_id'] = Auth::id();

        Quote::create($validated);

        return back()->with('success', 'Quote requested successfully.');
    }
}

