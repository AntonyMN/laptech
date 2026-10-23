<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GamingLaptopsSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'laptops')->first();

        if (!$category) {
            return;
        }

        $products = [
            // HP OMEN SERIES
            ['name' => 'HP Omen 15-dh', 'price' => 116500, 'image' => '/images/hp_omen_15_dh_1788520115904.jpg', 'description' => 'Intel Core i7-9750H | 16GB RAM | 512GB SSD | RTX 2070 8GB', 'specifications' => ['Processor' => 'Intel Core i7-9750H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 2070 8GB']],
            ['name' => 'HP Omen 15-ek (RTX 3060)', 'price' => 124000, 'image' => '/images/hp_omen_15_ek_1788520127666.jpg', 'description' => 'Intel Core i7-10750H | 16GB RAM | 512GB SSD | RTX 3060 6GB', 'specifications' => ['Processor' => 'Intel Core i7-10750H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 3060 6GB']],
            ['name' => 'HP Omen 15-ek (RTX 2070)', 'price' => 126000, 'image' => '/images/hp_omen_15_ek_1788520127666.jpg', 'description' => 'Intel Core i7-10750H | 16GB RAM | 512GB SSD | RTX 2070 8GB', 'specifications' => ['Processor' => 'Intel Core i7-10750H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 2070 8GB']],
            ['name' => 'HP Omen 16-b (RTX 3050)', 'price' => 120000, 'image' => '/images/hp_omen_16_b_1788520140973.jpg', 'description' => 'Intel Core i7-11800H | 16GB RAM | 512GB SSD | RTX 3050 4GB', 'specifications' => ['Processor' => 'Intel Core i7-11800H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 3050 4GB']],
            ['name' => 'HP Omen 16-k', 'price' => 135500, 'image' => '/images/hp_omen_16_k_1788520152855.jpg', 'description' => 'Intel Core i7-12700H | 16GB RAM | 512GB SSD | RTX 3060 6GB', 'specifications' => ['Processor' => 'Intel Core i7-12700H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 3060 6GB']],
            ['name' => 'HP Omen 16-b (RTX 3070)', 'price' => 148500, 'image' => '/images/hp_omen_16_b_1788520140973.jpg', 'description' => 'Intel Core i7-12700H | 16GB RAM | 512GB SSD | RTX 3070 8GB', 'specifications' => ['Processor' => 'Intel Core i7-12700H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 3070 8GB']],
            ['name' => 'HP Omen 16-wf (i7)', 'price' => 165000, 'image' => '/images/hp_omen_16_wf_1788520165330.jpg', 'description' => 'Intel Core i7-14650HX | 16GB RAM | 512GB SSD | RTX 4060 8GB | 16” 2K Display', 'specifications' => ['Processor' => 'Intel Core i7-14650HX', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 4060 8GB', 'Display' => '16” 2K Display']],
            ['name' => 'HP Omen 16-wf (i9)', 'price' => 175000, 'image' => '/images/hp_omen_16_wf_1788520165330.jpg', 'description' => 'Intel Core i9-13900H | 16GB RAM | 1TB SSD | RTX 4060 8GB | 16” 2K Display', 'specifications' => ['Processor' => 'Intel Core i9-13900H', 'RAM' => '16GB', 'Storage' => '1TB SSD', 'Graphics' => 'RTX 4060 8GB', 'Display' => '16” 2K Display']],

            // ASUS ROG & TUF SERIES
            ['name' => 'ASUS TUF Gaming A15 FA506 (Ryzen 7 4800H)', 'price' => 117300, 'image' => '/images/asus_tuf_a15_1788520263102.jpg', 'description' => 'AMD Ryzen 7 4800H | 16GB RAM | 512GB SSD | RTX 3050 4GB', 'specifications' => ['Processor' => 'AMD Ryzen 7 4800H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 3050 4GB']],
            ['name' => 'ASUS TUF Gaming A15 FA506 (Ryzen 7 5800H)', 'price' => 120500, 'image' => '/images/asus_tuf_a15_1788520263102.jpg', 'description' => 'AMD Ryzen 7 5800H | 16GB RAM | 512GB SSD | RTX 3050 Ti 4GB', 'specifications' => ['Processor' => 'AMD Ryzen 7 5800H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 3050 Ti 4GB']],
            ['name' => 'ASUS Zephyrus S GX502', 'price' => 118500, 'image' => '/images/asus_zephyrus_s_1788520287223.jpg', 'description' => 'Intel Core i7-9750H | 16GB RAM | 512GB SSD | RTX 2070 8GB', 'specifications' => ['Processor' => 'Intel Core i7-9750H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 2070 8GB']],
            ['name' => 'ASUS ROG Strix G512LV', 'price' => 122000, 'image' => '/images/asus_rog_strix_g512lv_1788520298363.jpg', 'description' => 'Intel Core i7-10875H | 16GB RAM | 512GB SSD | RTX 2060 6GB', 'specifications' => ['Processor' => 'Intel Core i7-10875H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 2060 6GB']],
            ['name' => 'ASUS ROG Zephyrus M15 GU502LW', 'price' => 125000, 'image' => '/images/asus_rog_zephyrus_m15_1788520310377.jpg', 'description' => 'Intel Core i7-10875H | 16GB RAM | 512GB SSD | RTX 2070 8GB | 15.6” 4K UHD Display', 'specifications' => ['Processor' => 'Intel Core i7-10875H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 2070 8GB', 'Display' => '15.6” 4K UHD Display']],
            ['name' => 'ASUS ROG Strix G531GW', 'price' => 115500, 'image' => '/images/asus_rog_strix_g531gw_1788520324108.jpg', 'description' => 'Intel Core i7-9750H | 16GB RAM | 512GB SSD | RTX 2070 8GB', 'specifications' => ['Processor' => 'Intel Core i7-9750H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 2070 8GB']],
            ['name' => 'ASUS TUF F15 FX507', 'price' => 146500, 'image' => '/images/asus_tuf_f15_1788520275089.jpg', 'description' => 'Intel Core i7-12700H | 16GB RAM | 512GB SSD | RTX 3070 8GB', 'specifications' => ['Processor' => 'Intel Core i7-12700H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 3070 8GB']],

            // DELL GAMING & WORKSTATIONS
            ['name' => 'Dell G5 5500', 'price' => 114500, 'image' => '/images/dell_g5_5500_1788520337340.jpg', 'description' => 'Intel Core i7-10750H | 16GB RAM | 512GB SSD | RTX 2060 6GB', 'specifications' => ['Processor' => 'Intel Core i7-10750H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 2060 6GB']],
            ['name' => 'Dell Precision 3580 (Touch)', 'price' => 120500, 'image' => '/images/dell_precision_3580_1788520350625.jpg', 'description' => 'Intel Core i7 13th Gen | 16GB RAM | 512GB SSD | RTX A500 4GB', 'specifications' => ['Processor' => 'Intel Core i7 13th Gen', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX A500 4GB', 'Features' => 'Touchscreen']],
            ['name' => 'Dell G15 5511', 'price' => 127500, 'image' => '/images/dell_g15_5511_1788618819732.jpg', 'description' => 'Intel Core i7-11800H | 16GB RAM | 512GB SSD | RTX 3060 6GB', 'specifications' => ['Processor' => 'Intel Core i7-11800H', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Graphics' => 'RTX 3060 6GB']],
            ['name' => 'Dell G15 5530', 'price' => 164000, 'image' => '/images/dell_g15_5530_1788618830558.jpg', 'description' => 'Intel Core i7-13650H | 16GB RAM | 1TB SSD | RTX 4060 8GB', 'specifications' => ['Processor' => 'Intel Core i7-13650H', 'RAM' => '16GB', 'Storage' => '1TB SSD', 'Graphics' => 'RTX 4060 8GB']],
            ['name' => 'Dell G16 7630', 'price' => 168000, 'image' => '/images/dell_g16_7630_1788618842085.jpg', 'description' => 'Intel Core i7 - 13900HX | 16GB RAM | 1TB SSD | RTX 4060 8GB | 16” QHD+ (2K) Display', 'specifications' => ['Processor' => 'Intel Core i7 - 13900HX', 'RAM' => '16GB', 'Storage' => '1TB SSD', 'Graphics' => 'RTX 4060 8GB', 'Display' => '16” QHD+ (2K) Display']],
        ];

        foreach ($products as $productData) {
            $productData['slug'] = Str::slug($productData['name']);
            $productData['category_id'] = $category->id;
            $productData['stock'] = 10;
            $productData['is_featured'] = true;
            $productData['status'] = 'Certified Refurbished';
            
            Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }
    }
}
