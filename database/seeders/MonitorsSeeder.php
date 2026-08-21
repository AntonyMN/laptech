<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MonitorsSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'monitors')->first();

        if (!$category) {
            return;
        }

        $products = [
            [
                'name' => 'HP StarVision Pro S27 – 27”',
                'description' => 'Crisp, Vibrant Visuals with Slim Bezels.',
                'price' => 55000,
                'stock' => 10,
                'is_featured' => true,
                'image' => '/images/hp_starvision_pro_s27.png',
                'specifications' => [
                    'Resolution' => 'Full HD Display',
                    'Refresh Rate' => '100Hz',
                    'Features' => 'Slim Bezels, Built-in Speakers'
                ]
            ],
            [
                'name' => 'LG 32MR50C-B – 32” Curved',
                'description' => 'FHD Curved Monitor with AMD FreeSync.',
                'price' => 72000,
                'stock' => 15,
                'is_featured' => true,
                'image' => '/images/lg_32mr50c_b.png',
                'specifications' => [
                    'Resolution' => 'FHD',
                    'Refresh Rate' => '100Hz',
                    'Features' => 'AMD FreeSync'
                ]
            ],
            [
                'name' => 'ASUS TUF VG34VQL3A',
                'description' => '34” Curved Ultrawide Gaming Monitor for the TUF Gamers.',
                'price' => 87000,
                'stock' => 5,
                'is_featured' => true,
                'image' => '/images/asus_tuf_vg34vql3a.png',
                'specifications' => [
                    'Resolution' => 'WQHD',
                    'Refresh Rate' => '180Hz',
                    'Response Time' => '1ms',
                    'Features' => 'FreeSync / G-Sync'
                ]
            ],
            [
                'name' => 'LG 34WQ60C-B – 34” Ultrawide',
                'description' => '34” Ultrawide IPS Monitor for productivity.',
                'price' => 90000,
                'stock' => 8,
                'is_featured' => true,
                'image' => '/images/lg_34wq60c_b.png',
                'specifications' => [
                    'Resolution' => 'WQHD 3440×1440',
                    'Panel' => 'IPS Panel',
                    'Refresh Rate' => '60Hz'
                ]
            ]
        ];

        foreach ($products as $productData) {
            $productData['slug'] = Str::slug($productData['name']);
            $productData['category_id'] = $category->id;
            
            Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }
    }
}
