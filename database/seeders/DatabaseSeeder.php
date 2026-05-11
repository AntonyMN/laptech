<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@laptech.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Product Categories
        $categories = [
            ['name' => 'Laptops', 'slug' => 'laptops', 'description' => 'High-performance laptops.'],
            ['name' => 'Components', 'slug' => 'components', 'description' => 'Internal hardware.'],
            ['name' => 'Peripherals', 'slug' => 'peripherals', 'description' => 'Essential accessories.'],
            ['name' => 'Servers', 'slug' => 'servers', 'description' => 'Enterprise servers.'],
            ['name' => 'Security Equipment', 'slug' => 'security-equipment', 'description' => 'CCTV and access control.'],
            ['name' => 'Software Licenses', 'slug' => 'software-licenses', 'description' => 'Professional licenses.'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Service Categories
        $serviceCategories = [
            ['name' => 'Infrastructure', 'slug' => 'infrastructure', 'description' => 'Network architecture.'],
            ['name' => 'Cybersecurity', 'slug' => 'cybersecurity', 'description' => 'Digital protection.'],
            ['name' => 'Managed Services', 'slug' => 'managed-services', 'description' => 'IT support.'],
        ];

        foreach ($serviceCategories as $cat) {
            ServiceCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Products
        $laptopCat = Category::where('slug', 'laptops')->first();
        if ($laptopCat) {
            Product::updateOrCreate(
                ['slug' => 'precision-x1-workstation'],
                [
                    'category_id' => $laptopCat->id,
                    'name' => 'Precision X1 Workstation',
                    'description' => 'Ultimate power for technical architects.',
                    'price' => 245000,
                    'stock' => 15,
                    'is_featured' => true,
                    'image' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&q=80&w=1200',
                    'specifications' => ['CPU' => 'i9-13900HK', 'RAM' => '64GB']
                ]
            );
        }

        // Services
        $infraServCat = ServiceCategory::where('slug', 'infrastructure')->first();
        if ($infraServCat) {
            Service::updateOrCreate(
                ['slug' => 'datacenter-architecture'],
                [
                    'service_category_id' => $infraServCat->id,
                    'name' => 'Datacenter Architecture',
                    'description' => 'Precision engineering.',
                    'icon' => 'fas fa-server',
                    'features' => [['title' => 'Uptime', 'description' => '99.99%']]
                ]
            );
        }

        // Blog Categories
        $blogCategories = [
            ['name' => 'Infrastructure', 'slug' => 'infrastructure', 'description' => 'Server architecture.'],
            ['name' => 'Cybersecurity', 'slug' => 'cybersecurity', 'description' => 'Digital protection.'],
        ];

        foreach ($blogCategories as $cat) {
            BlogCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Demo Blog Post
        $infraCat = BlogCategory::where('slug', 'infrastructure')->first();
        if ($infraCat) {
            BlogPost::updateOrCreate(
                ['slug' => 'future-of-enterprise-infrastructure-kenya'],
                [
                    'blog_category_id' => $infraCat->id,
                    'title' => 'The Future of Enterprise Infrastructure in Kenya',
                    'excerpt' => 'Deep dive into tech trends.',
                    'content' => '<h1>Architecting the Next Decade.</h1><p>The Kenyan enterprise landscape is shifting...</p>',
                    'featured_image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc4b?auto=format&fit=crop&q=80&w=1200',
                    'is_published' => true,
                    'published_at' => now(),
                ]
            );
        }
    }
}
