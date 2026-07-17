<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class NewServerAndSoftwareSeeder extends Seeder
{
    public function run(): void
    {
        // Dynamically resolve category IDs by slug
        $serverCat = Category::where('slug', 'servers')->first();
        $serverCatId = $serverCat ? $serverCat->id : 4;

        $componentCat = Category::where('slug', 'components')->first();
        $componentCatId = $componentCat ? $componentCat->id : 2;

        $softwareCat = Category::where('slug', 'software-licenses')->first();
        $softwareCatId = $softwareCat ? $softwareCat->id : 6;

        $securityCat = Category::where('slug', 'security-equipment')->first();
        $securityCatId = $securityCat ? $securityCat->id : 5;

        $peripheralCat = Category::where('slug', 'peripherals')->first();
        $peripheralCatId = $peripheralCat ? $peripheralCat->id : 3;

        $products = [
            [
                'category_id' => $serverCatId,
                'name' => 'Dell PowerEdge R760 Rack Server',
                'slug' => 'dell-poweredge-r760-rack-server',
                'description' => 'High-performance 2U, two-socket rack server designed for demanding enterprise workloads including database management, virtualization, and AI/ML processing.',
                'price' => 685000,
                'stock' => 5,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/dell_r760.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon Silver 4410Y (12 Cores, 2.0 GHz)',
                    'RAM' => '32GB DDR5 RDIMM',
                    'Storage' => '2x 480GB SATA SSD (RAID 1)',
                    'Form Factor' => '2U Rack Server',
                    'Power Supply' => 'Dual 800W Redundant',
                    'Remote Management' => 'iDRAC9 Enterprise',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'Dell PowerEdge T160 Tower Server',
                'slug' => 'dell-poweredge-t160-tower-server',
                'description' => 'Entry-level, space-saving single-socket 3U tower server ideal for small-to-medium businesses, educational institutions, and remote offices.',
                'price' => 215000,
                'stock' => 4,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/dell_t160.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E-2414 (4 Cores, 2.6 GHz)',
                    'RAM' => '16GB DDR5 ECC UDIMM',
                    'Storage' => '1x 2TB SATA 7.2K HDD',
                    'Form Factor' => '3U Mini Tower',
                    'Power Supply' => '300W Bronze',
                    'Remote Management' => 'iDRAC9 Basic',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'HPE ProLiant DL380 Gen11 Server',
                'slug' => 'hpe-proliant-dl380-gen11-server',
                'description' => 'Adaptable 2U rack server offering exceptional computing power, high-speed DDR5 memory, and PCIe Gen5 bandwidth for advanced virtualization and enterprise databases.',
                'price' => 720000,
                'stock' => 3,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/hpe_gen11.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon Silver 4410Y (12 Cores, 2.0 GHz)',
                    'RAM' => '32GB DDR5 Smart Memory',
                    'Storage' => '1x 960GB SATA Read Intensive SSD',
                    'Form Factor' => '2U Rack Server',
                    'Remote Management' => 'HPE iLO 6 Standard',
                    'Power Supply' => '1x 800W Flex Slot Platinum',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'HPE 32GB DDR5-4800 Smart Memory RDIMM',
                'slug' => 'hpe-32gb-ddr5-smart-memory',
                'description' => 'High-speed, error-correcting enterprise RAM module optimized for HPE ProLiant Gen11 servers, delivering enhanced data reliability and processing throughput.',
                'price' => 195000,
                'stock' => 10,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/hpe_ddr5.png',
                'specifications' => [
                    'Capacity' => '32GB',
                    'Memory Type' => 'DDR5 RDIMM (Registered)',
                    'Speed' => '4800 MT/s',
                    'Rank' => 'Dual Rank (2Rx8)',
                    'Voltage' => '1.1V',
                    'Compatibility' => 'HPE ProLiant Gen11 Servers',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'HPE 960GB SATA 6G Read Intensive SFF SSD',
                'slug' => 'hpe-960gb-sata-read-intensive-ssd',
                'description' => 'Enterprise-grade Solid State Drive featuring high performance, low latency, and low power consumption for read-intensive application workloads.',
                'price' => 58000,
                'stock' => 15,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/enterprise_ssd.png',
                'specifications' => [
                    'Capacity' => '960 GB',
                    'Interface' => 'SATA 6Gb/s',
                    'Form Factor' => '2.5-inch SFF',
                    'Workload Type' => 'Read Intensive (RI)',
                    'Carrier Type' => 'Smart Carrier (SC)',
                    'Compatibility' => 'HPE ProLiant Servers',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'HPE 1.2TB SAS 12G 10K SFF HDD',
                'slug' => 'hpe-12tb-sas-10k-hdd',
                'description' => 'High-performance, reliable SAS hard drive engineered for 24/7 mission-critical database and transaction-heavy server environments.',
                'price' => 65000,
                'stock' => 20,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/sas_hdd.png',
                'specifications' => [
                    'Capacity' => '1.2 TB',
                    'Interface' => 'SAS 12Gb/s',
                    'Rotational Speed' => '10,000 RPM',
                    'Form Factor' => '2.5-inch SFF',
                    'Carrier Type' => 'Smart Carrier (SC)',
                    'Compatibility' => 'HPE ProLiant Servers',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $softwareCatId,
                'name' => 'Microsoft Windows 11 Professional License',
                'slug' => 'microsoft-windows-11-professional-license',
                'description' => 'Genuine full package product (FPP) activation license for Windows 11 Pro, offering robust business management, security, and multitasking capabilities.',
                'price' => 24500,
                'stock' => 50,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/win11_pro.png',
                'specifications' => [
                    'Edition' => 'Windows 11 Professional',
                    'License Type' => 'Retail / Full Package Product (FPP)',
                    'Architecture' => '64-bit',
                    'Media' => 'USB Flash Drive / Digital Download',
                    'Support' => 'Lifetime Activation, 1 PC',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $softwareCatId,
                'name' => 'Microsoft Office 2021 Professional Plus',
                'slug' => 'microsoft-office-2021-professional-plus',
                'description' => 'Lifetime activation retail key for Microsoft Office 2021 Professional Plus, containing Word, Excel, PowerPoint, Outlook, Access, Publisher, and Teams.',
                'price' => 9800,
                'stock' => 50,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/office2021.png',
                'specifications' => [
                    'Edition' => 'Office 2021 Professional Plus',
                    'License Type' => 'Retail / Digital Activation Key',
                    'OS Compatibility' => 'Windows 10 / 11',
                    'Applications' => 'Word, Excel, PowerPoint, Outlook, Access, Publisher, Teams',
                    'Support' => 'Lifetime Activation, 1 PC',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $softwareCatId,
                'name' => 'Microsoft SQL Server 2022 Standard Server License',
                'slug' => 'microsoft-sql-server-2022-standard',
                'description' => 'SQL Server 2022 Standard Edition license designed for departmental databases, enabling secure, high-performance data storage and querying.',
                'price' => 85000,
                'stock' => 10,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/sql_server.png',
                'specifications' => [
                    'Edition' => 'SQL Server 2022 Standard',
                    'License Type' => 'Server License (Requires CALs)',
                    'Ideal For' => 'Small to medium databases and transaction systems',
                    'Support' => 'Core-based virtualization or Server + CAL model',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $securityCatId,
                'name' => 'Fortinet FortiGate 60F Next-Generation Firewall',
                'slug' => 'fortinet-fortigate-60f-firewall',
                'description' => 'Compact and reliable hardware firewall appliance providing advanced threat protection, SSL inspection, and secure SD-WAN capabilities for enterprise networks.',
                'price' => 145000,
                'stock' => 5,
                'is_featured' => true,
                'status' => 'Brand new',
                'image' => '/images/fortinet.png',
                'specifications' => [
                    'Threat Protection Throughput' => '700 Mbps',
                    'Firewall Throughput' => '10 Gbps',
                    'Interfaces' => '10x GE RJ45 ports (including 2x WAN, 1x DMZ, 7x Internal)',
                    'Concurrent Sessions' => '700,000',
                    'Management' => 'FortiGate Cloud & Local Web UI',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $peripheralCatId,
                'name' => 'Premium 42U Server Rack Cabinet',
                'slug' => 'premium-42u-server-rack-cabinet',
                'description' => 'Heavy-duty 42U enterprise server cabinet designed for optimal organization, security, and cooling of network rack-mount equipment.',
                'price' => 85000,
                'stock' => 5,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/server.png',
                'specifications' => [
                    'Form Factor' => '42U Server Rack Cabinet',
                    'Dimensions' => '600mm x 1000mm x 2000mm',
                    'Features' => 'Perforated front/rear doors, locking side panels, cooling fans, heavy-duty castors',
                    'Loading Capacity' => 'Up to 1000 kg',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'HP ProLiant DL560 G8 Server',
                'slug' => 'hp-proliant-dl560-g8-server',
                'description' => 'High-density 2U four-socket rack server designed for virtualization, database, and business-critical consolidation workloads.',
                'price' => 270000,
                'stock' => 3,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/hpe_proliant_dl_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E5-4603 v2',
                    'Cores' => '16 Cores (8x2)',
                    'RAM' => '128GB RAM',
                    'Storage' => '600GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '2U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'HP ProLiant ML350e G8 Tower Server',
                'slug' => 'hp-proliant-ml350e-g8-server',
                'description' => 'Expandable 4U dual-socket tower server delivering essential performance and storage growth for remote and branch offices.',
                'price' => 105000,
                'stock' => 2,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/hpe_proliant_ml_tower.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E5-2403',
                    'Cores' => '4 Cores @ 1.8GHz',
                    'RAM' => '32GB RAM',
                    'Storage' => '600GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '4U Tower Server',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'HP ProLiant DL360 G8 Server',
                'slug' => 'hp-proliant-dl360-g8-server',
                'description' => 'Industry-leading 1U rack server optimized for space-constrained data centers, offering high compute power and dense virtualization capacities.',
                'price' => 130000,
                'stock' => 4,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/hpe_proliant_dl_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E5-2430 v2',
                    'Cores' => '12 Cores (6x2)',
                    'RAM' => '128GB RAM',
                    'Storage' => '600GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '1U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'HP ProLiant DL360p Gen8 Server',
                'slug' => 'hp-proliant-dl360p-gen8-server',
                'description' => 'Enterprise-class 1U rack server delivering superior performance, efficiency, and advanced remote management features.',
                'price' => 120000,
                'stock' => 5,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/hpe_proliant_dl_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E5-2640',
                    'Cores' => '8 Cores (4x2)',
                    'RAM' => '128GB RAM',
                    'Storage' => '600GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '1U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'HP ProLiant DL380 G9 Server',
                'slug' => 'hp-proliant-dl380-g9-server',
                'description' => 'The data center standard 2U server offering robust performance, versatility, and manageability for multi-workload IT environments.',
                'price' => 310000,
                'stock' => 3,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/hpe_proliant_dl_rack.png',
                'specifications' => [
                    'Cores' => '28 Cores (14x2)',
                    'RAM' => '64GB RAM',
                    'Storage' => '600GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '2U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'HP ProLiant DL380 G10 Server (Intel Xeon Gold 5114)',
                'slug' => 'hp-proliant-dl380-g10-gold-5114',
                'description' => 'Secure and adaptable 2U server hosting dual Intel Xeon Gold processors, ideal for cloud infrastructure, databases, and heavy virtualization.',
                'price' => 320000,
                'stock' => 3,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/hpe_proliant_dl_rack.png',
                'specifications' => [
                    'CPU' => 'Dual Intel Xeon Gold 5114',
                    'Cores' => '16 Cores Total',
                    'RAM' => '32GB RAM',
                    'Storage' => '1.2TB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '2U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'HP ProLiant DL380 G10 Server (Intel Xeon Silver 4210)',
                'slug' => 'hp-proliant-dl380-g10-silver-4210',
                'description' => 'High-density, multi-threaded 2U rack server featuring dual Xeon Silver processors for reliable business workloads and container deployment.',
                'price' => 420000,
                'stock' => 2,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/hpe_proliant_dl_rack.png',
                'specifications' => [
                    'CPU' => 'Dual Intel Xeon Silver 4210',
                    'Cores' => '20 Cores Total',
                    'RAM' => '64GB RAM',
                    'Storage' => '1.2TB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '2U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'HP ProLiant ML310e Gen8 Tower Server',
                'slug' => 'hp-proliant-ml310e-gen8-server',
                'description' => 'Entry-level single-socket 4U tower server offering essential security, expansion, and reliability for small offices and retail locations.',
                'price' => 110000,
                'stock' => 3,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/hpe_proliant_ml_tower.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E3-1220 v3 @ 3.10GHz',
                    'RAM' => '16GB RAM',
                    'Storage' => '1TB HDD',
                    'Form Factor' => 'Tower Server',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'Dell PowerEdge R620 Server (Intel Xeon E5-2609)',
                'slug' => 'dell-poweredge-r620-e5-2609',
                'description' => 'Ultra-dense 1U dual-socket rack server engineered for high virtualization density, database clusters, and corporate networks.',
                'price' => 120000,
                'stock' => 4,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/dell_poweredge_r_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E5-2609',
                    'Cores' => '8 Cores (4x2)',
                    'RAM' => '128GB RAM',
                    'Storage' => '600GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '1U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'Dell PowerEdge R620 Server (Intel Xeon E5-2640)',
                'slug' => 'dell-poweredge-r620-e5-2640',
                'description' => 'Highly efficient 1U computing platform featuring 12 physical cores and 128GB memory, perfect for medium-scale databases and virtualization hosts.',
                'price' => 120000,
                'stock' => 4,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/dell_poweredge_r_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E5-2640',
                    'Cores' => '12 Cores (6x2)',
                    'RAM' => '128GB RAM',
                    'Storage' => '600GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '1U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'Dell PowerEdge R930 Server',
                'slug' => 'dell-poweredge-r930-server',
                'description' => 'Massive 4U four-socket rack server designed to execute database servers, in-memory databases, and large-scale virtualization.',
                'price' => 260000,
                'stock' => 2,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/dell_poweredge_r_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E7-4830 v3',
                    'Cores' => '24 Cores (12x2)',
                    'RAM' => '64GB RAM',
                    'Storage' => '900GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '4U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'Dell PowerEdge R910 Server',
                'slug' => 'dell-poweredge-r910-server',
                'description' => 'High-reliability 4U rack server featuring 4 power supplies and 16 cores, offering enterprise-class reliability and massive storage flexibility.',
                'price' => 120000,
                'stock' => 2,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/dell_poweredge_r_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E5-2670 v2',
                    'Cores' => '16 Cores (8x2)',
                    'RAM' => '128GB RAM',
                    'Storage' => '900GB SAS HDD',
                    'Power Supply' => '4 PSU',
                    'Form Factor' => '4U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'Dell PowerEdge R420 Server',
                'slug' => 'dell-poweredge-r420-server',
                'description' => 'Compute-dense 2-socket 1U rack server designed to support Web hosting, mail servers, and general network infrastructure workloads.',
                'price' => 120000,
                'stock' => 5,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/dell_poweredge_r_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E5-2420',
                    'Cores' => '12 Cores (6x2)',
                    'RAM' => '128GB RAM',
                    'Storage' => '600GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '1U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'Dell PowerEdge R730 Server (Intel Xeon E5-2667 v3)',
                'slug' => 'dell-poweredge-r730-e5-2667',
                'description' => 'Versatile and scalable 2U dual-socket rack server, highly optimized for databases, large-scale VDI, and virtualization workloads.',
                'price' => 240000,
                'stock' => 3,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/dell_poweredge_r_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E5-2667 v3',
                    'Cores' => '16 Cores (8x2)',
                    'RAM' => '64GB RAM',
                    'Storage' => '600GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '2U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'Dell PowerEdge R720 Server',
                'slug' => 'dell-poweredge-r720-server',
                'description' => 'The previous-generation data center benchmark, offering a balance of memory capacity, storage expansion, and dual-processor compute options.',
                'price' => 120000,
                'stock' => 4,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/dell_poweredge_r_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E5-2670',
                    'Cores' => '16 Cores (8x2)',
                    'RAM' => '128GB RAM',
                    'Storage' => '600GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '2U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'Dell PowerEdge R730 Server (Intel Xeon E5-2683 v3)',
                'slug' => 'dell-poweredge-r730-e5-2683',
                'description' => 'High core-count 2U server designed for data-intensive processing, parallel workloads, and web hosting platforms.',
                'price' => 275000,
                'stock' => 3,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/dell_poweredge_r_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E5-2683 v3',
                    'Cores' => '28 Cores (14x2)',
                    'RAM' => '64GB RAM',
                    'Storage' => '600GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '2U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'Dell PowerEdge R640 Server',
                'slug' => 'dell-poweredge-r640-server',
                'description' => '14th generation high-performance 1U rack server, leveraging dual Xeon Silver processors for web hosting, virtualization, and software-defined storage.',
                'price' => 280000,
                'stock' => 2,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/dell_poweredge_r_rack.png',
                'specifications' => [
                    'CPU' => 'Dual Intel Xeon Silver 4110',
                    'Cores' => '16 Cores (8x2)',
                    'RAM' => '64GB RAM',
                    'Storage' => '1.2TB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '1U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'Dell PowerEdge R630 Server',
                'slug' => 'dell-poweredge-r630-server',
                'description' => 'Ultra-dense 1U rack server delivering virtualization and enterprise workload computing power with dual-processor efficiency.',
                'price' => 180000,
                'stock' => 4,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/dell_poweredge_r_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon E5-2620',
                    'Cores' => '12 Cores (6x2)',
                    'RAM' => '64GB RAM',
                    'Storage' => '600GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '1U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'Dell PowerEdge 2950 Server',
                'slug' => 'dell-poweredge-2950-server',
                'description' => 'Legacy 2U rack server providing standard dual-socket computing capabilities and basic database/file storage.',
                'price' => 60000,
                'stock' => 1,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/dell_poweredge_r_rack.png',
                'specifications' => [
                    'CPU' => 'Xeon Processor',
                    'Cores' => '4 Cores',
                    'RAM' => '8GB RAM',
                    'Storage' => '300GB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '2U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'Cisco UCS C240-M5 Rack Server',
                'slug' => 'cisco-ucs-c240-m5-server',
                'description' => 'High-performance 2U rack server offering industry-leading computing power, storage expansion, and unified management for virtualization and enterprise apps.',
                'price' => 150000,
                'stock' => 3,
                'is_featured' => false,
                'status' => 'Certified Refurbished',
                'image' => '/images/cisco_ucs_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon Silver 4210',
                    'Cores' => '10 Cores',
                    'RAM' => '64GB RAM',
                    'Storage' => '2TB SAS HDD',
                    'Power Supply' => 'Dual PSU',
                    'Form Factor' => '2U Rackmount',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $serverCatId,
                'name' => 'HP ProLiant DL380 Gen10 Xeon Gold 6138 Server',
                'slug' => 'hp-proliant-dl380-gen10-xeon-gold-6138',
                'description' => 'Highly customizable, powerhouse 2U rack server equipped with 20 cores, dual heat sinks, and redundant 800W power supply units for extreme enterprise workloads.',
                'price' => 255000,
                'stock' => 2,
                'is_featured' => true,
                'status' => 'Certified Refurbished',
                'image' => '/images/hpe_proliant_dl_rack.png',
                'specifications' => [
                    'CPU' => 'Intel Xeon Gold 6138 (20 Cores, 40 Threads)',
                    'HDD Bay' => '16x SFF, NVMe Support',
                    'HeatSink Type' => '2x HeatSinks',
                    'Power Supply' => '2x 800W PSU',
                    'Cooling' => 'All fans included',
                    'Controller & Kit' => '1x Raid P480i battery, iLO, rail kit',
                    'Form Factor' => '2U Rack Server',
                    'Condition' => 'Certified Refurbished'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'Dell KRT3G 3.84TB NVMe Enterprise SSD',
                'slug' => 'dell-krt3g-384tb-nvme-ri-ssd',
                'description' => 'Dell Compellent certified 3.84TB enterprise solid state drive utilizing high-speed U.2 PCIe NVMe interface for read-intensive workloads.',
                'price' => 85000,
                'stock' => 12,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/enterprise_ssd.png',
                'specifications' => [
                    'Capacity' => '3.84TB',
                    'Interface Type' => 'U.2 PCIe NVMe Express',
                    'Form Factor' => '2.5" SFF',
                    'Endurance Class' => 'Read Intensive (RI)',
                    'Sector Size' => '512 / 512e',
                    'Hot-Swap' => 'Yes',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'Western Digital 0TS1889 NVMe Enterprise SSD',
                'slug' => 'wd-0ts1889-nvme-enterprise-ri-ssd',
                'description' => 'Western Digital enterprise-class 1.92TB NVMe U.2 SSD optimized for high endurance, extreme data throughput, and low latency virtualization.',
                'price' => 65000,
                'stock' => 15,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/enterprise_ssd.png',
                'specifications' => [
                    'Capacity' => '1.92TB',
                    'Interface Type' => 'U.2 PCIe NVMe Express',
                    'Form Factor' => '2.5" SFF',
                    'Endurance Class' => 'Read Intensive (RI)',
                    'Sector Size' => '512 / 512e',
                    'Hot-Swap' => 'Yes',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'HP P20735-001 G10/G10+ 1.92TB NVMe SSD',
                'slug' => 'hp-p20735-001-192tb-nvme-ri-ssd',
                'description' => 'Genuine HPE 1.92TB NVMe Read Intensive Smart Carrier (SCN) solid state drive designed for ProLiant Gen10 and Gen10 Plus server platforms.',
                'price' => 75000,
                'stock' => 10,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/enterprise_ssd.png',
                'specifications' => [
                    'Part Number' => 'P20735-001',
                    'Capacity' => '1.92TB',
                    'Interface' => 'U.3 PCIe (NVMe) SCN',
                    'Form Factor' => '2.5" SFF',
                    'Endurance Class' => 'Enterprise Read Intensive',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'Samsung PM1123a Series 3.84TB SATA SSD',
                'slug' => 'samsung-pm1123a-384tb-sata-ssd',
                'description' => 'Samsung PM1123a (MZ-HTJ3T8H) Enterprise SSD delivering consistent SATA-3 speeds, high reliability, and massive 3.84TB solid-state storage.',
                'price' => 75000,
                'stock' => 8,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/enterprise_ssd.png',
                'specifications' => [
                    'Model' => 'MZ-HTJ3T8H',
                    'Capacity' => '3.84TB',
                    'Interface Type' => 'SATA-3 6.0Gbps',
                    'Form Factor' => '2.5" SFF',
                    'Endurance Class' => 'Enterprise Read Intensive',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'HPE Q9U54A 1.92TB SAS SSD',
                'slug' => 'hpe-q9u54a-192tb-sas-ssd',
                'description' => 'Hewlett Packard Enterprise 1.92TB 12G SAS write-efficient and high-read solid state drive with 2.5-inch small form factor carrier.',
                'price' => 70000,
                'stock' => 14,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/enterprise_ssd.png',
                'specifications' => [
                    'Part Number' => 'Q9U54A',
                    'Capacity' => '1.92TB',
                    'Interface Type' => 'SAS 12G',
                    'Form Factor' => '2.5" SFF',
                    'Brand' => 'Hewlett Packard Enterprise',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'Dell Compellent 3F15R 1.92TB SAS SSD',
                'slug' => 'dell-compellent-3f15r-192tb-sas-ssd',
                'description' => 'Dell MZILT1T9HAJQ0D4 1.92TB enterprise SAS-3 solid state drive, built for Dell Compellent arrays to serve extreme IOPS workloads.',
                'price' => 75000,
                'stock' => 10,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/enterprise_ssd.png',
                'specifications' => [
                    'Part Number' => '3F15R',
                    'Model' => 'MZILT1T9HAJQ0D4',
                    'Capacity' => '1.92TB',
                    'Interface Type' => 'SAS-3 12Gbps',
                    'Form Factor' => '2.5" SFF',
                    'Endurance Class' => 'Enterprise Read Intensive (RI)',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'Dell Compellent 2.4TB 10K SAS HDD',
                'slug' => 'dell-compellent-24tb-10k-sas-hdd',
                'description' => 'High-capacity 2.4TB mechanical enterprise hard drive spinning at 10,000 RPM, featuring 12Gbps SAS interface and advanced format sector support.',
                'price' => 65000,
                'stock' => 20,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/sas_hdd.png',
                'specifications' => [
                    'Part Number' => '0X7NC4',
                    'Capacity' => '2.4TB',
                    'Spindle Speed' => '10,000 RPM',
                    'Interface Type' => 'SAS-3 12Gbps',
                    'Sector Size' => '512e / 4Kn AF Switching',
                    'Form Factor' => '2.5" SFF',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'Generic 960GB SATA 2.5" Enterprise SSD',
                'slug' => 'generic-960gb-sata-25-enterprise-ssd',
                'description' => 'Enterprise-class 2.5-inch 960GB SATA SSD designed for daily server workloads, database tasks, and read caching.',
                'price' => 40000,
                'stock' => 15,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/enterprise_ssd.png',
                'specifications' => [
                    'Capacity' => '960GB',
                    'Interface Type' => 'SATA-3 6.0Gbps',
                    'Form Factor' => '2.5" SFF',
                    'Endurance Class' => 'Enterprise Read Intensive',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'HP H5R19AA 960GB Enterprise SATA SSD',
                'slug' => 'hp-h5r19aa-960gb-sata-ssd',
                'description' => 'HP Enterprise-grade 960GB SATA solid state drive offering mixed-use endurance and 12G speed optimization for critical corporate nodes.',
                'price' => 35000,
                'stock' => 8,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/enterprise_ssd.png',
                'specifications' => [
                    'Part Number' => 'H5R19AA',
                    'Capacity' => '960GB',
                    'Interface Type' => 'SATA 6Gbps (12G MU optimization)',
                    'Form Factor' => '2.5" SFF',
                    'Endurance Class' => 'Enterprise Mixed Use (MU)',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'HP 518169-001 DL380 G6/G7 73GB SAS HDD',
                'slug' => 'hp-518169-001-73gb-sas-hdd',
                'description' => 'Legacy HP 73GB small form factor SAS hard drive running at 10,000 RPM, optimized for DL380 G6 and G7 server systems.',
                'price' => 20000,
                'stock' => 5,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/sas_hdd.png',
                'specifications' => [
                    'Part Number' => '518169-001',
                    'Capacity' => '73GB',
                    'Spindle Speed' => '10,000 RPM',
                    'Interface Type' => 'SAS 6Gbps / 3Gbps',
                    'Form Factor' => '2.5" SFF',
                    'Compatibility' => 'ProLiant DL380 G6 / G7',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'HPE 881339-B21 2TB Hot-Swap SAS HDD',
                'slug' => 'hpe-881339-b21-2tb-sas-hdd',
                'description' => 'Large Form Factor (LFF) HPE 2TB mid-line SAS mechanical drive, operating at 7,200 RPM with hot-swap support.',
                'price' => 35000,
                'stock' => 12,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/sas_hdd.png',
                'specifications' => [
                    'Part Number' => '881339-B21',
                    'Capacity' => '2TB',
                    'Spindle Speed' => '7,200 RPM',
                    'Interface Type' => 'SAS 12G',
                    'Form Factor' => '3.5" LFF',
                    'Carrier Type' => 'Hot-Swap Midline (MDL)',
                    'Condition' => 'Brand New'
                ]
            ],
            [
                'category_id' => $componentCatId,
                'name' => 'Intel SSD D7-P5520 1.92TB NVMe PCIe 4.0 SSD',
                'slug' => 'intel-d7-p5520-192tb-nvme-ssd',
                'description' => 'Intel D7 series premium 1.92TB solid state drive, running on blazing-fast PCIe 4.0 NVMe U.2 interface with 3D4 TLC flash memory.',
                'price' => 75000,
                'stock' => 6,
                'is_featured' => false,
                'status' => 'Brand new',
                'image' => '/images/enterprise_ssd.png',
                'specifications' => [
                    'Model' => 'D7-P5520',
                    'Capacity' => '1.92TB',
                    'Interface Type' => 'U.2 PCIe 4.0 NVMe',
                    'Flash Memory' => '3D4 TLC NAND',
                    'Form Factor' => '2.5" SFF',
                    'Condition' => 'Brand New'
                ]
            ]
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(['slug' => $prod['slug']], $prod);
        }
    }
}
