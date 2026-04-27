<?php

use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

// Boot Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting image population...\n";

$sourceFile = __DIR__ . '/../thomas.webp';
$targetDir = public_path('images/products');

if (!File::exists($sourceFile)) {
    die("Error: thomas.webp not found in project root.\n");
}

if (!File::exists($targetDir)) {
    File::makeDirectory($targetDir, 0755, true);
    echo "Created directory: {$targetDir}\n";
}

$products = Product::all();
$count = 0;

foreach ($products as $product) {
    $imageName = $product->slug . '.webp';
    $targetPath = $targetDir . '/' . $imageName;
    $dbPath = 'images/products/' . $imageName;

    // Copy the file
    if (File::copy($sourceFile, $targetPath)) {
        // Update database
        $product->main_image = $dbPath;
        $product->save();
        
        echo "Processed [{$product->id}]: {$product->name} -> {$imageName}\n";
        $count++;
    } else {
        echo "Failed to copy for product [{$product->id}]: {$product->name}\n";
    }
}

echo "\nDone! Processed {$count} products.\n";
