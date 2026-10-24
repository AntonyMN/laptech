<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * Get system information, version, and platform configuration.
     */
    public function index(Request $request): JsonResponse
    {
        $dbConnected = true;
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbConnected = false;
        }

        return response()->json([
            'app_name' => config('app.name', 'Laptech Systems'),
            'app_env' => config('app.env', 'production'),
            'version' => '1.0.0',
            'api_version' => 'v1',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database_connected' => $dbConnected,
            'server_time' => now()->toIso8601String(),
            'server_timezone' => config('app.timezone', 'UTC'),
        ]);
    }

    /**
     * Clear application cache.
     */
    public function clearCache(Request $request): JsonResponse
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');

        return response()->json([
            'message' => 'System cache cleared successfully.',
        ]);
    }
}
