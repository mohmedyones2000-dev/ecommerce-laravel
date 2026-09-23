<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductImportExportService
{
    /**
     * ✅ تصدير المنتجات إلى CSV
     */
    public static function export(?array $filters = []): array
    {
        try {
            $query = Product::with(['category', 'subCategory', 'brand']);

            // ✅ تطبيق الفلاتر (تصدير مفلتر)
            if (!empty($filters['category_id'])) {
                $query->where('category_id', $filters['category_id']);
            }
            if (!empty($filters['brand_id'])) {
                $query->where('brand_id', $filters['brand_id']);
            }
            if (!empty($filters['is_active'])) {
                $query->where('is_active', $filters['is_active']);
            }
            if (!empty($filters['is_featured'])) {
                $query->where('is_featured', $filters['is_featured']);
            }

            $products = $query->latest()->get();

            // ✅ رأس الملف (Headers)
            $headers = [
                'ID',
                'الاسم',
                'Slug',
                'الوصف',
                'السعر',
                'سعر الخصم',
                'التصنيف',
                'التصنيف الفرعي',
                'الماركة',
                'نشط',
                'مميز',
                'تاريخ الإنشاء',
            ];

            // ✅ تجهيز الصفوف
            $rows = [];
            foreach ($products as $product) {
                $rows[] = [
                    $product->id,
                    $product->name,
                    $product->slug,
                    strip_tags($product->description ?? ''),
                    $product->price,
                    $product->discount_price ?? '',
                    $product->category?->name ?? '',
                    $product->subCategory?->name ?? '',
                    $product->brand?->name ?? '',
                    $product->is_active ? 'نعم' : 'لا',
                    $product->is_featured ? 'نعم' : 'لا',
                    $product->created_at->format('Y-m-d H:i'),
                ];
            }

            // ✅ تسجيل النجاح
            Log::channel('audit')->info('Products exported to CSV', [
                'event'     => 'products.export.success',
                'count'     => count($rows),
                'user_id'   => auth()->id(),
            ]);

            return [
                'success' => true,
                'headers' => $headers,
                'rows'    => $rows,
                'count'   => count($rows),
            ];

        } catch (\Exception $e) {
            Log::channel('audit')->error('Products export failed', [
                'event'   => 'products.export.failed',
                'error'   => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return [
                'success' => false,
                'message' => 'فشل التصدير: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * ✅ إنشاء ملف CSV للتحميل
     */
    public static function generateCsvContent(array $data): string
    {
        $output = fopen('php://temp', 'r+');

        // ✅ UTF-8 BOM لدعم العربية في Excel
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // ✅ Headers
        fputcsv($output, $data['headers']);

        // ✅ Rows
        foreach ($data['rows'] as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        return $content;
    }

    /**
     * ✅ استيراد المنتجات من CSV
     */
    public static function import(UploadedFile $file): array
    {
        $results = [
            'success'       => true,
            'total'         => 0,
            'imported'      => 0,
            'failed'        => 0,
            'errors'        => [],
            'message'       => '',
        ];

        try {
            // ✅ 1. فتح الملف
            $handle = fopen($file->getRealPath(), 'r');

            if (!$handle) {
                throw new \Exception('لا يمكن قراءة الملف');
            }

            // ✅ 2. تخطي BOM إذا وُجد
            $bom = fread($handle, 3);
            if ($bom !== chr(0xEF) . chr(0xBB) . chr(0xBF)) {
                rewind($handle);
            }

            // ✅ 3. قراءة الرأس
            $headers = fgetcsv($handle);

            if (!$headers) {
                throw new \Exception('الملف فارغ أو غير صالح');
            }

            // ✅ 4. قراءة الصفوف
            $rowNumber = 1; // نبدأ من 1 (الرأس)

            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                $results['total']++;

                // ✅ 5. تحويل الصف لمصفوفة (اسم العمود => القيمة)
                $data = [];
                foreach ($headers as $index => $header) {
                    $data[trim($header)] = $row[$index] ?? null;
                }

                // ✅ 6. التحقق من الصف
                $validation = self::validateRow($data, $rowNumber);

                if (!$validation['valid']) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row'    => $rowNumber,
                        'errors' => $validation['errors'],
                    ];
                    continue;
                }

                // ✅ 7. إدخال الصف
                try {
                    self::importRow($data);
                    $results['imported']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row'    => $rowNumber,
                        'errors' => [$e->getMessage()],
                    ];
                }
            }

            fclose($handle);

            $results['message'] = "تم استيراد {$results['imported']} من {$results['total']} سجل";

            // ✅ 8. تسجيل النتيجة
            Log::channel('audit')->info('Products imported from CSV', [
                'event'    => 'products.import.completed',
                'total'    => $results['total'],
                'imported' => $results['imported'],
                'failed'   => $results['failed'],
                'user_id'  => auth()->id(),
                'errors'   => $results['errors'],
            ]);

            return $results;

        } catch (\Exception $e) {
            Log::channel('audit')->error('Products import failed', [
                'event'   => 'products.import.failed',
                'error'   => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return [
                'success' => false,
                'message' => 'فشل الاستيراد: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * ✅ التحقق من صحة الصف
     */
    private static function validateRow(array $data, int $rowNumber): array
    {
        $errors = [];

        // ✅ 1. الاسم مطلوب
        $name = trim($data['الاسم'] ?? '');
        if (empty($name)) {
            $errors[] = 'الاسم مطلوب';
        } elseif (mb_strlen($name) > 255) {
            $errors[] = 'الاسم طويل جداً (الحد الأقصى 255 حرف)';
        }

        // ✅ 2. السعر مطلوب ورقم
        $price = $data['السعر'] ?? '';
        if (empty($price)) {
            $errors[] = 'السعر مطلوب';
        } elseif (!is_numeric($price)) {
            $errors[] = 'السعر يجب أن يكون رقماً';
        } elseif ($price < 0) {
            $errors[] = 'السعر لا يمكن أن يكون سالباً';
        }

        // ✅ 3. التصنيف مطلوب ويجب أن يكون موجوداً
        $categoryName = trim($data['التصنيف'] ?? '');
        if (empty($categoryName)) {
            $errors[] = 'التصنيف مطلوب';
        } else {
            $category = Category::where('name', $categoryName)->first();
            if (!$category) {
                $errors[] = "التصنيف '{$categoryName}' غير موجود";
            }
        }

        // ✅ 4. الماركة (اختياري) — إذا موجودة يجب أن تكون صحيحة
        $brandName = trim($data['الماركة'] ?? '');
        if (!empty($brandName)) {
            $brand = Brand::where('name', $brandName)->first();
            if (!$brand) {
                $errors[] = "الماركة '{$brandName}' غير موجودة";
            }
        }

        // ✅ 5. التصنيف الفرعي (اختياري) — إذا موجود يجب أن يكون صحيحاً
        $subCategoryName = trim($data['التصنيف الفرعي'] ?? '');
        if (!empty($subCategoryName)) {
            $subCategory = SubCategory::where('name', $subCategoryName)->first();
            if (!$subCategory) {
                $errors[] = "التصنيف الفرعي '{$subCategoryName}' غير موجود";
            }
        }

        // ✅ 6. سعر الخصم يجب أن يكون أقل من السعر
        $discountPrice = $data['سعر الخصم'] ?? '';
        if (!empty($discountPrice) && is_numeric($discountPrice)) {
            if ($discountPrice >= $price) {
                $errors[] = 'سعر الخصم يجب أن يكون أقل من السعر';
            }
        }

        return [
            'valid'  => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * ✅ إدخال صف واحد
     */
    private static function importRow(array $data): void
    {
        $category = Category::where('name', trim($data['التصنيف']))->firstOrFail();

        $subCategory = !empty($data['التصنيف الفرعي'])
            ? SubCategory::where('name', trim($data['التصنيف الفرعي']))->first()
            : null;

        $brand = !empty($data['الماركة'])
            ? Brand::where('name', trim($data['الماركة']))->first()
            : null;

        Product::create([
            'name'            => trim($data['الاسم']),
            'slug'            => Str::slug($data['الاسم']) . '-' . uniqid(),
            'description'     => $data['الوصف'] ?? null,
            'price'           => (float) $data['السعر'],
            'discount_price'  => !empty($data['سعر الخصم']) ? (float) $data['سعر الخصم'] : null,
            'category_id'     => $category->id,
            'sub_category_id' => $subCategory?->id,
            'brand_id'        => $brand?->id,
            'is_active'       => ($data['نشط'] ?? 'نعم') === 'نعم',
            'is_featured'     => ($data['مميز'] ?? 'لا') === 'نعم',
        ]);
    }

    /**
     * ✅ إنشاء ملف قالب للاستيراد
     */
    public static function generateTemplate(): string
    {
        $headers = [
            'الاسم',
            'الوصف',
            'السعر',
            'سعر الخصم',
            'التصنيف',
            'التصنيف الفرعي',
            'الماركة',
            'نشط',
            'مميز',
        ];

        // ✅ صف نموذجي
        $sampleRow = [
            'قميص قطني رجالي',
            'قميص قطني 100% بتصميم عصري',
            '150',
            '120',
            'ملابس رجالية',
            'قمصان',
            'Zara',
            'نعم',
            'لا',
        ];

        $output = fopen('php://temp', 'r+');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, $headers);
        fputcsv($output, $sampleRow);
        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        return $content;
    }
}