<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@laptech.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Categories
        $categories = [
            ['name' => 'Laptops', 'slug' => 'laptops', 'description' => 'High-performance laptops for professionals and enthusiasts.'],
            ['name' => 'Desktops', 'slug' => 'desktops', 'description' => 'Powerful workstations and compact desktop solutions.'],
            ['name' => 'Components', 'slug' => 'components', 'description' => 'Enterprise-grade SSDs, HDDs, and internal hardware.'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Essential tech peripherals and upgrades.'],
            ['name' => 'Printers', 'slug' => 'printers', 'description' => 'Professional printing and imaging solutions.'],
            ['name' => 'Servers', 'slug' => 'servers', 'description' => 'Enterprise rack and tower servers for scalable infrastructure.'],
            ['name' => 'Networking', 'slug' => 'networking', 'description' => 'High-speed switches, routers, and connectivity gear.'],
            ['name' => 'Audio Visual', 'slug' => 'audio-visual', 'description' => 'Premium display, sound, and conferencing systems for enterprise environments.'],
            ['name' => 'Security Equipment', 'slug' => 'security-equipment', 'description' => 'Elite surveillance, access control, and hardware security modules.'],
            ['name' => 'Software Licenses', 'slug' => 'software-licenses', 'description' => 'Genuine enterprise software, operating systems, and database licenses.'],
            ['name' => 'POS Systems', 'slug' => 'pos-systems', 'description' => 'Point of Sale hardware for retail and hospitality.'],
            ['name' => 'Other', 'slug' => 'other', 'description' => 'Specialized tech equipment and uncategorized items.'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $componentCat = Category::where('slug', 'components')->first();
        $serverCat = Category::where('slug', 'servers')->first();
        $laptopCat = Category::where('slug', 'laptops')->first();

        // Products from datasource/products.md
        $products = [
            [
                'category_id' => $serverCat->id,
                'name' => 'HPE DL380 Gen 10 Rack Server',
                'slug' => 'hpe-dl380-gen-10',
                'description' => 'Intel Xeon Gold 6138 (20 cores/40 threads), 16x SFF NVMe bays, Dual 800W PSU. The industry-leading 2P rack server.',
                'price' => 255000,
                'stock' => 5,
                'is_featured' => true,
                'image' => '/images/server.png',
                'specifications' => ['CPU' => 'Intel Xeon Gold 6138', 'Bays' => '16x SFF', 'PSU' => '2x 800W'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => '3.84TB Dell KRT3G NVME RI SSD',
                'slug' => 'dell-krt3g-3-84tb-nvme',
                'description' => 'Intel 2.5" SFF U.2 Read Intensive PCIe NVMe Express Enterprise SSD.',
                'price' => 85000,
                'stock' => 20,
                'is_featured' => true,
                'image' => '/images/enterprise_ssd.png',
                'specifications' => ['Capacity' => '3.84TB', 'Interface' => 'NVMe U.2', 'Type' => 'Read Intensive'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'WD 0TS1889 NVMe Enterprise RI SSD',
                'slug' => 'wd-0ts1889-nvme-1-92tb',
                'description' => 'Western Digital Ultrastar DC SN200 NVMe Enterprise SSD. High endurance Read Intensive drive.',
                'price' => 65000,
                'stock' => 12,
                'image' => '/images/enterprise_ssd.png',
                'specifications' => ['Capacity' => '1.92TB', 'Interface' => 'NVMe', 'Endurance' => 'RI'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'HP G10+ 1.92TB NVMe RI SCN SSD',
                'slug' => 'hp-p20735-001-nvme',
                'description' => 'HP G10/G10+ Enterprise Read Intensive SCN SSD. Interface: U.3 PCI Express.',
                'price' => 75000,
                'stock' => 8,
                'image' => '/images/enterprise_ssd.png',
                'specifications' => ['PN' => 'P20735-001', 'Capacity' => '1.92TB', 'Interface' => 'U.3 NVMe'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'Samsung PM1123a 3.84TB SATA SSD',
                'slug' => 'samsung-pm1123a-3-84tb',
                'description' => 'Samsung PM1123a Series 2.5-inch Enterprise SATA SSD. High capacity storage.',
                'price' => 75000,
                'stock' => 10,
                'image' => '/images/enterprise_ssd.png',
                'specifications' => ['Model' => 'MZ-HTJ3T8H', 'Capacity' => '3.84TB', 'Interface' => 'SATA'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'HPE 1.92TB 2.5 6G SAS SSD',
                'slug' => 'hpe-q9u54a-1-92tb-sas',
                'description' => 'Hewlett Packard Enterprise 1.92 TB SAS 6G Solid State Drive.',
                'price' => 70000,
                'stock' => 15,
                'image' => '/images/enterprise_ssd.png',
                'specifications' => ['PN' => 'Q9U54A', 'Capacity' => '1.92TB', 'Interface' => 'SAS'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'Dell Compellent 1.92TB SAS 12G SSD',
                'slug' => 'dell-3f15r-1-92tb-sas',
                'description' => 'Dell Compellent SFF SAS 12Gbps Read Intensive SSD. Optimized for storage arrays.',
                'price' => 75000,
                'stock' => 25,
                'image' => '/images/enterprise_ssd.png',
                'specifications' => ['Model' => 'MZILT1T9HAJQ0D4', 'Interface' => 'SAS-3 12Gbps'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'Dell Compellent 2.4TB 10K SAS HDD',
                'slug' => 'dell-0x7nc4-2-4tb-sas',
                'description' => 'Dell Compellent 2.4TB 10K RPM SAS 12Gbps 2.5" 4Kn HDD.',
                'price' => 65000,
                'stock' => 30,
                'image' => '/images/sas_hdd.png',
                'specifications' => ['Capacity' => '2.4TB', 'Speed' => '10K RPM', 'Interface' => 'SAS-3'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'Samsung PM863 960GB Enterprise SATA',
                'slug' => 'samsung-960gb-sata-enterprise',
                'description' => 'Samsung Enterprise class 960GB SATA 2.5" SSD for heavy workloads.',
                'price' => 40000,
                'stock' => 40,
                'image' => '/images/enterprise_ssd.png',
                'specifications' => ['Model' => 'PM863', 'Capacity' => '960GB', 'Interface' => 'SATA'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'HP 960GB 12G MU SATA SSD',
                'slug' => 'hp-h5r19aa-960gb',
                'description' => 'HP Enterprise SATA Internal Solid State Drive (SSD) 960GB.',
                'price' => 35000,
                'stock' => 5,
                'image' => '/images/enterprise_ssd.png',
                'specifications' => ['PN' => 'H5R19AA', 'Capacity' => '960GB', 'Interface' => 'SATA'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'HP 73GB 10k 2.5 SAS HDD',
                'slug' => 'hp-518169-001-73gb',
                'description' => 'HP DL380 G6/G7 Server 73GB 10k 2.5 SAS Hard Drive.',
                'price' => 20000,
                'stock' => 10,
                'image' => '/images/sas_hdd.png',
                'specifications' => ['Capacity' => '73GB', 'Speed' => '10K RPM', 'Interface' => 'SAS 6G'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'HPE 2TB 3.5in 7.2K SAS HDD',
                'slug' => 'hpe-881339-b21-2tb',
                'description' => 'HPE 2TB 3.5in 7.2K RPM Hot-Swap SAS 12G MDL HDD.',
                'price' => 35000,
                'stock' => 20,
                'image' => '/images/sas_hdd.png',
                'specifications' => ['Capacity' => '2TB', 'Speed' => '7.2K RPM', 'Interface' => 'SAS 12G'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'Intel SSD D7-P5520 1.92TB NVMe',
                'slug' => 'intel-d7-p5520-1-92tb',
                'description' => 'Intel SSD D7-P5520 1.92TB U.2 PCIe 4.0 NVMe 3D4 TLC SSD.',
                'price' => 75000,
                'stock' => 15,
                'image' => '/images/enterprise_ssd.png',
                'specifications' => ['Capacity' => '1.92TB', 'Interface' => 'PCIe 4.0 NVMe', 'Flash' => '3D4 TLC'],
            ],
            [
                'category_id' => $serverCat->id,
                'name' => 'HPE ProLiant DL380 Gen11',
                'slug' => 'hpe-proliant-dl380-gen11',
                'description' => 'The next generation of enterprise performance. Accelerate your most data-intensive applications with Gen11 technology.',
                'price' => 455000,
                'stock' => 3,
                'is_featured' => true,
                'image' => '/images/hpe_gen11.png',
                'specifications' => ['Generation' => 'Gen11', 'Performance' => 'Enterprise Grade', 'Scale' => 'Built to Scale'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'HPE 64GB DDR5 Smart Memory Kit',
                'slug' => 'hpe-64gb-ddr5-smart-memory',
                'description' => 'HPE 64GB (1x64GB) Dual Rank x4 DDR5-6400 CAS-52 Registered Smart Memory Kit.',
                'price' => 125000,
                'stock' => 10,
                'image' => '/images/hpe_ddr5.png',
                'specifications' => ['PN' => 'P69728-B21', 'Capacity' => '64GB', 'Type' => 'DDR5-6400 RDIMM'],
            ],
            [
                'category_id' => $serverCat->id,
                'name' => 'Dell PowerEdge R760xs Rack Server',
                'slug' => 'dell-poweredge-r760xs',
                'description' => 'Intel Xeon Silver 4410Y, 16GB DDR5 ECC RAM, 2TB SATA HDD, PERC H755 RAID Controller.',
                'price' => 385000,
                'stock' => 4,
                'is_featured' => true,
                'image' => '/images/dell_r760.png',
                'specifications' => ['CPU' => 'Xeon Silver 4410Y', 'RAM' => '16GB DDR5', 'RAID' => 'PERC H755'],
            ],
            [
                'category_id' => $serverCat->id,
                'name' => 'Dell PowerEdge T160 Tower Server',
                'slug' => 'dell-poweredge-t160',
                'description' => 'Intel Xeon E-2414, 16GB DDR5, 2TB 7.2K RPM, Chassis 3 x 3.5", Broadcom 5720.',
                'price' => 195000,
                'stock' => 6,
                'image' => '/images/dell_t160.png',
                'specifications' => ['CPU' => 'Xeon E-2414', 'RAM' => '16GB DDR5', 'Storage' => '2TB 7.2K'],
            ],
            [
                'category_id' => Category::where('slug', 'software-licenses')->first()->id,
                'name' => 'Windows 11 Professional License',
                'slug' => 'windows-11-professional-license',
                'description' => 'Genuine Microsoft Windows 11 Professional Digital License key. Do your work uninterrupted with a secure, modern OS.',
                'price' => 15000,
                'stock' => 100,
                'is_featured' => true,
                'image' => '/images/win11_pro.png',
                'specifications' => ['Edition' => 'Professional', 'Type' => 'Digital License', 'Architecture' => '64-bit'],
            ],
            [
                'category_id' => Category::where('slug', 'software-licenses')->first()->id,
                'name' => 'Microsoft Office 2021 Pro Plus',
                'slug' => 'microsoft-office-2021-pro-plus',
                'description' => 'Genuine Microsoft Office Professional Plus 2021. Includes Word, Excel, PowerPoint, Outlook, and more.',
                'price' => 25000,
                'stock' => 50,
                'is_featured' => true,
                'image' => '/images/office2021.png',
                'specifications' => ['Version' => '2021', 'Edition' => 'Professional Plus', 'License' => 'Lifetime'],
            ],
            [
                'category_id' => Category::where('slug', 'software-licenses')->first()->id,
                'name' => 'Microsoft Office 2019 Pro Plus',
                'slug' => 'microsoft-office-2019-pro-plus',
                'description' => 'Genuine Microsoft Office Professional Plus 2019. Stable productivity suite for legacy systems.',
                'price' => 18000,
                'stock' => 30,
                'image' => '/images/office2021.png',
                'specifications' => ['Version' => '2019', 'Edition' => 'Professional Plus', 'License' => 'Lifetime'],
            ],
            [
                'category_id' => Category::where('slug', 'software-licenses')->first()->id,
                'name' => 'Microsoft Office 2024 Pro Plus',
                'slug' => 'microsoft-office-2024-pro-plus',
                'description' => 'The latest Microsoft Office Professional Plus 2024. Optimized for Windows 11 with new collaboration features.',
                'price' => 35000,
                'stock' => 25,
                'is_featured' => true,
                'image' => '/images/office2021.png',
                'specifications' => ['Version' => '2024', 'Edition' => 'Professional Plus', 'License' => 'Lifetime'],
            ],
            [
                'category_id' => Category::where('slug', 'software-licenses')->first()->id,
                'name' => 'Microsoft Project 2024 Professional',
                'slug' => 'microsoft-project-2024-professional',
                'description' => 'Genuine Microsoft Project 2024 Professional. Powerful project management for professionals.',
                'price' => 45000,
                'stock' => 15,
                'image' => '/images/win11_pro.png',
                'specifications' => ['Edition' => 'Professional', 'Year' => '2024', 'Type' => 'Project Management'],
            ],
            [
                'category_id' => Category::where('slug', 'security-equipment')->first()->id,
                'name' => 'Fortinet Activation Key',
                'slug' => 'fortinet-activation-key',
                'description' => 'Genuine Fortinet Activation Key for secure networking and firewall features.',
                'price' => 120000,
                'stock' => 5,
                'is_featured' => true,
                'image' => '/images/win11_pro.png',
                'specifications' => ['Vendor' => 'Fortinet', 'Type' => 'Security License', 'Edition' => 'Retail'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'Intel Xeon Gold 6430 Processor',
                'slug' => 'intel-xeon-gold-6430',
                'description' => '32 Cores, 64 Threads, 2.1 GHz Base, 60MB Cache. High-performance enterprise CPU.',
                'price' => 285000,
                'stock' => 2,
                'image' => '/images/hpe_gen11.png',
                'specifications' => ['Cores' => '32', 'Threads' => '64', 'Cache' => '60MB'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'HPE MSA 2.4TB SAS 12G HDD',
                'slug' => 'hpe-msa-2-4tb-sas',
                'description' => 'HPE MSA 2.4TB SAS 12G Enterprise 10K SFF (2.5in) M2 HDD. P/N: R0Q57A.',
                'price' => 75000,
                'stock' => 12,
                'image' => '/images/sas_hdd.png',
                'specifications' => ['PN' => 'R0Q57A', 'Capacity' => '2.4TB', 'Interface' => 'SAS 12G'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'HPE 64GB DDR5 4800MHz RAM',
                'slug' => 'hpe-64gb-ddr5-4800',
                'description' => 'HPE 64GB DDR5 4800MHz Registered Smart Memory module.',
                'price' => 95000,
                'stock' => 20,
                'image' => '/images/hpe_ddr5.png',
                'specifications' => ['Capacity' => '64GB', 'Speed' => '4800MHz', 'Type' => 'DDR5 RDIMM'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'Samsung 32GB DDR5 4800MHz RAM',
                'slug' => 'samsung-32gb-ddr5-4800',
                'description' => 'Samsung 32GB DDR5 4800MHz Registered Enterprise Memory.',
                'price' => 45000,
                'stock' => 30,
                'image' => '/images/hpe_ddr5.png',
                'specifications' => ['Capacity' => '32GB', 'Speed' => '4800MHz', 'Type' => 'DDR5 RDIMM'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'HPE 64GB DDR4 3200MHz RAM',
                'slug' => 'hpe-64gb-ddr4-3200',
                'description' => 'HPE 64GB DDR4 3200MHz Registered Smart Memory.',
                'price' => 55000,
                'stock' => 15,
                'image' => '/images/hpe_ddr5.png',
                'specifications' => ['Capacity' => '64GB', 'Speed' => '3200MHz', 'Type' => 'DDR4 RDIMM'],
            ],
            [
                'category_id' => $componentCat->id,
                'name' => 'HPE 32GB DDR4 3200MHz RAM',
                'slug' => 'hpe-32gb-ddr4-3200',
                'description' => 'HPE 32GB DDR4 3200MHz Registered Smart Memory.',
                'price' => 28000,
                'stock' => 40,
                'image' => '/images/hpe_ddr5.png',
                'specifications' => ['Capacity' => '32GB', 'Speed' => '3200MHz', 'Type' => 'DDR4 RDIMM'],
            ],
            [
                'category_id' => Category::where('slug', 'software-licenses')->first()->id,
                'name' => 'Microsoft SQL Server License',
                'slug' => 'microsoft-sql-server-license',
                'description' => 'Enterprise-grade database management. Genuine Microsoft SQL Server licensing for scalable data solutions.',
                'price' => 85000,
                'stock' => 10,
                'image' => '/images/sql_server.png',
                'specifications' => ['Type' => 'Database Server', 'Edition' => 'Standard/Enterprise', 'License' => 'Per Core/CAL'],
            ],
        ];

        foreach ($products as $prod) {
            Product::create($prod);
        }

        // Add some dummy Laptops to fill the Hub
        $laptops = [
            [
                'category_id' => $laptopCat->id,
                'name' => 'X-Pro Ultra 16',
                'slug' => 'x-pro-ultra-16',
                'description' => 'The ultimate machine for developers and creators. High-performance M3 chip.',
                'price' => 345000,
                'stock' => 15,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&q=80&w=800',
                'specifications' => ['CPU' => 'M3 Max', 'RAM' => '64GB', 'SSD' => '2TB'],
            ],
            [
                'category_id' => $laptopCat->id,
                'name' => 'Stealth Blade G5',
                'slug' => 'stealth-blade-g5',
                'description' => 'Ultra-thin, ultra-fast gaming and productivity laptop with RTX 4080.',
                'price' => 210000,
                'stock' => 10,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?auto=format&fit=crop&q=80&w=800',
                'specifications' => ['GPU' => 'RTX 4080', 'RAM' => '32GB', 'Screen' => '240Hz'],
            ],
        ];

        foreach ($laptops as $lap) {
            Product::create($lap);
        }

        // Service Categories
        $infra = ServiceCategory::create(['name' => 'Infrastructure', 'slug' => 'infrastructure']);
        $support = ServiceCategory::create(['name' => 'Technical Support', 'slug' => 'technical-support']);
        $security = ServiceCategory::create(['name' => 'Security Systems', 'slug' => 'security-systems']);

        // Services from signage
        $services = [
            [
                'name' => 'IT Consultancy',
                'slug' => 'it-consultancy',
                'description' => 'Professional technology advisory services to align your IT infrastructure with your business goals and future growth.',
                'icon' => 'fas fa-lightbulb',
                'is_featured' => true,
                'service_category_id' => $infra->id,
            ],
            [
                'name' => 'Hardware Sales & Repair',
                'slug' => 'hardware-sales-repair',
                'description' => 'Premium laptop and desktop sourcing combined with expert diagnostic and repair services for all major brands.',
                'icon' => 'fas fa-laptop-medical',
                'is_featured' => true,
                'service_category_id' => $support->id,
            ],
            [
                'name' => 'CCTV & Networking',
                'slug' => 'cctv-networking',
                'description' => 'Enterprise-grade security surveillance systems and robust networking infrastructure for seamless, secure connectivity.',
                'icon' => 'fas fa-video',
                'is_featured' => true,
                'service_category_id' => $security->id,
            ],
            [
                'name' => 'Server Solutions & Upgrades',
                'slug' => 'server-solutions',
                'description' => 'High-performance server deployment, proactive management, and strategic hardware upgrades for data-intensive operations.',
                'icon' => 'fas fa-server',
                'is_featured' => true,
                'service_category_id' => $infra->id,
            ],
            [
                'name' => 'Software Sales & Services',
                'slug' => 'software-solutions',
                'description' => 'Genuine software provisioning, installation, and enterprise licensing management tailored to your specific requirements.',
                'icon' => 'fas fa-code',
                'is_featured' => true,
                'service_category_id' => $support->id,
            ],
            [
                'name' => 'Gaming & Rendering Machines',
                'slug' => 'high-performance-computing',
                'description' => 'Custom-engineered workstations designed specifically for elite gaming, 3D rendering, and professional-grade video production.',
                'icon' => 'fas fa-microchip',
                'is_featured' => true,
                'service_category_id' => $support->id,
            ],
        ];

        foreach ($services as $serv) {
            Service::create($serv);
        }

        // Create some sample orders for the Admin User
        $admin = User::where('email', 'admin@laptech.com')->first();
        $sampleProducts = Product::limit(3)->get();
        
        \App\Models\Order::create([
            'user_id' => $admin->id,
            'items' => $sampleProducts->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'quantity' => 1,
                'image' => $p->image
            ])->toArray(),
            'total' => $sampleProducts->sum('price'),
            'status' => 'pending',
            'shipping_address' => [
                'name' => 'Antony Admin',
                'email' => 'admin@laptech.com',
                'address' => 'Laptech HQ, Nairobi',
                'city' => 'Nairobi'
            ]
        ]);

        \App\Models\Order::create([
            'user_id' => $admin->id,
            'items' => [['id' => $sampleProducts[0]->id, 'name' => $sampleProducts[0]->name, 'price' => $sampleProducts[0]->price, 'quantity' => 2]],
            'total' => $sampleProducts[0]->price * 2,
            'status' => 'delivered',
            'shipping_address' => [
                'name' => 'Samuel Client',
                'email' => 'samuel@example.com',
                'address' => 'Upper Hill',
                'city' => 'Nairobi'
            ]
        ]);

        // Create some sample quotes
        $sampleServices = Service::limit(2)->get();
        foreach ($sampleServices as $service) {
            \App\Models\Quote::create([
                'user_id' => $admin->id,
                'service_id' => $service->id,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+254700000000',
                'details' => 'I need a custom ' . $service->name . ' for my new office setup in Westlands.',
                'status' => 'pending'
            ]);
        }
    }
}
