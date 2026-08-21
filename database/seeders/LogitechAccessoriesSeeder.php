<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LogitechAccessoriesSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'accessories'],
            ['name' => 'Accessories', 'description' => 'Computer peripherals and accessories.']
        );

        $rawProducts = [
            // Mice
            ['name' => 'Logitech Mouse M90', 'base_price' => 500, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M190', 'base_price' => 1050, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M171 Black', 'base_price' => 1150, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M171 Red', 'base_price' => 1200, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M185 Graphite', 'base_price' => 1200, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M185 Blue Grey', 'base_price' => 1200, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M196 Bluetooth Black', 'base_price' => 1250, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M196 Bluetooth White', 'base_price' => 1250, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M221 Graphite', 'base_price' => 1750, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M240 Bluetooth', 'base_price' => 2300, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M350 Pebble 2 White', 'base_price' => 2900, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M350 Pebble 2 Black', 'base_price' => 2900, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M575 Ergo Graphite', 'base_price' => 5500, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M650 L Bluetooth Graphite', 'base_price' => 4400, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech Mouse M720 Bluetooth', 'base_price' => 6500, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech MX Anywhere 3S', 'base_price' => 12500, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech MX Master 3S Graphite', 'base_price' => 13500, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech MX Master 3S White', 'base_price' => 13500, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],
            ['name' => 'Logitech G305', 'base_price' => 7500, 'image' => '/images/logitech_mouse.png', 'type' => 'Mouse'],

            // Keyboards
            ['name' => 'Logitech K380s Bluetooth Keyboard', 'base_price' => 5000, 'image' => '/images/logitech_keyboard.png', 'type' => 'Keyboard'],
            ['name' => 'Logitech G512 RGB Keyboard', 'base_price' => 14000, 'image' => '/images/logitech_keyboard.png', 'type' => 'Keyboard'],
            ['name' => 'Logitech MX Keys Mini Pink', 'base_price' => 14500, 'image' => '/images/logitech_keyboard.png', 'type' => 'Keyboard'],
            ['name' => 'Logitech Pop Keys Daydream', 'base_price' => 13500, 'image' => '/images/logitech_keyboard.png', 'type' => 'Keyboard'],
            ['name' => 'Logitech Pop Keys Yellow', 'base_price' => 13500, 'image' => '/images/logitech_keyboard.png', 'type' => 'Keyboard'],
            ['name' => 'Logitech MX Keys S', 'base_price' => 15000, 'image' => '/images/logitech_keyboard.png', 'type' => 'Keyboard'],
            ['name' => 'Logitech MX Keys Mini Mechanical', 'base_price' => 21500, 'image' => '/images/logitech_keyboard.png', 'type' => 'Keyboard'],
            ['name' => 'Logitech MX Keys Mini Pale Grey', 'base_price' => 15000, 'image' => '/images/logitech_keyboard.png', 'type' => 'Keyboard'],
            ['name' => 'Logitech MX Keys Plus', 'base_price' => 16000, 'image' => '/images/logitech_keyboard.png', 'type' => 'Keyboard'],

            // Combos
            ['name' => 'Logitech MK120 Combo', 'base_price' => 1600, 'image' => '/images/logitech_combo.png', 'type' => 'Combo'],
            ['name' => 'Logitech MK220 Combo', 'base_price' => 2200, 'image' => '/images/logitech_combo.png', 'type' => 'Combo'],
            ['name' => 'Logitech MK235 Combo', 'base_price' => 2800, 'image' => '/images/logitech_combo.png', 'type' => 'Combo'],
            ['name' => 'Logitech MK270 Combo', 'base_price' => 2800, 'image' => '/images/logitech_combo.png', 'type' => 'Combo'],
            ['name' => 'Logitech MK295 Combo', 'base_price' => 4500, 'image' => '/images/logitech_combo.png', 'type' => 'Combo'],
            ['name' => 'Logitech MK650 Bluetooth Combo', 'base_price' => 13000, 'image' => '/images/logitech_combo.png', 'type' => 'Combo'],
            ['name' => 'Logitech MK850 Bluetooth Combo', 'base_price' => 18000, 'image' => '/images/logitech_combo.png', 'type' => 'Combo'],
            ['name' => 'Logitech Pebble 2 Combo Bluetooth', 'base_price' => 8000, 'image' => '/images/logitech_combo.png', 'type' => 'Combo'],
            ['name' => 'Logitech MX Keys Mini Combo for Business', 'base_price' => 25000, 'image' => '/images/logitech_combo.png', 'type' => 'Combo'],
            ['name' => 'Logitech MX Keys Combo for Business Gen 2', 'base_price' => 28000, 'image' => '/images/logitech_combo.png', 'type' => 'Combo'],

            // Webcams
            ['name' => 'Logitech Webcam C270', 'base_price' => 3000, 'image' => '/images/logitech_webcam.png', 'type' => 'Webcam'],
            ['name' => 'Logitech Webcam C505e', 'base_price' => 3000, 'image' => '/images/logitech_webcam.png', 'type' => 'Webcam'],
            ['name' => 'Logitech Webcam C922', 'base_price' => 12000, 'image' => '/images/logitech_webcam.png', 'type' => 'Webcam'],
            ['name' => 'Logitech Brio Ultra 4K Webcam', 'base_price' => 24000, 'image' => '/images/logitech_webcam.png', 'type' => 'Webcam'],
            ['name' => 'Logitech Webcam C920e', 'base_price' => 9000, 'image' => '/images/logitech_webcam.png', 'type' => 'Webcam'],
            ['name' => 'Logitech Webcam C925e', 'base_price' => 10500, 'image' => '/images/logitech_webcam.png', 'type' => 'Webcam'],
            ['name' => 'Logitech Brio 500 Pink', 'base_price' => 15500, 'image' => '/images/logitech_webcam.png', 'type' => 'Webcam'],
            ['name' => 'Logitech Brio 505', 'base_price' => 14500, 'image' => '/images/logitech_webcam.png', 'type' => 'Webcam'],
            ['name' => 'Logitech MX Brio 705 Business 4K', 'base_price' => 22000, 'image' => '/images/logitech_webcam.png', 'type' => 'Webcam'],
            ['name' => 'Logitech Webcam C930e', 'base_price' => 11500, 'image' => '/images/logitech_webcam.png', 'type' => 'Webcam'],

            // Headsets
            ['name' => 'Logitech Headset H110', 'base_price' => 1250, 'image' => '/images/logitech_headset.png', 'type' => 'Headset'],
            ['name' => 'Logitech Headset H111', 'base_price' => 1250, 'image' => '/images/logitech_headset.png', 'type' => 'Headset'],
            ['name' => 'Logitech Headset H340', 'base_price' => 2500, 'image' => '/images/logitech_headset.png', 'type' => 'Headset'],
            ['name' => 'Logitech Headset H390', 'base_price' => 3300, 'image' => '/images/logitech_headset.png', 'type' => 'Headset'],
            ['name' => 'Logitech Headset H540', 'base_price' => 4350, 'image' => '/images/logitech_headset.png', 'type' => 'Headset'],
            ['name' => 'Logitech Zone Vibe 100 Black', 'base_price' => 12000, 'image' => '/images/logitech_headset.png', 'type' => 'Headset'],
            ['name' => 'Logitech Zone Wired', 'base_price' => 13500, 'image' => '/images/logitech_headset.png', 'type' => 'Headset'],
            ['name' => 'Logitech Zone 300 White', 'base_price' => 8500, 'image' => '/images/logitech_headset.png', 'type' => 'Headset'],
            ['name' => 'Logitech Zone 300 Black', 'base_price' => 8500, 'image' => '/images/logitech_headset.png', 'type' => 'Headset'],
            ['name' => 'Logitech Headset H650e', 'base_price' => 12500, 'image' => '/images/logitech_headset.png', 'type' => 'Headset'],
            ['name' => 'Logitech Headset H820e Mono', 'base_price' => 16000, 'image' => '/images/logitech_headset.png', 'type' => 'Headset'],
            ['name' => 'Logitech G432 Headset', 'base_price' => 9500, 'image' => '/images/logitech_headset.png', 'type' => 'Headset'],
            ['name' => 'Logitech G335 Headset', 'base_price' => 8500, 'image' => '/images/logitech_headset.png', 'type' => 'Headset'],

            // Speakers
            ['name' => 'Logitech Speakers Z120', 'base_price' => 2550, 'image' => '/images/logitech_speaker.png', 'type' => 'Speaker'],
            ['name' => 'Logitech Speakers Z130', 'base_price' => 3000, 'image' => '/images/logitech_speaker.png', 'type' => 'Speaker'],
            ['name' => 'Logitech G560 Speakers', 'base_price' => 35000, 'image' => '/images/logitech_speaker.png', 'type' => 'Speaker'],

            // Video Conferencing
            ['name' => 'Logitech PTZ Pro 2', 'base_price' => 52000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech BCC950', 'base_price' => 24500, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Rally Bar', 'base_price' => 370000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Rally Bar Mini Graphite', 'base_price' => 300000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Scribe White USB', 'base_price' => 125000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Webcam Rally Bar Huddle', 'base_price' => 145000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Group', 'base_price' => 68000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech MeetUp', 'base_price' => 67000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech MeetUp 2', 'base_price' => 120000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Logi Dock', 'base_price' => 55000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Expansion Microphone (2 packs) for Group Camera', 'base_price' => 30000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Expansion Mic for MeetUp', 'base_price' => 25000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Web Cam Sight', 'base_price' => 220000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Rally Mounting Kit', 'base_price' => 25500, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Rally Mic Pod Hub', 'base_price' => 32000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Tap Table Mount', 'base_price' => 25000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Tap Wall Mount', 'base_price' => 25000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Extended Cable for Group 10M', 'base_price' => 15500, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Extended Cable for Group 15M', 'base_price' => 19000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Extended Cable for MeetUp 10M', 'base_price' => 15000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
            ['name' => 'Logitech Strong Cable Type C 10M', 'base_price' => 30000, 'image' => '/images/logitech_video_conferencing.png', 'type' => 'Video Conferencing'],
        ];

        foreach ($rawProducts as $productData) {
            $basePrice = $productData['base_price'];
            $increment = 0;

            if ($basePrice < 3000) {
                $increment = 500;
            } elseif ($basePrice >= 3000 && $basePrice < 10000) {
                $increment = 1000;
            } elseif ($basePrice >= 10000 && $basePrice < 20000) {
                $increment = 2000;
            } elseif ($basePrice >= 20000 && $basePrice < 50000) {
                $increment = 5000;
            } else {
                $increment = 10000;
            }

            $finalPrice = $basePrice + $increment;

            $slug = Str::slug($productData['name']);

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $productData['name'],
                    'category_id' => $category->id,
                    'price' => $finalPrice,
                    'image' => $productData['image'],
                    'description' => 'Genuine ' . $productData['name'] . ' with ' . $productData['type'] . ' functionality.',
                    'specifications' => ['Type' => $productData['type'], 'Brand' => 'Logitech'],
                    'stock' => 15,
                    'is_featured' => false
                ]
            );
        }
    }
}
