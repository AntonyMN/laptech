<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class NewProductsSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'name' => 'HP EliteBook 830 G8',
                'slug' => 'hp-elitebook-830-g8',
                'description' => 'High-performance Ex-UK business laptop featuring an Intel Core i5 11th Gen processor, sleek aluminum chassis, and lightweight mobility.',
                'price' => 39500,
                'stock' => 5,
                'is_featured' => true,
                'status' => 'Ex-UK',
                'image' => '/images/hp_830_g8.jpg',
                'specifications' => [
                    'CPU' => 'Intel Core i5 11th Gen',
                    'RAM' => '8GB',
                    'Storage' => '256GB SSD',
                    'Display' => '13.3" FHD',
                    'Condition' => 'Ex-UK'
                ]
            ],
            [
                'category_id' => 1,
                'name' => 'HP OmniBook Ultra Flip',
                'slug' => 'hp-omnibook-ultra-flip',
                'description' => 'Brand new next-gen AI convertible laptop with stunning 3K OLED touch display, Core Ultra 7 processor, and versatile x360 hinge.',
                'price' => 182000,
                'stock' => 3,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/hp_omnibook_ultra_flip.png',
                'specifications' => [
                    'CPU' => 'Intel Core Ultra 7',
                    'RAM' => '16GB',
                    'Storage' => '1TB SSD',
                    'Display' => '13.3" 3K OLED 120Hz Touch',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => 1,
                'name' => 'HP OmniBook 5 Flip 14',
                'slug' => 'hp-omnibook-5-flip-14',
                'description' => 'Sleek, brand new 2-in-1 convertible laptop powered by Core 7 processor, beautiful 2K touch screen, and x360 versatility.',
                'price' => 118000,
                'stock' => 3,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/hp_omnibook_5_flip.png',
                'specifications' => [
                    'CPU' => 'Intel Core 7-150U',
                    'RAM' => '16GB',
                    'Storage' => '512GB SSD',
                    'Display' => '14" 2K Touch x360',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => 1,
                'name' => 'HP EliteBook x360 1030 G7',
                'slug' => 'hp-elitebook-x360-1030-g7',
                'description' => 'Premium certified refurbished convertible business laptop with a 13.3” touchscreen, Core i5 10th Gen, and long battery life.',
                'price' => 57000,
                'stock' => 4,
                'is_featured' => true,
                'status' => 'Certified Refurbished',
                'image' => '/images/hp_elitebook_1030_g7.png',
                'specifications' => [
                    'CPU' => 'Intel Core i5 10th Gen',
                    'RAM' => '16GB',
                    'Storage' => '512GB SSD',
                    'Display' => '13.3" Touchscreen x360',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => 1,
                'name' => 'Dell Latitude 5400 Touchscreen',
                'slug' => 'dell-latitude-5400-touchscreen',
                'description' => 'High-durability Ex-UK business laptop with Core i7 8th Gen, robust chassis, and a highly responsive 14" touchscreen.',
                'price' => 37000,
                'stock' => 5,
                'is_featured' => true,
                'status' => 'Ex-UK',
                'image' => '/images/dell_latitude_5400.png',
                'specifications' => [
                    'CPU' => 'Intel Core i7 8th Gen',
                    'RAM' => '8GB',
                    'Storage' => '256GB SSD',
                    'Display' => '14" Touchscreen',
                    'Condition' => 'Ex-UK'
                ]
            ],
            [
                'category_id' => 1,
                'name' => 'Lenovo LOQ 15IRX10 Gaming',
                'slug' => 'lenovo-loq-15irx10-gaming',
                'description' => 'Brand new, high-action gaming laptop powered by i7-13650HX, 16GB RAM, and next-gen NVIDIA RTX 5050 graphics.',
                'price' => 164000,
                'stock' => 3,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/lenovo_loq_15.png',
                'specifications' => [
                    'CPU' => 'Intel Core i7-13650HX',
                    'RAM' => '16GB',
                    'Storage' => '512GB SSD',
                    'GPU' => 'NVIDIA RTX 5050 8GB',
                    'Display' => '15.6" FHD IPS',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => 1,
                'name' => 'HP Victus 15-fa2309TX Gaming',
                'slug' => 'hp-victus-15-fa2309tx-gaming',
                'description' => 'High-performance brand new gaming laptop featuring a massive 24GB DDR5 RAM, RTX 5050, and 144Hz high-refresh display.',
                'price' => 168000,
                'stock' => 3,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/hp_victus_15.png',
                'specifications' => [
                    'CPU' => 'Intel Core i7-13620H',
                    'RAM' => '24GB DDR5',
                    'Storage' => '1TB SSD',
                    'GPU' => 'NVIDIA RTX 5050 8GB',
                    'Display' => '15.6" FHD 144Hz',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => 1,
                'name' => 'HP Omen 16-am0073dx Gaming',
                'slug' => 'hp-omen-16-am0073dx-gaming',
                'description' => 'Ultra-premium brand new gaming laptop with next-gen Core Ultra 7 255H, RTX 5060 8GB graphics, and a brilliant 16” 2K 144Hz VRR Display.',
                'price' => 185000,
                'stock' => 2,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/hp_omen_16.png',
                'specifications' => [
                    'CPU' => 'Intel Core Ultra 7 255H',
                    'RAM' => '16GB DDR5',
                    'Storage' => '1TB SSD',
                    'GPU' => 'NVIDIA RTX 5060 8GB',
                    'Display' => '16" 2K 144Hz VRR',
                    'Condition' => 'Brand New'
                ]
            ]
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(['slug' => $prod['slug']], $prod);
        }
    }
}
