<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SoftwareSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'software'],
            ['name' => 'Software', 'description' => 'Genuine software licenses and subscriptions.']
        );

        $products = [
            ['name' => 'Adobe Creative Cloud 1-Year Subscription', 'price' => 47000, 'image' => '/images/software_creative.png', 'description' => 'Genuine Adobe Creative Cloud 1-Year Subscription.'],
            ['name' => 'Archicad 2024 3-Year Subscription', 'price' => 55000, 'image' => '/images/software_cad.png', 'description' => 'Genuine Archicad 2024 3-Year Subscription.'],
            ['name' => 'AutoCAD 2024 3-Year Subscription', 'price' => 52500, 'image' => '/images/software_cad.png', 'description' => 'Genuine AutoCAD 2024 3-Year Subscription.'],
            ['name' => 'QuickBooks 2020 Premier/Pro', 'price' => 37000, 'image' => '/images/software_accounting.png', 'description' => 'Genuine QuickBooks 2020 Premier/Pro.'],
            ['name' => 'QuickBooks Point of Sale v19', 'price' => 39500, 'image' => '/images/software_accounting.png', 'description' => 'Genuine QuickBooks Point of Sale v19.'],
            ['name' => 'Office 2021 Pro', 'price' => 7500, 'image' => '/images/software_os_office.png', 'description' => 'Genuine Office 2021 Pro.'],
            ['name' => 'Windows 11 Pro', 'price' => 6500, 'image' => '/images/software_os_office.png', 'description' => 'Genuine Windows 11 Pro.'],
            ['name' => 'Home & Business 2021 Mac', 'price' => 19500, 'image' => '/images/software_os_office.png', 'description' => 'Genuine Home & Business 2021 Mac.'],
        ];

        foreach ($products as $productData) {
            $productData['slug'] = Str::slug($productData['name']);
            $productData['category_id'] = $category->id;
            $productData['stock'] = 99; // Software is digital, so high stock
            $productData['is_featured'] = false;
            
            // Generate some specs based on the name
            $productData['specifications'] = ['Type' => 'Digital License / Subscription'];
            
            Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }
    }
}
