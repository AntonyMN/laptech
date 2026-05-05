<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@laptech.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);


        // Categories
        $categories = [
            ['name' => 'Laptops', 'slug' => 'laptops', 'description' => 'High-performance laptops for pros.'],
            ['name' => 'Desktops', 'slug' => 'desktops', 'description' => 'Powerful workstations.'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Tech essentials.'],
            ['name' => 'Networking', 'slug' => 'networking', 'description' => 'Enterprise solutions.'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::create($cat);
        }

        $laptopCat = \App\Models\Category::where('slug', 'laptops')->first();

        // Products
        $products = [
            [
                'category_id' => $laptopCat->id,
                'name' => 'X-Pro Ultra 16',
                'slug' => 'x-pro-ultra-16',
                'description' => 'The ultimate machine for developers and creators.',
                'price' => 345000,
                'stock' => 15,
                'is_featured' => true,
                'specifications' => ['CPU' => 'M3 Max', 'RAM' => '64GB', 'SSD' => '2TB'],
            ],
            [
                'category_id' => $laptopCat->id,
                'name' => 'Stealth Blade G5',
                'slug' => 'stealth-blade-g5',
                'description' => 'Ultra-thin, ultra-fast gaming and productivity.',
                'price' => 210000,
                'stock' => 10,
                'is_featured' => true,
                'specifications' => ['GPU' => 'RTX 4080', 'RAM' => '32GB', 'Screen' => '240Hz'],
            ],

        ];

        foreach ($products as $prod) {
            \App\Models\Product::create($prod);
        }

        // Services
        $services = [
            [
                'name' => 'IT Consultation',
                'slug' => 'it-consultation',
                'description' => 'Expert advice for your business infrastructure.',
                'icon' => 'fas fa-lightbulb',
                'is_featured' => true,
            ],
            [
                'name' => 'Network Installation',
                'slug' => 'network-installation',
                'description' => 'Seamless networking solutions for modern offices.',
                'icon' => 'fas fa-network-wired',
                'is_featured' => true,
            ],
            [
                'name' => 'Custom Build Workstations',
                'slug' => 'custom-builds',
                'description' => 'Bespoke high-performance hardware tailored to your needs.',
                'icon' => 'fas fa-tools',
                'is_featured' => true,
            ],
        ];

        foreach ($services as $serv) {
            \App\Models\Service::create($serv);
        }
    }
}

