<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Product;
use App\Models\Category;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class NewServicesAndCCTVsSeeder extends Seeder
{
    public function run(): void
    {
        // Dynamically resolve category IDs by slug to ensure compatibility across local and production DBs
        $securityCat = Category::where('slug', 'security-equipment')->first();
        $securityCatId = $securityCat ? $securityCat->id : 9;

        $techSupportCat = ServiceCategory::where('slug', 'technical-support')->first();
        $techSupportCatId = $techSupportCat ? $techSupportCat->id : 2;

        // 1. Insert Laptop & PC Repair Service
        Service::updateOrCreate(
            ['slug' => 'laptop-pc-repair'],
            [
                'service_category_id' => $techSupportCatId,
                'name' => 'Laptop & PC Repair',
                'description' => 'Professional diagnostic, board-level repairs, screen replacements, battery upgrades, and software optimization for all PC and MacBook brands.',
                'icon' => 'fas fa-toolbox',
                'is_featured' => true,
                'features' => [
                    [
                        'title' => 'Diagnostic & Board Level',
                        'description' => 'Component-level troubleshooting, motherboard repairs, power IC, and liquid damage restoration.'
                    ],
                    [
                        'title' => 'Part Replacement',
                        'description' => 'Genuine replacement parts for screens, keyboards, batteries, and charging ports with full warranty.'
                    ],
                    [
                        'title' => 'System Optimization',
                        'description' => 'SSD upgrades, operating system clean installation, malware removal, and performance tuning.'
                    ]
                ]
            ]
        );

        // 2. Insert CCTV / Security Products
        $cctvProducts = [
            [
                'category_id' => $securityCatId,
                'name' => 'Hikvision 2MP Dome Camera',
                'slug' => 'hikvision-2mp-dome-camera',
                'description' => 'High-definition indoor ceiling dome camera with infrared night vision and robust protective design.',
                'price' => 3500,
                'stock' => 25,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/hikvision_dome.png',
                'specifications' => [
                    'Resolution' => '2 Megapixel (1080p)',
                    'Type' => 'Indoor Dome',
                    'Night Vision' => 'IR up to 30m',
                    'Brand' => 'Hikvision',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $securityCatId,
                'name' => 'Hikvision 5MP Bullet Camera',
                'slug' => 'hikvision-5mp-bullet-camera',
                'description' => 'Ultra-high-definition weatherproof outdoor bullet camera with up to 30m smart IR night vision.',
                'price' => 4800,
                'stock' => 20,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/hikvision_bullet.png',
                'specifications' => [
                    'Resolution' => '5 Megapixel (1920p)',
                    'Type' => 'Outdoor Bullet (IP67)',
                    'Night Vision' => 'Smart IR up to 30m',
                    'Brand' => 'Hikvision',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $securityCatId,
                'name' => 'Dahua 4MP IP Network Camera',
                'slug' => 'dahua-4mp-ip-camera',
                'description' => 'Digital IP camera with high-resolution video streams, built-in microphone, power over ethernet (PoE), and smart motion detection.',
                'price' => 7200,
                'stock' => 15,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/dahua_ip_camera.png',
                'specifications' => [
                    'Resolution' => '4 Megapixel IP',
                    'Type' => 'Smart Network Bullet',
                    'PoE' => 'Supported (Power over Ethernet)',
                    'Features' => 'Built-in Mic & Smart Tracking',
                    'Brand' => 'Dahua',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $securityCatId,
                'name' => 'EZVIZ C6N Smart Wi-Fi PTZ Camera',
                'slug' => 'ezviz-c6n-smart-wifi-camera',
                'description' => 'Smart home Wi-Fi camera with motorized pan and tilt for 360-degree visual coverage, smart tracking, two-way audio, and clear night vision.',
                'price' => 5500,
                'stock' => 18,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/ezviz_c6n.png',
                'specifications' => [
                    'Resolution' => '2 Megapixel (1080p)',
                    'Type' => 'Pan & Tilt Wi-Fi Smart Camera',
                    'Rotation' => '360-degree motor coverage',
                    'Audio' => 'Two-way talk audio',
                    'Brand' => 'EZVIZ',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $securityCatId,
                'name' => 'Hikvision 8-Channel Turbo HD DVR',
                'slug' => 'hikvision-8-channel-dvr',
                'description' => 'Professional 8-channel digital video recorder supporting high-definition video compression and multi-channel playback.',
                'price' => 12500,
                'stock' => 8,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/hikvision_dvr.png',
                'specifications' => [
                    'Channels' => '8-Channel analog HD',
                    'Compression' => 'H.265 Pro+',
                    'Video Output' => 'HDMI & VGA up to 1080p',
                    'Brand' => 'Hikvision',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $securityCatId,
                'name' => 'Dahua 8-Channel PoE NVR',
                'slug' => 'dahua-8-channel-nvr',
                'description' => 'High-capacity 8-channel Network Video Recorder with built-in PoE ports for easy plug-and-play IP camera installations.',
                'price' => 16500,
                'stock' => 6,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/dahua_nvr.png',
                'specifications' => [
                    'Channels' => '8-Channel Digital IP',
                    'PoE Ports' => '8 Built-in PoE Ports',
                    'Decoding' => 'Up to 8MP resolution',
                    'Brand' => 'Dahua',
                    'Condition' => 'Brand New'
                ]
            ]
        ];

        foreach ($cctvProducts as $prod) {
            Product::updateOrCreate(['slug' => $prod['slug']], $prod);
        }
    }
}
