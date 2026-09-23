<?php

namespace App\Observers;

use App\Models\ProductImage;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Log;

class ProductImageObserver
{
    /**
     * ✅ يُنفذ قبل حذف السجل من قاعدة البيانات
     */
    public function deleting(ProductImage $image): void
    {
        // ✅ حذف الملف من Storage
        FileUploadService::delete($image->image_path);

        // ✅ تسجيل الحدث
        Log::channel('audit')->info('Product image deleted (Observer)', [
            'event'       => 'product_image.deleting',
            'image_id'    => $image->id,
            'image_path'  => $image->image_path,
            'product_id'  => $image->product_id,
            'user_id'     => auth()->id(),
        ]);
    }

    /**
     * ✅ يُنفذ بعد حذف السجل
     */
    public function deleted(ProductImage $image): void
    {
        Log::channel('audit')->info('Product image deleted from DB (Observer)', [
            'event'      => 'product_image.deleted',
            'image_id'   => $image->id,
            'product_id' => $image->product_id,
            'user_id'    => auth()->id(),
        ]);
    }

    /**
     * ✅ (اختياري للمتميزين) — يُنفذ قبل تحديث السجل
     *    لحذف الصورة القديمة عند رفع صورة جديدة
     */
    public function updating(ProductImage $image): void
    {
        // ✅ إذا تغيّر مسار الصورة
        if ($image->isDirty('image_path')) {
            $oldPath = $image->getOriginal('image_path');

            // ✅ حذف القديمة (إذا لم تكن Base64)
            if ($oldPath && !str_starts_with($oldPath, 'data:')) {
                FileUploadService::delete($oldPath);

                Log::channel('audit')->info('Old product image deleted on update (Observer)', [
                    'event'       => 'product_image.updating',
                    'image_id'    => $image->id,
                    'old_path'    => $oldPath,
                    'new_path'    => $image->image_path,
                    'user_id'     => auth()->id(),
                ]);
            }
        }
    }   // ← ✅ قوس إغلاق `updating()`

    /**
     * ✅ يُنفذ بعد إنشاء سجل جديد
     */
    public function created(ProductImage $image): void
    {
        Log::channel('audit')->info('Product image created (Observer)', [
            'event'      => 'product_image.created',
            'image_id'   => $image->id,
            'image_path' => $image->image_path,
            'product_id' => $image->product_id,
            'user_id'    => auth()->id(),
        ]);
    }
}   // ← ✅ قوس إغلاق `class`