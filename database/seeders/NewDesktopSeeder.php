<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class NewDesktopSeeder extends Seeder
{
    public function run(): void
    {
        // Dynamically resolve Desktops category ID by slug
        $desktopCat = Category::where('slug', 'desktops')->first();
        $desktopCatId = $desktopCat ? $desktopCat->id : 2;

        Product::updateOrCreate(
            ['slug' => 'hp-elitedesk-800-g1-usdt'],
            [
                'category_id' => $desktopCatId,
                'name' => 'HP EliteDesk 800 G1 USDT',
                'slug' => 'hp-elitedesk-800-g1-usdt',
                'description' => 'Ultra-slim, space-saving certified refurbished business desktop PC powered by an Intel Core i5 4th Gen processor. Ideal for office work, study, and retail front-desk setups.',
                'price' => 16500,
                'stock' => 10,
                'is_featured' => true,
                'status' => 'Certified Refurbished',
                'image' => '/images/hp_elitedesk_800_g1.jpg',
                'specifications' => [
                    'CPU' => 'Intel Core i5 4th Gen',
                    'RAM' => '8GB',
                    'Storage' => '500GB HDD',
                    'Form Factor' => 'Ultra Slim Desktop (USDT)',
                    'Condition' => 'Certified Refurbished'
                ]
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'asus-s5-s501mer-gaming-desktop'],
            [
                'category_id' => $desktopCatId,
                'name' => 'ASUS S5 S501MER Monster Gaming Rig',
                'slug' => 'asus-s5-s501mer-gaming-desktop',
                'description' => 'Uncompromising gaming and editing desktop powerhouse featuring the latest 14th Gen processor, high-speed DDR5 memory, and a dedicated GeForce RTX 4060 graphics card.',
                'price' => 137500,
                'stock' => 2,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/asus_s501mer.png',
                'specifications' => [
                    'CPU' => 'Intel Core i5-14400F (14th Gen)',
                    'RAM' => '16GB DDR5 5600MHz',
                    'Storage' => '1TB NVMe SSD',
                    'GPU' => 'NVIDIA GeForce RTX 4060 8GB',
                    'Condition' => 'Brand New',
                    'Features' => 'Custom RGB lighting, high-airflow chassis'
                ]
            ]
        );
    }
}
