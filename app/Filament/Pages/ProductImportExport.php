<?php

namespace App\Filament\Pages;

use App\Services\ProductImportExportService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Http\UploadedFile;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductImportExport extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationGroup = 'إدارة المتجر';
    protected static ?string $navigationLabel = 'استيراد/تصدير';
    protected static ?string $title = 'استيراد وتصدير المنتجات';
    protected static ?int $navigationSort = 98;
    protected static string $view = 'filament.pages.product-import-export';

    // ✅ بيانات الفورم
    public ?UploadedFile $file = null;

    // ✅ فلاتر التصدير
    public ?int $export_category_id = null;
    public ?bool $export_is_active = null;

    // ✅ نتائج الاستيراد
    public array $importResults = [];
    public bool $showImportResults = false;

    /**
     * ✅ تصدير المنتجات
     */
    public function export(): StreamedResponse
    {
        $filters = array_filter([
            'category_id' => $this->export_category_id,
            'is_active'   => $this->export_is_active,
        ]);

        $data = ProductImportExportService::export($filters);

        if (!$data['success']) {
            Notification::make()
                ->title($data['message'])
                ->danger()
                ->send();

            return response()->streamDownload(fn() => '', 'error.csv');
        }

        $csv = ProductImportExportService::generateCsvContent($data);

        // ✅ إشعار النجاح
        Notification::make()
            ->title("تم تصدير {$data['count']} منتج بنجاح")
            ->success()
            ->send();

        // ✅ تسجيل الحدث في logs
        \Illuminate\Support\Facades\Log::channel('audit')->info('Products exported', [
            'event'   => 'products.export',
            'count'   => $data['count'],
            'user_id' => auth()->id(),
        ]);

        $filename = 'products-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * ✅ تحميل قالب الاستيراد
     */
    public function downloadTemplate(): StreamedResponse
    {
        $csv = ProductImportExportService::generateTemplate();

        $filename = 'products-import-template.csv';

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * ✅ استيراد المنتجات
     */
    public function import(): void
    {
        // ✅ التحقق من الملف
        $this->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'file.required' => 'يجب رفع ملف CSV',
            'file.mimes'    => 'الملف يجب أن يكون بصيغة CSV',
            'file.max'      => 'حجم الملف يجب أن يكون أقل من 2MB',
        ]);

        $result = ProductImportExportService::import($this->file);

        if (!$result['success']) {
            Notification::make()
                ->title($result['message'])
                ->danger()
                ->send();
            return;
        }

        // ✅ إظهار النتائج
        $this->importResults = $result;
        $this->showImportResults = true;

        // ✅ إشعار
        if ($result['failed'] > 0) {
            Notification::make()
                ->title("تم استيراد {$result['imported']} سجل، فشل {$result['failed']} سجل")
                ->warning()
                ->send();
        } else {
            Notification::make()
                ->title("تم استيراد {$result['imported']} سجل بنجاح")
                ->success()
                ->send();
        }

        // ✅ إعادة تعيين الملف
        $this->file = null;
    }
}