<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiAdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected string $adminToken;

    protected function setUp(): void
    {
        parent::setUp();

        // Create or find admin user
        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@laptech.com'],
            [
                'name' => 'Admin Tester',
                'password' => Hash::make('secret123'),
                'is_admin' => true,
            ]
        );
        $this->admin->is_admin = true;
        $this->admin->save();

        // Create normal non-admin customer
        $this->customer = User::firstOrCreate(
            ['email' => 'customer_test@laptech.com'],
            [
                'name' => 'Customer Tester',
                'password' => Hash::make('secret123'),
                'is_admin' => false,
            ]
        );
        $this->customer->is_admin = false;
        $this->customer->save();

        $this->adminToken = $this->admin->createToken('test-token')->plainTextToken;
    }

    public function test_admin_can_login_via_api(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'admin_test@laptech.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email', 'is_admin'],
            ]);
    }

    public function test_non_admin_cannot_login_via_api(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'customer_test@laptech.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Access restricted to administrators. Mobile app is only accessible to authorized admin personnel.',
            ]);
    }

    public function test_invalid_credentials_return_422(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'admin_test@laptech.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
    }

    public function test_dashboard_api_returns_stats_alerts_and_quicklinks(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'stats' => ['revenue', 'total_orders', 'total_products', 'low_stock_count'],
                'alerts',
                'quick_links',
                'recent_orders',
            ]);
    }

    public function test_product_crud_and_archive_endpoints(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'test-laptops'],
            ['name' => 'Test Laptops', 'description' => 'Test']
        );

        // 1. Create Product
        $createRes = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson('/api/v1/products', [
                'name' => 'Test ThinkPad X1',
                'description' => 'Flagship test laptop',
                'price' => 150000,
                'stock' => 8,
                'category_id' => $category->id,
                'status' => 'Brand new',
                'is_featured' => true,
                'specifications' => ['CPU' => 'Intel Core Ultra 7', 'RAM' => '32GB'],
            ]);

        $createRes->assertStatus(201);
        $productId = $createRes->json('product.id');
        $this->assertNotNull($productId);

        // 2. Fetch List
        $listRes = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/products?search=ThinkPad');
        $listRes->assertStatus(200);

        // 3. Update Product
        $updateRes = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->putJson("/api/v1/products/{$productId}", [
                'name' => 'Test ThinkPad X1 Gen 2',
                'price' => 160000,
            ]);
        $updateRes->assertStatus(200)
            ->assertJsonPath('product.name', 'Test ThinkPad X1 Gen 2');

        // 4. Archive Product
        $archiveRes = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson("/api/v1/products/{$productId}/archive");
        $archiveRes->assertStatus(200);
        $this->assertTrue(Product::find($productId)->is_archived);

        // 5. Restore Product
        $restoreRes = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson("/api/v1/products/{$productId}/restore");
        $restoreRes->assertStatus(200);
        $this->assertFalse(Product::find($productId)->is_archived);

        // 6. Delete Product
        $deleteRes = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->deleteJson("/api/v1/products/{$productId}");
        $deleteRes->assertStatus(200);
    }

    public function test_order_status_update_endpoint(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'items' => [['name' => 'Keyboard', 'price' => 2000, 'quantity' => 1]],
            'total' => 2000,
            'status' => 'pending',
            'shipping_address' => ['full_name' => 'Jane Doe', 'city' => 'Nairobi'],
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->patchJson("/api/v1/orders/{$order->id}/status", [
                'status' => 'shipped',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('order.status', 'shipped');

        $this->assertEquals('shipped', $order->fresh()->status);
        $order->delete();
    }

    public function test_service_crud_endpoints(): void
    {
        $category = ServiceCategory::firstOrCreate(
            ['slug' => 'test-infra'],
            ['name' => 'Infrastructure Support']
        );

        // Create service
        $res = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson('/api/v1/services', [
                'name' => 'Server Maintenance Plus',
                'description' => '24/7 server monitoring and SLA guarantee',
                'service_category_id' => $category->id,
                'features' => ['24/7 Monitoring', 'Hardware Replacement'],
                'icon' => 'server',
                'is_featured' => true,
            ]);

        $res->assertStatus(201);
        $serviceId = $res->json('service.id');

        // Update service
        $upd = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->putJson("/api/v1/services/{$serviceId}", [
                'name' => 'Server Maintenance Elite',
            ]);
        $upd->assertStatus(200);

        // Delete service
        $del = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->deleteJson("/api/v1/services/{$serviceId}");
        $del->assertStatus(200);
    }

    public function test_settings_api_returns_system_info(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/settings');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'app_name',
                'version',
                'api_version',
                'php_version',
                'laravel_version',
                'database_connected',
            ]);
    }
}
