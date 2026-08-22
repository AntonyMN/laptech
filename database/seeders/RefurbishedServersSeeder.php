<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RefurbishedServersSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'servers')->first();

        if (!$category) {
            return;
        }

        $products = [
            // HP
            ['name' => 'HP ProLiant DL380 G6', 'price' => 84990, 'image' => '/images/hp_proliant_dl_2u.png', 'description' => 'Intel(R) Xeon 4Core | 64GB RAM | 600GB SAS HDD | 2U Rack-mountable', 'specifications' => ['Processor' => 'Intel Xeon 4Core', 'RAM' => '64GB', 'Storage' => '600GB SAS HDD', 'Form Factor' => '2U Rack-mountable']],
            ['name' => 'HP ProLiant DL380 G7', 'price' => 94990, 'image' => '/images/hp_proliant_dl_2u.png', 'description' => 'Intel(R) Xeon 8Core | 64GB RAM | 600GB SAS HDD | 2U Rack-mountable', 'specifications' => ['Processor' => 'Intel Xeon 8Core', 'RAM' => '64GB', 'Storage' => '600GB SAS HDD', 'Form Factor' => '2U Rack-mountable']],
            ['name' => 'HP ProLiant DL560 G8', 'price' => 219990, 'image' => '/images/hp_proliant_dl_2u.png', 'description' => 'Intel Xeon E5-4603 v2 16core | 128GB RAM | 600GB SAS HDD | 2U', 'specifications' => ['Processor' => 'Intel Xeon E5-4603 v2 (16 Core)', 'RAM' => '128GB', 'Storage' => '600GB SAS HDD', 'Form Factor' => '2U Rack-mountable']],
            ['name' => 'HP ProLiant ML350e G8', 'price' => 94990, 'image' => '/images/hp_proliant_ml.png', 'description' => 'Intel Xeon E5-2403 4core | 32GB RAM | 600GB SAS HDD | 4U Tower', 'specifications' => ['Processor' => 'Intel Xeon E5-2403 (4 Core)', 'RAM' => '32GB', 'Storage' => '600GB SAS HDD', 'Form Factor' => '4U Tower']],
            ['name' => 'HP ProLiant DL320e G8', 'price' => 94990, 'image' => '/images/hp_proliant_dl_1u.png', 'description' => 'Intel Xeon E3-1220 v2 4core | 32GB RAM | 1TB HDD | 1U', 'specifications' => ['Processor' => 'Intel Xeon E3-1220 v2 (4 Core)', 'RAM' => '32GB', 'Storage' => '1TB HDD', 'Form Factor' => '1U Rack-mountable']],
            ['name' => 'HP ProLiant DL360 G8', 'price' => 119990, 'image' => '/images/hp_proliant_dl_1u.png', 'description' => 'Intel Xeon E5-2430 v2 12Core | 128GB RAM | 600GB SAS HDD | 1U', 'specifications' => ['Processor' => 'Intel Xeon E5-2430 v2 (12 Core)', 'RAM' => '128GB', 'Storage' => '600GB SAS HDD', 'Form Factor' => '1U Rack-mountable']],
            // HPE Servers
            ['name' => 'HPE Proliant DL380 Gen11 Server', 'price' => 849990, 'image' => '/images/hpe_proliant_dl380.png', 'description' => 'Dual Xeon Gold 6430 | 256GB DDR5 RAM | 2x 2.4TB SAS HDD | 2U', 'specifications' => ['Processor' => '2x Intel Xeon Gold 6430', 'RAM' => '256GB DDR5', 'Storage' => '2x 2.4TB SAS HDD', 'Form Factor' => '2U Rack-mountable']],
            ['name' => 'HPE Proliant DL380 Gen10 Server', 'price' => 299990, 'image' => '/images/hpe_proliant_dl380.png', 'description' => 'Dual Xeon Silver 4210R | 64GB RAM | 2x 1.2TB SAS HDD | 2U', 'specifications' => ['Processor' => '2x Intel Xeon Silver 4210R', 'RAM' => '64GB DDR4', 'Storage' => '2x 1.2TB SAS HDD', 'Form Factor' => '2U Rack-mountable']],
            ['name' => 'HPE Proliant DL360 Gen10 Server', 'price' => 220000, 'image' => '/images/hpe_proliant_dl380.png', 'description' => 'Xeon Silver 4114 | 32GB RAM | 2x 600GB SAS HDD | 1U', 'specifications' => ['Processor' => 'Intel Xeon Silver 4114', 'RAM' => '32GB DDR4', 'Storage' => '2x 600GB SAS HDD', 'Form Factor' => '1U Rack-mountable']],
            ['name' => 'HPE Proliant DL380 Gen9 Server', 'price' => 99990, 'image' => '/images/hpe_proliant_dl380.png', 'description' => 'Dual Xeon E5-2660 v3 | 64GB RAM | 2x 1.2TB SAS HDD | 2U', 'specifications' => ['Processor' => '2x Intel Xeon E5-2660 v3', 'RAM' => '64GB DDR4', 'Storage' => '2x 1.2TB SAS HDD', 'Form Factor' => '2U Rack-mountable']],
            ['name' => 'HP ProLiant DL380 G8 12-Core', 'price' => 119990, 'image' => '/images/hp_proliant_dl_2u.png', 'description' => 'Intel Xeon E5-2609 12Core | 128GB RAM | 600GB SAS HDD | 1U', 'specifications' => ['Processor' => 'Intel Xeon E5-2609 (12 Core)', 'RAM' => '128GB', 'Storage' => '600GB SAS HDD', 'Form Factor' => '1U Rack-mountable']],
            ['name' => 'HPE ProLiant ML110 Gen 10', 'price' => 249990, 'image' => '/images/hp_proliant_ml.png', 'description' => 'Xeon Gold 520 10core | 32GB RAM | 1.2TB SAS HDD', 'specifications' => ['Processor' => 'Xeon Gold 520 (10 Core)', 'RAM' => '32GB', 'Storage' => '1.2TB SAS HDD', 'Form Factor' => 'Tower']],
            ['name' => 'HPE ML30 Gen 10 Plus', 'price' => 259990, 'image' => '/images/hp_proliant_ml.png', 'description' => 'E-2314 1P | 16GB RAM | 1TB HDD', 'specifications' => ['Processor' => 'Intel E-2314', 'RAM' => '16GB', 'Storage' => '1TB HDD', 'Form Factor' => 'Tower']],
            ['name' => 'HP ProLiant DL380 G10 20-Core', 'price' => 319990, 'image' => '/images/hp_proliant_dl_2u.png', 'description' => 'Intel Xeon 4210 20Core | 64GB RAM | 1.2TB SAS HDD | 2U', 'specifications' => ['Processor' => 'Intel Xeon 4210 (20 Core)', 'RAM' => '64GB', 'Storage' => '1.2TB SAS HDD', 'Form Factor' => '2U Rack-mountable']],
            ['name' => 'HP ProLiant ML30 G9', 'price' => 145000, 'image' => '/images/hp_proliant_ml.png', 'description' => 'Intel Xeon E3-1230 V6 4Core | 8GB RAM | 1TB HDD', 'specifications' => ['Processor' => 'Intel Xeon E3-1230 V6 (4 Core)', 'RAM' => '8GB', 'Storage' => '1TB HDD', 'Form Factor' => 'Tower']],
            ['name' => 'HP ProLiant ML30 Gen 10', 'price' => 229999, 'image' => '/images/hp_proliant_ml.png', 'description' => 'E-2124 4Core | 8GB RAM | 1TB HDD', 'specifications' => ['Processor' => 'Intel E-2124 (4 Core)', 'RAM' => '8GB', 'Storage' => '1TB HDD', 'Form Factor' => 'Tower']],
            ['name' => 'HP Store Once 3640', 'price' => 299990, 'image' => '/images/storage_server.png', 'description' => 'HP Store Once Data Sheet 3640 Backup Appliance', 'specifications' => ['Product Type' => 'Backup Appliance']],

            // Dell Servers
            ['name' => 'Dell Poweredge R760 Rack Server', 'price' => 799990, 'image' => '/images/dell_poweredge_r760.png', 'description' => 'Dual Xeon Gold 6430 | 128GB DDR5 RAM | 2x 2.4TB SAS HDD | 2U', 'specifications' => ['Processor' => '2x Intel Xeon Gold 6430', 'RAM' => '128GB DDR5', 'Storage' => '2x 2.4TB SAS HDD', 'Form Factor' => '2U Rack-mountable']],
            ['name' => 'Dell Poweredge R740 Rack Server', 'price' => 280000, 'image' => '/images/dell_poweredge_r760.png', 'description' => 'Dual Xeon Silver 4114 | 64GB RAM | 2x 1.2TB SAS HDD | 2U', 'specifications' => ['Processor' => '2x Intel Xeon Silver 4114', 'RAM' => '64GB DDR4', 'Storage' => '2x 1.2TB SAS HDD', 'Form Factor' => '2U Rack-mountable']],
            ['name' => 'Dell Poweredge R640 Rack Server', 'price' => 240000, 'image' => '/images/dell_poweredge_r_1u.png', 'description' => 'Xeon Silver 4114 | 32GB RAM | 2x 600GB SAS HDD | 1U', 'specifications' => ['Processor' => 'Intel Xeon Silver 4114', 'RAM' => '32GB DDR4', 'Storage' => '2x 600GB SAS HDD', 'Form Factor' => '1U Rack-mountable']],
            ['name' => 'Dell Poweredge R730 Rack Server', 'price' => 120000, 'image' => '/images/dell_poweredge_r760.png', 'description' => 'Dual Xeon E5-2660 v3 | 64GB RAM | 2x 1.2TB SAS HDD | 2U', 'specifications' => ['Processor' => '2x Intel Xeon E5-2660 v3', 'RAM' => '64GB DDR4', 'Storage' => '2x 1.2TB SAS HDD', 'Form Factor' => '2U Rack-mountable']],
            ['name' => 'Dell PowerEdge R930', 'price' => 219000, 'image' => '/images/dell_poweredge_r_4u.png', 'description' => 'Intel Xeon E7-4830v3 24Core | 64GB RAM | 900GB SAS HDD | 4U', 'specifications' => ['Processor' => 'Intel Xeon E7-4830v3 (24 Core)', 'RAM' => '64GB', 'Storage' => '900GB SAS HDD', 'Form Factor' => '4U Rack-mountable']],
            ['name' => 'Dell PowerEdge R910', 'price' => 109990, 'image' => '/images/dell_poweredge_r_4u.png', 'description' => 'Intel Xeon E5-2670v2 16Core | 128GB RAM | 900GB SAS HDD | 4U', 'specifications' => ['Processor' => 'Intel Xeon E5-2670v2 (16 Core)', 'RAM' => '128GB', 'Storage' => '900GB SAS HDD', 'Form Factor' => '4U Rack-mountable']],
            ['name' => 'Dell PowerEdge R420', 'price' => 109990, 'image' => '/images/dell_poweredge_r_1u.png', 'description' => 'Intel E5-2420 12Core | 128GB RAM | 600GB SAS HDD | 1U', 'specifications' => ['Processor' => 'Intel E5-2420 (12 Core)', 'RAM' => '128GB', 'Storage' => '600GB SAS HDD', 'Form Factor' => '1U Rack-mountable']],
            ['name' => 'Dell PowerEdge R730 16-Core', 'price' => 219990, 'image' => '/images/dell_poweredge_r_2u.png', 'description' => 'Intel E5-2667v3 16Core | 64GB RAM | 600GB SAS HDD | 2U', 'specifications' => ['Processor' => 'Intel E5-2667v3 (16 Core)', 'RAM' => '64GB', 'Storage' => '600GB SAS HDD', 'Form Factor' => '2U Rack-mountable']],
            ['name' => 'Dell PowerEdge R720', 'price' => 119990, 'image' => '/images/dell_poweredge_r_2u.png', 'description' => 'Intel E5-2670 16Core | 128GB RAM | 600GB SAS HDD | 2U', 'specifications' => ['Processor' => 'Intel E5-2670 (16 Core)', 'RAM' => '128GB', 'Storage' => '600GB SAS HDD', 'Form Factor' => '2U Rack-mountable']],
            ['name' => 'Dell PowerEdge R730 28-Core', 'price' => 239990, 'image' => '/images/dell_poweredge_r_2u.png', 'description' => 'Intel Xeon E5-2683v3 28Core | 64GB RAM | 600GB SAS HDD', 'specifications' => ['Processor' => 'Intel Xeon E5-2683v3 (28 Core)', 'RAM' => '64GB', 'Storage' => '600GB SAS HDD']],
            ['name' => 'Dell PowerEdge R730xd', 'price' => 249990, 'image' => '/images/dell_poweredge_r_2u.png', 'description' => 'Intel Xeon E5-2620 v4 16Core | 32GB RAM', 'specifications' => ['Processor' => 'Intel Xeon E5-2620 v4 (16 Core)', 'RAM' => '32GB']],
            ['name' => 'Dell PowerEdge R240', 'price' => 269990, 'image' => '/images/dell_poweredge_r_1u.png', 'description' => 'Intel Xeon E-2144G | 16GB RAM | Brand New', 'specifications' => ['Processor' => 'Intel Xeon E-2144G', 'RAM' => '16GB', 'Condition' => 'Brand New']],
            ['name' => 'Dell PowerEdge R640', 'price' => 299990, 'image' => '/images/dell_poweredge_r_1u.png', 'description' => 'Intel Xeon Silver 4110 16Core | 64GB RAM | 1.2TB SAS HDD | 1U', 'specifications' => ['Processor' => 'Intel Xeon Silver 4110 (16 Core)', 'RAM' => '64GB', 'Storage' => '1.2TB SAS HDD', 'Form Factor' => '1U Rack-mountable']],
            ['name' => 'Dell PowerEdge R630', 'price' => 219000, 'image' => '/images/dell_poweredge_r_1u.png', 'description' => 'Intel E5-2620 12Core | 64GB RAM | 600GB SAS HDD | 1U', 'specifications' => ['Processor' => 'Intel E5-2620 (12 Core)', 'RAM' => '64GB', 'Storage' => '600GB SAS HDD', 'Form Factor' => '1U Rack-mountable']],

            // Cisco & Storage
            ['name' => 'CISCO Server UCS C240-M5', 'price' => 149990, 'image' => '/images/cisco_ucs_c240.png', 'description' => 'Xeon Silver 4210 10Core | 64GB RAM | 2TB SAS HDD | 2U', 'specifications' => ['Processor' => 'Xeon Silver 4210 (10 Core)', 'RAM' => '64GB', 'Storage' => '2TB SAS HDD', 'Form Factor' => '2U Rack-mountable']],
            ['name' => 'Dell MD 1420', 'price' => 119990, 'image' => '/images/storage_server.png', 'description' => 'Direct Attached Storage Enclosure', 'specifications' => ['Product Type' => 'Storage Enclosure']],
            ['name' => 'Dell Equallogic PS4000', 'price' => 99990, 'image' => '/images/storage_server.png', 'description' => 'Dell Equallogic PS4000 Server Storage', 'specifications' => ['Product Type' => 'SAN Storage']],
        ];

        foreach ($products as $productData) {
            $productData['slug'] = Str::slug($productData['name']);
            $productData['category_id'] = $category->id;
            $productData['stock'] = 10;
            $productData['is_featured'] = false;
            $productData['status'] = 'Certified Refurbished';
            
            Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }
    }
}
