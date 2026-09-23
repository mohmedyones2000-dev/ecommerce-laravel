<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * ✅ رفع ملف آمن مع UUID
     */
    public static function upload(
        UploadedFile $file,
        string $folder = 'uploads',
        string $disk = 'public'
    ): ?string {
        try {
            // ✅ 1. التحقق من النوع
            $allowedMimes = ['jpeg', 'jpg', 'png', 'webp', 'gif', 'svg', 'pdf'];
            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, $allowedMimes, true)) {
                throw new \Exception("نوع الملف غير مسموح: {$extension}");
            }

            // ✅ 2. التحقق من الحجم (2MB)
            if ($file->getSize() > 2048 * 1024) {
                throw new \Exception('حجم الملف يتجاوز 2 ميجابايت');
            }

            // ✅ 3. إعادة التسمية UUID
            $filename = Str::uuid() . '.' . $extension;
            $path = $folder . '/' . $filename;

            // ✅ 4. الرفع عبر Storage Facade
            Storage::disk($disk)->putFileAs($folder, $file, $filename);

            // ✅ 5. تسجيل النجاح
            Log::channel('audit')->info('File uploaded successfully', [
                'event'     => 'file.upload.success',
                'path'      => $path,
                'size'      => $file->getSize(),
                'mime'      => $file->getMimeType(),
                'user_id'   => auth()->id(),
            ]);

            return $path;

        } catch (\Exception $e) {
            // ✅ 6. تسجيل الفشل
            Log::channel('audit')->error('File upload failed', [
                'event'      => 'file.upload.failed',
                'error'      => $e->getMessage(),
                'file_name'  => $file->getClientOriginalName(),
                'file_size'  => $file->getSize(),
                'user_id'    => auth()->id(),
                'ip'         => request()->ip(),
            ]);

            return null;
        }
    }

    /**
     * ✅ حذف ملف آمن
     */
    public static function delete(?string $path, string $disk = 'public'): bool
    {
        if (!$path) {
            return false;
        }

        try {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);

                Log::channel('audit')->info('File deleted successfully', [
                    'event'   => 'file.delete.success',
                    'path'    => $path,
                    'user_id' => auth()->id(),
                ]);

                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::channel('audit')->error('File deletion failed', [
                'event'   => 'file.delete.failed',
                'error'   => $e->getMessage(),
                'path'    => $path,
                'user_id' => auth()->id(),
            ]);

            return false;
        }
    }

    /**
     * ✅ توليد URL للملف
     */
    public static function url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        // ✅ إذا كان Base64 (SVG مضمّن) — أعده كما هو
        if (str_starts_with($path, 'data:')) {
            return $path;
        }

        // ✅ إذا كان URL خارجي — أعده كما هو
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        // ✅ وإلا — استخدم Storage::url
        return Storage::url($path);
    }
}