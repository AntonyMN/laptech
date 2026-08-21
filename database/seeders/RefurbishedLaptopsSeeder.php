<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RefurbishedLaptopsSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'laptops')->first();

        if (!$category) {
            return;
        }

        $products = [
            // HP
            ['name' => 'HP EliteBook 745 G6', 'price' => 40000, 'image' => '/images/hp_elitebook.png', 'description' => 'AMD Ryzen 5 3000 Series | 8GB RAM | 256GB SSD | 2GB Radeon Graphics', 'specifications' => ['Processor' => 'AMD Ryzen 5 3000 Series', 'RAM' => '8GB', 'Storage' => '256GB SSD', 'Graphics' => '2GB Radeon Graphics']],
            ['name' => 'HP EliteBook 745 G5', 'price' => 41000, 'image' => '/images/hp_elitebook.png', 'description' => 'AMD Ryzen 7 2000 Series | 8GB RAM | 256GB SSD | 1GB Radeon Graphics', 'specifications' => ['Processor' => 'AMD Ryzen 7 2000 Series', 'RAM' => '8GB', 'Storage' => '256GB SSD', 'Graphics' => '1GB Radeon Graphics']],
            ['name' => 'HP EliteBook 830 G6', 'price' => 41500, 'image' => '/images/hp_elitebook.png', 'description' => 'Intel Core i5 8th Gen | 8GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'Intel Core i5 8th Gen', 'RAM' => '8GB', 'Storage' => '256GB SSD']],
            ['name' => 'HP EliteBook 830 G8', 'price' => 47000, 'image' => '/images/hp_elitebook.png', 'description' => 'Intel Core i5 11th Gen | 8GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'Intel Core i5 11th Gen', 'RAM' => '8GB', 'Storage' => '256GB SSD']],
            ['name' => 'HP ZBook Firefly 14 G7 Touch', 'price' => 47000, 'image' => '/images/hp_zbook.png', 'description' => 'Intel Core i5 10th Gen | 8GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'Intel Core i5 10th Gen', 'RAM' => '8GB', 'Storage' => '256GB SSD', 'Features' => 'Touchscreen']],
            ['name' => 'HP Elite X2 G4 Detachable', 'price' => 48000, 'image' => '/images/hp_elite_x2.png', 'description' => 'Intel Core i5 8th Gen | 16GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'Intel Core i5 8th Gen', 'RAM' => '16GB', 'Storage' => '256GB SSD', 'Features' => 'Detachable']],
            ['name' => 'HP EliteBook 640 G8 Touch', 'price' => 48000, 'image' => '/images/hp_elitebook.png', 'description' => 'Intel Core i5 11th Gen | 8GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'Intel Core i5 11th Gen', 'RAM' => '8GB', 'Storage' => '256GB SSD', 'Features' => 'Touchscreen']],
            ['name' => 'HP EliteBook 845 G7', 'price' => 48000, 'image' => '/images/hp_elitebook.png', 'description' => 'AMD Ryzen 7 PRO 4750U | 8GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'AMD Ryzen 7 PRO 4750U', 'RAM' => '8GB', 'Storage' => '256GB SSD']],
            ['name' => 'HP ProBook 435 G7 x360', 'price' => 50500, 'image' => '/images/hp_probook_x360.png', 'description' => 'AMD Ryzen 7 4000 Series | 8GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'AMD Ryzen 7 4000 Series', 'RAM' => '8GB', 'Storage' => '256GB SSD', 'Features' => 'x360 Convertible']],
            ['name' => 'HP EliteBook 640 G8', 'price' => 50500, 'image' => '/images/hp_elitebook.png', 'description' => 'Intel Core i7 11th Gen | 8GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'Intel Core i7 11th Gen', 'RAM' => '8GB', 'Storage' => '256GB SSD']],
            ['name' => 'HP EliteBook 840 G7', 'price' => 54000, 'image' => '/images/hp_elitebook.png', 'description' => 'Intel Core i7 10th Gen | 16GB RAM | 512GB SSD', 'specifications' => ['Processor' => 'Intel Core i7 10th Gen', 'RAM' => '16GB', 'Storage' => '512GB SSD']],
            ['name' => 'HP EliteBook x360 830 G7', 'price' => 55000, 'image' => '/images/hp_elitebook_x360.png', 'description' => 'Intel Core i5 10th Gen | 16GB RAM | 512GB SSD', 'specifications' => ['Processor' => 'Intel Core i5 10th Gen', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Features' => 'x360 Convertible']],
            ['name' => 'HP EliteBook x360 830 G8', 'price' => 56500, 'image' => '/images/hp_elitebook_x360.png', 'description' => 'Intel Core i5 11th Gen | 16GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'Intel Core i5 11th Gen', 'RAM' => '16GB', 'Storage' => '256GB SSD', 'Features' => 'x360 Convertible']],
            ['name' => 'HP ZBook Firefly 14 G8', 'price' => 57500, 'image' => '/images/hp_zbook.png', 'description' => 'Intel Core i7 11th Gen | 16GB RAM | 512GB SSD', 'specifications' => ['Processor' => 'Intel Core i7 11th Gen', 'RAM' => '16GB', 'Storage' => '512GB SSD']],
            ['name' => 'HP EliteBook 840 G8 Touch', 'price' => 61000, 'image' => '/images/hp_elitebook.png', 'description' => 'Intel Core i7 11th Gen | 16GB RAM | 512GB SSD', 'specifications' => ['Processor' => 'Intel Core i7 11th Gen', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Features' => 'Touchscreen']],
            ['name' => 'HP EliteBook x360 1040 G8', 'price' => 75000, 'image' => '/images/hp_elitebook_x360.png', 'description' => 'Intel Core i7 11th Gen | 32GB RAM | 512GB SSD', 'specifications' => ['Processor' => 'Intel Core i7 11th Gen', 'RAM' => '32GB', 'Storage' => '512GB SSD', 'Features' => 'x360 Convertible']],
            ['name' => 'HP ZBook Firefly 14 G9 Touch', 'price' => 82000, 'image' => '/images/hp_zbook.png', 'description' => 'Intel Core i7 12th Gen | 16GB RAM | 512GB SSD', 'specifications' => ['Processor' => 'Intel Core i7 12th Gen', 'RAM' => '16GB', 'Storage' => '512GB SSD', 'Features' => 'Touchscreen']],
            ['name' => 'HP ZBook Firefly 14 G11 Touch', 'price' => 96500, 'image' => '/images/hp_zbook.png', 'description' => 'Intel Core Ultra 7 165H | 16GB DDR5 RAM | 512GB SSD', 'specifications' => ['Processor' => 'Intel Core Ultra 7 165H', 'RAM' => '16GB DDR5', 'Storage' => '512GB SSD', 'Features' => 'Touchscreen']],

            // Lenovo
            ['name' => 'Lenovo ThinkPad X390', 'price' => 39500, 'image' => '/images/lenovo_thinkpad.png', 'description' => 'Intel Core i5 8th Gen | 16GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'Intel Core i5 8th Gen', 'RAM' => '16GB', 'Storage' => '256GB SSD']],
            ['name' => 'Lenovo ThinkPad T490', 'price' => 40000, 'image' => '/images/lenovo_thinkpad.png', 'description' => 'Intel Core i5 8th Gen | 8GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'Intel Core i5 8th Gen', 'RAM' => '8GB', 'Storage' => '256GB SSD']],
            ['name' => 'Lenovo ThinkPad X1 Yoga Gen 4', 'price' => 47500, 'image' => '/images/lenovo_thinkpad_yoga.png', 'description' => 'Intel Core i5 8th Gen | 8GB RAM | 256GB SSD | Premium Metallic Finish', 'specifications' => ['Processor' => 'Intel Core i5 8th Gen', 'RAM' => '8GB', 'Storage' => '256GB SSD', 'Features' => 'Yoga Convertible']],
            ['name' => 'Lenovo ThinkPad T14s Gen 2', 'price' => 61000, 'image' => '/images/lenovo_thinkpad.png', 'description' => 'Intel Core i7 11th Gen | 32GB RAM | 512GB SSD', 'specifications' => ['Processor' => 'Intel Core i7 11th Gen', 'RAM' => '32GB', 'Storage' => '512GB SSD']],

            // Dell
            ['name' => 'Dell Latitude 5400 Touch', 'price' => 40500, 'image' => '/images/dell_latitude.png', 'description' => 'Intel Core i7 8th Gen | 8GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'Intel Core i7 8th Gen', 'RAM' => '8GB', 'Storage' => '256GB SSD', 'Features' => 'Touchscreen']],
            ['name' => 'Dell Latitude 5310 2-in-1', 'price' => 43000, 'image' => '/images/dell_latitude_2in1.png', 'description' => 'Intel Core i5 10th Gen | 8GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'Intel Core i5 10th Gen', 'RAM' => '8GB', 'Storage' => '256GB SSD', 'Features' => '2-in-1 Convertible']],
            ['name' => 'Dell Latitude 3410', 'price' => 46500, 'image' => '/images/dell_latitude.png', 'description' => 'Intel Core i5 10th Gen | 16GB RAM | 512GB SSD', 'specifications' => ['Processor' => 'Intel Core i5 10th Gen', 'RAM' => '16GB', 'Storage' => '512GB SSD']],
            ['name' => 'Dell Latitude 7320', 'price' => 47500, 'image' => '/images/dell_latitude.png', 'description' => 'Intel Core i5 11th Gen | 16GB RAM | 256GB SSD', 'specifications' => ['Processor' => 'Intel Core i5 11th Gen', 'RAM' => '16GB', 'Storage' => '256GB SSD']],
            ['name' => 'Dell Vostro 7572', 'price' => 50000, 'image' => '/images/dell_vostro.png', 'description' => 'Intel Core i7 8th Gen | 8GB RAM | 256GB SSD | 4GB Graphics', 'specifications' => ['Processor' => 'Intel Core i7 8th Gen', 'RAM' => '8GB', 'Storage' => '256GB SSD', 'Graphics' => '4GB Dedicated']],
            ['name' => 'Dell Latitude 7410 x360 Touch', 'price' => 57000, 'image' => '/images/dell_latitude_2in1.png', 'description' => 'Intel Core i7 10th Gen | 32GB RAM | 512GB SSD', 'specifications' => ['Processor' => 'Intel Core i7 10th Gen', 'RAM' => '32GB', 'Storage' => '512GB SSD', 'Features' => 'x360 Touch']],

            // Microsoft
            ['name' => 'Microsoft Surface Laptop Go', 'price' => 47000, 'image' => '/images/microsoft_surface_laptop.png', 'description' => 'Intel Core i5 10th Gen | 8GB RAM | 256GB SSD | 2K Touchscreen', 'specifications' => ['Processor' => 'Intel Core i5 10th Gen', 'RAM' => '8GB', 'Storage' => '256GB SSD', 'Features' => '2K Touchscreen']],
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
