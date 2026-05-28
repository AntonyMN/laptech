<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class NewAccessoriesSeeder extends Seeder
{
    public function run(): void
    {
        // Dynamically create or resolve the Accessories category by slug
        $accessoriesCat = Category::updateOrCreate(
            ['slug' => 'accessories'],
            [
                'name' => 'Accessories',
                'description' => 'Essential computing, productivity, and workspace accessories.'
            ]
        );

        $accessories = [
            [
                'category_id' => $accessoriesCat->id,
                'name' => 'Logitech MK270 Wireless Keyboard & Mouse Combo',
                'slug' => 'logitech-mk270-combo',
                'description' => 'Reliable wireless keyboard and mouse combo with long-range connection, smart multimedia keys, and extended battery life.',
                'price' => 3800,
                'stock' => 15,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/logitech_mk270.png',
                'specifications' => [
                    'Connectivity' => '2.4 GHz Wireless (USB Dongle)',
                    'Keyboard Layout' => 'Full-size QWERTY',
                    'Mouse DPI' => '1000 DPI Optical',
                    'Battery Life' => 'Up to 36 months keyboard, 12 months mouse',
                    'Brand' => 'Logitech',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $accessoriesCat->id,
                'name' => 'Logitech Pebble M350 Silent Mouse',
                'slug' => 'logitech-pebble-m350',
                'description' => 'Modern, slim, and silent wireless portable mouse designed for clean, aesthetic, and quiet workspaces.',
                'price' => 2800,
                'stock' => 25,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/logitech_pebble.png',
                'specifications' => [
                    'Connectivity' => 'Bluetooth & 2.4 GHz Wireless',
                    'Silent Click' => 'Over 90% noise reduction',
                    'Battery' => '1x AA battery (included)',
                    'Design' => 'Ultra-slim Pebble profile',
                    'Brand' => 'Logitech',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $accessoriesCat->id,
                'name' => 'HP USB-C G5 Essential Docking Station',
                'slug' => 'hp-usb-c-g5-dock',
                'description' => 'Universal USB-C docking station providing seamless dual-monitor output, high-speed data transfer, and 65W power delivery for your laptop.',
                'price' => 14500,
                'stock' => 10,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/hp_g5_dock.png',
                'specifications' => [
                    'Interface' => 'USB Type-C',
                    'Ports' => '4x USB 3.0, 1x USB-C, 2x DisplayPort, 1x HDMI, 1x RJ-45 Ethernet',
                    'Power Delivery' => 'Up to 65W charging support',
                    'Compatibility' => 'Universal HP, Dell, Lenovo, and MacBook',
                    'Brand' => 'HP',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $accessoriesCat->id,
                'name' => 'Universal 65W USB-C Laptop Charger',
                'slug' => 'universal-65w-usb-c-charger',
                'description' => 'High-compatibility 65W fast-charging power adapter supporting USB-C Power Delivery (PD) for HP, Dell, Lenovo, and MacBook laptops.',
                'price' => 3500,
                'stock' => 30,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/universal_charger.png',
                'specifications' => [
                    'Wattage' => '65W Auto-switching',
                    'Connection' => 'USB Type-C Power Delivery',
                    'Safety' => 'Over-voltage, over-current, and short-circuit protection',
                    'Cable' => '1.8m thick heavy-duty cable',
                    'Condition' => 'Brand New'
                ]
            ]
        ];

        foreach ($accessories as $prod) {
            Product::updateOrCreate(['slug' => $prod['slug']], $prod);
        }
    }
}
