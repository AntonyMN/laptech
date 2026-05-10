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
                'name' => 'IT Consultancy',
                'slug' => 'it-consultancy',
                'description' => 'Professional technology advisory services to align your IT infrastructure with your business goals and future growth.',
                'icon' => 'fas fa-lightbulb',
                'is_featured' => true,
            ],
            [
                'name' => 'Hardware Sales & Repair',
                'slug' => 'hardware-sales-repair',
                'description' => 'Premium laptop and desktop sourcing combined with expert diagnostic and repair services for all major brands.',
                'icon' => 'fas fa-laptop-medical',
                'is_featured' => true,
            ],
            [
                'name' => 'CCTV & Networking',
                'slug' => 'cctv-networking',
                'description' => 'Enterprise-grade security surveillance systems and robust networking infrastructure for seamless, secure connectivity.',
                'icon' => 'fas fa-video',
                'is_featured' => true,
            ],
            [
                'name' => 'Server Solutions & Upgrades',
                'slug' => 'server-solutions',
                'description' => 'High-performance server deployment, proactive management, and strategic hardware upgrades for data-intensive operations.',
                'icon' => 'fas fa-server',
                'is_featured' => true,
            ],
            [
                'name' => 'Software Sales & Services',
                'slug' => 'software-solutions',
                'description' => 'Genuine software provisioning, installation, and enterprise licensing management tailored to your specific requirements.',
                'icon' => 'fas fa-code',
                'is_featured' => true,
            ],
            [
                'name' => 'Gaming & Rendering Machines',
                'slug' => 'high-performance-computing',
                'description' => 'Custom-engineered workstations designed specifically for elite gaming, 3D rendering, and professional-grade video production.',
                'icon' => 'fas fa-microchip',
                'is_featured' => true,
            ],
        ];

        foreach ($services as $serv) {
            \App\Models\Service::create($serv);
        }
    }
}

