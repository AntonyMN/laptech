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

        $infraCat = ServiceCategory::where('slug', 'infrastructure')->first();
        $infraCatId = $infraCat ? $infraCat->id : 1;

        $managedCat = ServiceCategory::where('slug', 'managed-services')->first();
        $managedCatId = $managedCat ? $managedCat->id : 3;

        // Seed 6 services from Laptech Solutions Banner
        $services = [
            [
                'service_category_id' => $managedCatId,
                'name' => 'IT Consultancy',
                'slug' => 'it-consultancy',
                'description' => 'Expert IT advisory services for business growth, network architecture planning, security auditing, and hardware/software optimization.',
                'icon' => 'fas fa-hands-helping',
                'is_featured' => true,
                'features' => [
                    [
                        'title' => 'Security Audit',
                        'description' => 'Complete vulnerability assessment and penetration testing to secure organizational data.'
                    ],
                    [
                        'title' => 'Infrastructure Design',
                        'description' => 'Custom blueprints for local local-area networks, high-uptime storage pools, and server racks.'
                    ],
                    [
                        'title' => 'Technology Strategy',
                        'description' => 'Direct alignment of your business objectives with state-of-the-art technological solutions.'
                    ]
                ]
            ],
            [
                'service_category_id' => $managedCatId,
                'name' => 'Laptop & Desktop Sales & Repair',
                'slug' => 'laptop-desktop-sales-repair',
                'description' => 'Professional diagnostic services, component-level board repairs, screen/battery replacements, and sales of certified premium devices.',
                'icon' => 'fas fa-toolbox',
                'is_featured' => true,
                'features' => [
                    [
                        'title' => 'Board-Level Diagnostics',
                        'description' => 'Motherboard micro-soldering, power management IC repair, and liquid damage restoration.'
                    ],
                    [
                        'title' => 'Device Upgrades',
                        'description' => 'Speed optimization via fast SSD installations, RAM expansions, and thermal paste replacement.'
                    ],
                    [
                        'title' => 'Certified Refurbished Sales',
                        'description' => 'Thoroughly tested and verified Ex-UK laptops and desktops supplied with warranties.'
                    ]
                ]
            ],
            [
                'service_category_id' => $infraCatId,
                'name' => 'CCTV & Networking',
                'slug' => 'cctv-networking',
                'description' => 'Comprehensive installation of high-definition security camera networks and secure, robust business router configurations.',
                'icon' => 'fas fa-network-wired',
                'is_featured' => true,
                'features' => [
                    [
                        'title' => 'Surveillance Deployment',
                        'description' => 'Professional indoor/outdoor PoE IP and analog HD camera installation with mobile remote access.'
                    ],
                    [
                        'title' => 'Structured Cabling',
                        'description' => 'High-bandwidth CAT6 and fiber optic routing, clean cabinet dressing, and server rack installations.'
                    ],
                    [
                        'title' => 'Firewall & Routing',
                        'description' => 'Implementation of Fortinet, Sophos, or Cisco firewalls to defend your network border.'
                    ]
                ]
            ],
            [
                'service_category_id' => $infraCatId,
                'name' => 'Server Sales, Installation & Management',
                'slug' => 'server-sales-upgrades',
                'description' => 'End-to-end enterprise server solutions — supply, rack installation and configuration of rack and tower servers, plus ongoing management, hardware upgrades and virtualization.',
                'icon' => 'fas fa-server',
                'is_featured' => true,
                'features' => [
                    [
                        'title' => 'Supply & Installation',
                        'description' => 'Sourcing, racking and configuration of HP ProLiant and Dell PowerEdge rackmount and tower servers.'
                    ],
                    [
                        'title' => 'Managed Upkeep & Upgrades',
                        'description' => 'Ongoing monitoring, patching and hot-swappable SAS/NVMe SSD, Xeon CPU and ECC RAM upgrades.'
                    ],
                    [
                        'title' => 'Virtualization & Hosting',
                        'description' => 'Installation and support for VMware ESXi hypervisors, Windows Server, Active Directory, and SQL Server.'
                    ]
                ]
            ],
            [
                'service_category_id' => $managedCatId,
                'name' => 'Software Sales & Services',
                'slug' => 'software-sales-services',
                'description' => 'Genuine OS activations, productivity suite licenses, business databases, and system configuration solutions.',
                'icon' => 'fas fa-compact-disc',
                'is_featured' => true,
                'features' => [
                    [
                        'title' => 'Authentic Licensing',
                        'description' => 'Legal license activation keys for Microsoft Windows 11 Pro, Office Pro Plus, and SQL Server.'
                    ],
                    [
                        'title' => 'Business Applications',
                        'description' => 'ERP setup, point-of-sale (POS) installation, and accounting software deployment.'
                    ],
                    [
                        'title' => 'Data Backup & Sync',
                        'description' => 'Disaster recovery planning, automated local backups, and secure cloud storage replication.'
                    ]
                ]
            ],
            [
                'service_category_id' => $managedCatId,
                'name' => 'Gaming & Rendering Machines',
                'slug' => 'gaming-rendering-machines',
                'description' => 'Custom-built, high-action gaming rigs, editing workspaces, and rendering units featuring multi-core CPUs and premium GPU architectures.',
                'icon' => 'fas fa-gamepad',
                'is_featured' => true,
                'features' => [
                    [
                        'title' => 'Custom PC Tuning',
                        'description' => 'Custom liquid cooling loops, high-speed DDR5 memory profiles, and GPU overclocking.'
                    ],
                    [
                        'title' => 'Rendering Optimization',
                        'description' => 'Configured for heavy CAD, Blender, Premiere Pro, and Revit workflows.'
                    ],
                    [
                        'title' => 'High-FPS Gaming',
                        'description' => 'Built to deliver butter-smooth performance on 2K/4K resolutions for modern gaming titles.'
                    ]
                ]
            ]
        ];

        foreach ($services as $srv) {
            Service::updateOrCreate(['slug' => $srv['slug']], $srv);
        }

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
