<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'roles' => fn () => $request->user()?->getRoleNames() ?? [],
                'can' => fn () => $request->user()
                    ? collect(\App\Support\Permissions::ALL)
                        ->mapWithKeys(fn ($p) => [$p => $request->user()->can($p)])
                        ->all()
                    : [],
            ],
            'appName' => config('app.name', 'Laptech'),
            'appUrl' => config('app.url'),
            'navCategories' => fn () => \App\Models\Category::has('products')
                ->orderBy('name')
                ->get(['id', 'name', 'slug']),
            'canLogin' => \Illuminate\Support\Facades\Route::has('login'),
            'canRegister' => \Illuminate\Support\Facades\Route::has('register'),
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
                'generated_content' => $request->session()->get('generated_content'),
            ],
        ];
    }
}
