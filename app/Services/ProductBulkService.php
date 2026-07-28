<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Bulk product export / import as an .xlsx spreadsheet.
 *
 * Rows are matched back to products by the immutable numeric "Product ID".
 * A blank ID creates a new product; a filled ID updates the existing one.
 * Unknown category names are created on import (surfaced in the preview first).
 */
class ProductBulkService
{
    public const STATUSES = ['Brand new', 'Ex-UK', 'Certified Refurbished'];

    /** Canonical column labels, in export order (A..G). */
    public const COLUMNS = ['Product ID', 'Name', 'Category', 'Status', 'Price', 'Stock', 'Image URL'];

    /** Extra blank rows (beyond current stock) that still get dropdowns, so new products can be added. */
    private const SPARE_ROWS = 200;

    // ---- Export ---------------------------------------------------------

    public function buildExport(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Products');

        // Header
        foreach (self::COLUMNS as $i => $label) {
            $sheet->setCellValue([$i + 1, 1], $label);
        }
        $sheet->getStyle('A1:G1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:G1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1A1A1A');
        $sheet->getComment('A1')->getText()->createText('Do not edit or delete this column — it links each row to an existing product. Leave blank to add a new product.');

        // Data rows
        $products = Product::with('category')->orderBy('id')->get();
        $row = 2;
        foreach ($products as $p) {
            $sheet->setCellValue([1, $row], $p->id);
            $sheet->setCellValue([2, $row], $p->name);
            $sheet->setCellValue([3, $row], $p->category?->name);
            $sheet->setCellValue([4, $row], $p->status ?: 'Brand new');
            $sheet->setCellValue([5, $row], $p->price);
            $sheet->setCellValue([6, $row], $p->stock);
            $sheet->setCellValue([7, $row], $p->image);
            $row++;
        }
        $lastDataRow = $row - 1;
        $validationEnd = max($lastDataRow, 1) + self::SPARE_ROWS;

        // Hidden reference sheet feeding the dropdowns
        $categories = Category::orderBy('name')->pluck('name')->values();
        $lists = $spreadsheet->createSheet();
        $lists->setTitle('Lists');
        foreach (self::STATUSES as $i => $status) {
            $lists->setCellValue([1, $i + 1], $status);
        }
        foreach ($categories as $i => $name) {
            $lists->setCellValue([2, $i + 1], $name);
        }
        $lists->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

        // Dropdowns: Category (C) + Status (D)
        $this->applyListValidation(
            $sheet,
            "C2:C{$validationEnd}",
            'Lists!$B$1:$B$' . max($categories->count(), 1),
            'Pick an existing category, or type a new one to create it on import.'
        );
        $this->applyListValidation(
            $sheet,
            "D2:D{$validationEnd}",
            'Lists!$A$1:$A$' . count(self::STATUSES),
            'Choose the product condition.'
        );

        // Visually mark the read-only ID column
        if ($lastDataRow >= 2) {
            $sheet->getStyle("A2:A{$lastDataRow}")->getFill()
                ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EFEFEF');
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->freezePane('A2');
        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    private function applyListValidation(Worksheet $sheet, string $range, string $formula, string $prompt): void
    {
        $validation = new DataValidation();
        $validation->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('Invalid entry')
            ->setError('Please choose a value from the dropdown list.')
            ->setPromptTitle('Select a value')
            ->setPrompt($prompt)
            ->setFormula1($formula);

        $sheet->setDataValidation($range, $validation);
    }

    // ---- Import ---------------------------------------------------------

    /** Read an uploaded spreadsheet into normalized row arrays. */
    public function rowsFromFile(UploadedFile $file): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getSheetByName('Products') ?? $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, false, false);

        if (count($data) < 1) {
            return [];
        }

        $header = array_map(fn ($h) => $this->canonicalKey((string) $h), $data[0]);
        $index = [];
        foreach ($header as $i => $key) {
            if ($key !== '') {
                $index[$key] = $i;
            }
        }

        $rows = [];
        for ($i = 1; $i < count($data); $i++) {
            $raw = $data[$i];
            if (count(array_filter($raw, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue; // skip blank rows
            }
            $rows[] = [
                'row_number' => $i + 1,
                'id' => $this->cell($raw, $index, 'id'),
                'name' => $this->cell($raw, $index, 'name'),
                'category' => $this->cell($raw, $index, 'category'),
                'status' => $this->cell($raw, $index, 'status'),
                'price' => $this->cell($raw, $index, 'price'),
                'stock' => $this->cell($raw, $index, 'stock'),
                'image' => $this->cell($raw, $index, 'image'),
            ];
        }

        return $rows;
    }

    /** Dry-run: validate rows and summarize what an import would do. */
    public function analyze(array $rows): array
    {
        $existing = Category::pluck('name')->mapWithKeys(fn ($n) => [mb_strtolower(trim($n)) => true]);
        $productIds = Product::pluck('id')->mapWithKeys(fn ($id) => [(int) $id => true]);

        $newCategories = [];
        $errors = [];
        $toCreate = 0;
        $toUpdate = 0;

        foreach ($rows as $r) {
            $rowErrors = [];
            $id = trim((string) $r['id']);
            $name = trim((string) $r['name']);
            $category = trim((string) $r['category']);
            $status = trim((string) $r['status']) ?: 'Brand new';
            $price = $r['price'];
            $stock = $r['stock'];

            if ($name === '') {
                $rowErrors[] = 'Name is required';
            }
            if ($category === '') {
                $rowErrors[] = 'Category is required';
            }
            if (! in_array($status, self::STATUSES, true)) {
                $rowErrors[] = "Invalid status \"{$status}\"";
            }
            if (! is_numeric($price) || (float) $price < 0) {
                $rowErrors[] = 'Price must be a number ≥ 0';
            }
            if (! is_numeric($stock) || (int) $stock != (float) $stock || (int) $stock < 0) {
                $rowErrors[] = 'Stock must be a whole number ≥ 0';
            }

            if ($id !== '') {
                if (! ctype_digit($id) || ! $productIds->has((int) $id)) {
                    $rowErrors[] = "Product ID {$id} does not exist";
                } else {
                    $toUpdate++;
                }
            } else {
                $toCreate++;
            }

            if ($category !== '' && ! $existing->has(mb_strtolower($category))) {
                $newCategories[mb_strtolower($category)] = $category;
            }

            if ($rowErrors) {
                $errors[] = ['row' => $r['row_number'], 'messages' => $rowErrors];
            }
        }

        return [
            'total' => count($rows),
            'toCreate' => $toCreate,
            'toUpdate' => $toUpdate,
            'newCategories' => array_values($newCategories),
            'errors' => $errors,
            'valid' => count($rows) > 0 && count($errors) === 0,
        ];
    }

    /** Apply the import inside a transaction. Throws if the rows don't validate. */
    public function commit(array $rows): array
    {
        $analysis = $this->analyze($rows);
        if (! $analysis['valid']) {
            throw new \RuntimeException('The spreadsheet has validation errors and was not imported.');
        }

        $created = 0;
        $updated = 0;
        $categoriesCreated = 0;

        DB::transaction(function () use ($rows, &$created, &$updated, &$categoriesCreated) {
            $catCache = Category::all()->keyBy(fn ($c) => mb_strtolower(trim($c->name)));

            foreach ($rows as $r) {
                $id = trim((string) $r['id']);
                $name = trim((string) $r['name']);
                $categoryName = trim((string) $r['category']);
                $status = trim((string) $r['status']) ?: 'Brand new';
                $price = (int) round((float) $r['price']);
                $stock = (int) $r['stock'];
                $image = trim((string) $r['image']);

                $key = mb_strtolower($categoryName);
                if (! $catCache->has($key)) {
                    $category = Category::create([
                        'name' => $categoryName,
                        'slug' => $this->uniqueSlug($categoryName, Category::class),
                    ]);
                    $catCache->put($key, $category);
                    $categoriesCreated++;
                }
                $categoryId = $catCache->get($key)->id;

                if ($id !== '') {
                    $product = Product::find((int) $id);
                    if (! $product) {
                        continue;
                    }
                    $product->name = $name;
                    $product->category_id = $categoryId;
                    $product->status = $status;
                    $product->price = $price;
                    $product->stock = $stock;
                    if ($image !== '') {
                        $product->image = $image;
                    }
                    $product->save();
                    $updated++;
                } else {
                    Product::create([
                        'name' => $name,
                        'slug' => $this->uniqueSlug($name, Product::class),
                        'description' => '',
                        'category_id' => $categoryId,
                        'status' => $status,
                        'price' => $price,
                        'stock' => $stock,
                        'image' => $image !== '' ? $image : null,
                    ]);
                    $created++;
                }
            }
        });

        return compact('created', 'updated', 'categoriesCreated');
    }

    // ---- Helpers --------------------------------------------------------

    private function canonicalKey(string $label): string
    {
        return match (mb_strtolower(trim($label))) {
            'product id', 'id' => 'id',
            'name', 'product name' => 'name',
            'category' => 'category',
            'status', 'condition' => 'status',
            'price' => 'price',
            'stock', 'quantity', 'qty' => 'stock',
            'image url', 'image', 'image path' => 'image',
            default => '',
        };
    }

    private function cell(array $raw, array $index, string $key): string
    {
        if (! isset($index[$key])) {
            return '';
        }

        return trim((string) ($raw[$index[$key]] ?? ''));
    }

    private function uniqueSlug(string $value, string $modelClass): string
    {
        $base = Str::slug($value) ?: 'item';
        $slug = $base;
        $i = 2;
        while ($modelClass::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
