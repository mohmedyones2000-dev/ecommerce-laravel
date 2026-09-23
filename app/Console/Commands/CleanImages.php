<?php

namespace App\Console\Commands;

use App\Models\ProductImage;
use Illuminate\Console\Command;

class CleanImages extends Command
{
    protected $signature = 'images:clean';
    protected $description = 'حذف الصور القديمة';

    public function handle(): void
    {
        $images = ProductImage::where('created_at', '<', now()->subMonths(6))->get();

        foreach ($images as $image) {
            // ✅ Observer يعمل تلقائياً — لا نحتاج كتابة أي منطق إضافي
            $image->delete();
        }

        $this->info("تم حذف {$images->count()} صورة");
    }
}