<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class SyncCatalog extends Command
{
    protected $signature = 'app:sync-catalog
                            {--dry-run : Show what would happen without making changes}
                            {--skip-archive : Skip archiving existing products (only seed new)}';

    protected $description = 'Archive existing products and seed the local catalog (one-time deployment helper)';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $skipArchive = $this->option('skip-archive');

        if ($dryRun) {
            $this->warn('DRY RUN — no changes will be made.');
        }

        // Step 1: Archive existing products
        if (!$skipArchive) {
            $activeCount = Product::where('is_archived', false)->count();
            $this->info("Found {$activeCount} active product(s) to archive.");

            if (!$dryRun && $activeCount > 0) {
                Product::where('is_archived', false)->update([
                    'is_archived' => true,
                    'archived_at' => now(),
                ]);
                $this->info("✓ Archived {$activeCount} product(s).");
            }
        } else {
            $this->info('Skipping archive step (--skip-archive).');
        }

        // Step 2: Run the product seeders to create/update the local catalog
        $this->info('Seeding the local product catalog...');

        if (!$dryRun) {
            $seeders = [
                \Database\Seeders\DatabaseSeeder::class,
            ];

            foreach ($seeders as $seeder) {
                $this->info("  Running " . class_basename($seeder) . "...");
                $this->callSilent('db:seed', [
                    '--class' => $seeder,
                    '--force' => true,
                ]);
            }

            // Ensure all newly seeded products are active
            // (seeders use updateOrCreate, so they won't have is_archived set — already default false)
            $this->info('✓ Catalog seeded.');
        } else {
            $this->info('  Would run: DatabaseSeeder (includes all product seeders).');
        }

        // Summary
        $totalActive = Product::where('is_archived', false)->count();
        $totalArchived = Product::where('is_archived', true)->count();
        $this->newLine();
        $this->info("Summary: {$totalActive} active product(s), {$totalArchived} archived product(s).");

        return self::SUCCESS;
    }
}
