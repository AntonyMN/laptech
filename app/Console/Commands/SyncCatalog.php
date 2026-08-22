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

        // Step 1: Archive ALL existing products
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

        // Record the timestamp so we can identify which products were touched.
        $seedStart = now()->subSecond();

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

            $this->info('✓ Catalog seeded.');

            // Step 3: Un-archive products that the seeders just created/updated.
            // updateOrCreate touches updated_at, so any product with
            // updated_at >= $seedStart was part of the local catalog.
            $reactivated = Product::where('is_archived', true)
                ->where('updated_at', '>=', $seedStart)
                ->update([
                    'is_archived' => false,
                    'archived_at' => null,
                ]);
            $this->info("✓ Reactivated {$reactivated} seeded product(s).");
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
