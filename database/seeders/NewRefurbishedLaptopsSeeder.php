<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class NewRefurbishedLaptopsSeeder extends Seeder
{
    public function run(): void
    {
        $laptops = [
            [
                'category_id' => 1, // Laptops
                'name' => 'HP EliteBook 830 G8 (Refurbished)',
                'slug' => 'hp-elitebook-830-g8-refurbished',
                'description' => 'Premium certified refurbished business laptop featuring an Intel Core i5 11th Gen processor, high-durability chassis, and sleek lightweight design.',
                'price' => 43000,
                'stock' => 8,
                'is_featured' => true,
                'status' => 'Certified Refurbished',
                'image' => '/images/hp_830_g8.jpg',
                'specifications' => [
                    'CPU' => 'Intel Core i5 11th Gen',
                    'RAM' => '8GB',
                    'Storage' => '256GB SSD',
                    'Display' => '13.3" FHD Anti-Glare',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => 1, // Laptops
                'name' => 'HP ProBook Aero 635 G7',
                'slug' => 'hp-probook-aero-635-g7-refurbished',
                'description' => 'Ultra-lightweight certified refurbished HP business laptop powered by AMD Ryzen 5 with premium silver finish and stellar performance.',
                'price' => 40500,
                'stock' => 5,
                'is_featured' => true,
                'status' => 'Certified Refurbished',
                'image' => '/images/hp_elitebook_1030_g7.png',
                'specifications' => [
                    'CPU' => 'AMD Ryzen 5 4500U',
                    'RAM' => '8GB',
                    'Storage' => '256GB SSD',
                    'Display' => '13.3" FHD IPS Silver',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => 1, // Laptops
                'name' => 'HP ZBook Firefly 14 G9 Touch',
                'slug' => 'hp-zbook-firefly-14-g9-touch-refurbished',
                'description' => 'Workstation-class professional laptop with a gorgeous touchscreen, Intel Core i7 12th Gen processor, and high-capacity processing.',
                'price' => 78000,
                'stock' => 4,
                'is_featured' => true,
                'status' => 'Certified Refurbished',
                'image' => '/images/hp_omnibook_5_flip.png',
                'specifications' => [
                    'CPU' => 'Intel Core i7 12th Gen',
                    'RAM' => '16GB',
                    'Storage' => '512GB SSD',
                    'Display' => '14" Touchscreen',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => 1, // Laptops
                'name' => 'Lenovo ThinkPad T490s',
                'slug' => 'lenovo-thinkpad-t490s-refurbished',
                'description' => 'Slim, sleek carbon black legendary business notebook featuring high processing speeds and military-grade durability.',
                'price' => 41000,
                'stock' => 10,
                'is_featured' => true,
                'status' => 'Certified Refurbished',
                'image' => '/images/lenovo_loq_15.png',
                'specifications' => [
                    'CPU' => 'Intel Core i7 8th Gen',
                    'RAM' => '16GB',
                    'Storage' => '256GB SSD',
                    'Display' => '14" FHD IPS Matte Black',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => 1, // Laptops
                'name' => 'Lenovo ThinkPad X13 Gen 1',
                'slug' => 'lenovo-thinkpad-x13-gen-1-refurbished',
                'description' => 'Highly compact and modern certified refurbished ThinkPad powered by i5 10th Gen, designed for ultimate professional mobility.',
                'price' => 42000,
                'stock' => 6,
                'is_featured' => true,
                'status' => 'Certified Refurbished',
                'image' => '/images/lenovo_loq_15.png',
                'specifications' => [
                    'CPU' => 'Intel Core i5 10th Gen',
                    'RAM' => '16GB',
                    'Storage' => '256GB SSD',
                    'Display' => '13.3" FHD Compact',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => 1, // Laptops
                'name' => 'Lenovo IdeaPad Miix 520 Detachable',
                'slug' => 'lenovo-ideapad-miix-520-detachable-refurbished',
                'description' => 'Certified refurbished detachable 2-in-1 slate notebook with high responsiveness, ideal for flexible study and creative design.',
                'price' => 41500,
                'stock' => 5,
                'is_featured' => true,
                'status' => 'Certified Refurbished',
                'image' => '/images/hp_omnibook_ultra_flip.png',
                'specifications' => [
                    'CPU' => 'Intel Core i5 8th Gen',
                    'RAM' => '8GB',
                    'Storage' => '256GB SSD',
                    'Display' => '12.2" Touch Detachable Slate',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => 1, // Laptops
                'name' => 'Dell Latitude 5400 Touch',
                'slug' => 'dell-latitude-5400-touch-refurbished',
                'description' => 'Highly responsive 14" certified refurbished touchscreen business laptop powered by a robust Core i7 8th Gen processor.',
                'price' => 39000,
                'stock' => 12,
                'is_featured' => true,
                'status' => 'Certified Refurbished',
                'image' => '/images/dell_latitude_5400.png',
                'specifications' => [
                    'CPU' => 'Intel Core i7 8th Gen',
                    'RAM' => '8GB',
                    'Storage' => '256GB SSD',
                    'Display' => '14" Touchscreen',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => 1, // Laptops
                'name' => 'Dell Latitude 7410 2-in-1 Touch x360',
                'slug' => 'dell-latitude-7410-2-in-1-touch-x360-refurbished',
                'description' => 'Top-tier enterprise 2-in-1 convertible laptop featuring an incredible 32GB RAM capacity and Core i7 10th Gen processing power.',
                'price' => 55000,
                'stock' => 4,
                'is_featured' => true,
                'status' => 'Certified Refurbished',
                'image' => '/images/dell_latitude_5400.png',
                'specifications' => [
                    'CPU' => 'Intel Core i7 10th Gen',
                    'RAM' => '32GB',
                    'Storage' => '512GB SSD',
                    'Display' => '14" Touch x360 Convertible',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => 1, // Laptops
                'name' => 'MacBook Pro A1502 (2015) 13” Retina',
                'slug' => 'macbook-pro-a1502-2015-retina',
                'description' => 'Super clean condition classic MacBook Pro with brilliant 13" Retina display, Intel Core i5 processor, and responsive solid-state storage.',
                'price' => 35000,
                'stock' => 5,
                'is_featured' => true,
                'status' => 'Certified Refurbished',
                'image' => '/images/macbook_pro_a1502.png',
                'specifications' => [
                    'CPU' => 'Intel Core i5',
                    'RAM' => '8GB',
                    'Storage' => '256GB SSD',
                    'Display' => '13" Retina Display',
                    'Color' => 'Original Silver',
                    'Condition' => 'Super Clean'
                ]
            ],
            [
                'category_id' => 1, // Laptops
                'name' => 'MacBook Pro 14-inch (2021) M1 Pro',
                'slug' => 'macbook-pro-14-inch-2021-m1-pro',
                'description' => 'Ultra-powerful professional laptop featuring the legendary Apple M1 Pro chip, 16GB unified memory, and a gorgeous Liquid Retina XDR screen.',
                'price' => 136000,
                'stock' => 3,
                'is_featured' => true,
                'status' => 'Certified Refurbished',
                'image' => '/images/macbook_pro_14.png',
                'specifications' => [
                    'CPU' => 'Apple M1 Pro Chip',
                    'RAM' => '16GB Unified Memory',
                    'Storage' => '512GB SSD',
                    'Display' => '14” Liquid Retina XDR',
                    'Condition' => 'Super Clean'
                ]
            ]
        ];

        foreach ($laptops as $prod) {
            Product::updateOrCreate(['slug' => $prod['slug']], $prod);
        }
    }
}
