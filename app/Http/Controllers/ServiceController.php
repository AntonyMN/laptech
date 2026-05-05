<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Service;
use App\Models\ServiceCategory;
use Inertia\Inertia;


class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query()->with('category');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->where('service_category_id', $request->category);
        }

        return Inertia::render('Services/Index', [
            'services' => $query->get(),
            'categories' => ServiceCategory::all(),
            'filters' => $request->only(['search', 'category']),
        ]);
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();
        
        return Inertia::render('Services/Show', [
            'service' => $service,
        ]);
    }
}

