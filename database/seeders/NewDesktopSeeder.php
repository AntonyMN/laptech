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
    }
}
