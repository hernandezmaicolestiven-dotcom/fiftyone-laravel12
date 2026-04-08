<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DownloadProductImages extends Command
{
    protected $signature = 'products:download-images';
    protected $description = 'Download product images from URLs to local storage';

    public function handle(): void
    {
        $products = Product::whereNotNull('image')->get();
        $count = 0;

        foreach ($products as $product) {
            if (! str_starts_with($product->image, 'http')) {
                $this->line("Skip (already local): {$product->name}");
                continue;
            }

            try {
                $ctx = stream_context_create(['http' => ['timeout' => 15, 'user_agent' => 'Mozilla/5.0']]);
                $img = @file_get_contents($product->image, false, $ctx);

                if ($img) {
                    $path = 'products/product_' . $product->id . '.jpg';
                    Storage::disk('public')->put($path, $img);
                    $product->update(['image' => $path]);
                    $count++;
                    $this->info("OK: {$product->name}");
                } else {
                    $this->warn("FAIL (empty): {$product->name}");
                }
            } catch (\Exception $e) {
                $this->error("FAIL: {$product->name} — {$e->getMessage()}");
            }
        }

        $this->info("Done. Downloaded: {$count} images.");
    }
}
