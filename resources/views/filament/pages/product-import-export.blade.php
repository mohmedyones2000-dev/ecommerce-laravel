<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ============ EXPORT SECTION ============ --}}
        <x-filament::section>
            <x-slot name="heading">
                <span class="inline-flex items-center gap-2">
                    <x-heroicon-o-arrow-down-tray class="w-5 h-5 text-success-600 dark:text-success-400" />
                    تصدير المنتجات
                </span>
            </x-slot>

            <x-slot name="description">
                قم بتصدير المنتجات إلى ملف CSV (يفتح في Excel)
            </x-slot>

            <div class="space-y-4">
                {{-- التصنيف --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                        التصنيف (اختياري)
                    </label>
                    <select wire:model="export_category_id"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-primary-500 focus:ring-primary-500">
                        <option value="">كل التصنيفات</option>
                        @foreach(\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- الحالة --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                        الحالة
                    </label>
                    <select wire:model="export_is_active"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-primary-500 focus:ring-primary-500">
                        <option value="">الكل</option>
                        <option value="1">النشطة فقط</option>
                        <option value="0">غير النشطة</option>
                    </select>
                </div>

                {{-- زر التصدير --}}
                <x-filament::button wire:click="export" icon="heroicon-o-arrow-down-tray" color="success" size="lg"
                    class="w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>تصدير إلى CSV</span>
                    <span wire:loading>جارٍ التصدير...</span>
                </x-filament::button>
            </div>
        </x-filament::section>

        {{-- ============ IMPORT SECTION ============ --}}
        <x-filament::section>
            <x-slot name="heading">
                <span class="inline-flex items-center gap-2">
                    <x-heroicon-o-arrow-up-tray class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                    استيراد المنتجات
                </span>
            </x-slot>

            <x-slot name="description">
                ارفع ملف CSV لإضافة منتجات دفعة واحدة
            </x-slot>

            <div class="space-y-4">
                {{-- تحميل القالب --}}
                <div class="rounded-lg border border-info-200 dark:border-info-800 bg-info-50 dark:bg-info-950/30 p-4">
                    <p class="text-sm text-info-800 dark:text-info-200 mb-3 flex items-start gap-2">
                        <x-heroicon-o-information-circle class="w-5 h-5 flex-shrink-0 mt-0.5" />
                        <span>ننصح بتحميل القالب أولاً لفهم الصيغة المطلوبة قبل الاستيراد.</span>
                    </p>
                    <x-filament::button wire:click="downloadTemplate" icon="heroicon-o-document-arrow-down" color="info"
                        size="sm">
                        تحميل قالب CSV
                    </x-filament::button>
                </div>

                {{-- رفع الملف --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                        ملف CSV <span class="text-danger-600">*</span>
                    </label>
                    <input type="file" wire:model="file" accept=".csv,.txt"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:bg-primary-600 file:text-white file:font-medium hover:file:bg-primary-700 cursor-pointer" />
                    @error('file')
                        <p class="text-danger-600 dark:text-danger-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- زر الاستيراد --}}
                <x-filament::button wire:click="import" icon="heroicon-o-arrow-up-tray" color="primary" size="lg"
                    class="w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>استيراد من CSV</span>
                    <span wire:loading>جارٍ الاستيراد...</span>
                </x-filament::button>
            </div>
        </x-filament::section>
    </div>

    {{-- ============ IMPORT RESULTS ============ --}}
    @if($showImportResults)
        <x-filament::section class="mt-6">
            <x-slot name="heading">
                <span class="inline-flex items-center gap-2">
                    <x-heroicon-o-chart-bar class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                    نتائج الاستيراد
                </span>
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                {{-- إجمالي --}}
                <div
                    class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 flex items-center justify-center gap-1">
                        <x-heroicon-o-document-text class="w-4 h-4" />
                        إجمالي الصفوف
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $importResults['total'] }}</p>
                </div>

                {{-- نجح --}}
                <div
                    class="rounded-lg border border-success-200 dark:border-success-800 bg-success-50 dark:bg-success-950/30 p-4 text-center">
                    <p class="text-xs text-success-700 dark:text-success-300 mb-1 flex items-center justify-center gap-1">
                        <x-heroicon-o-check-circle class="w-4 h-4" />
                        نُجح
                    </p>
                    <p class="text-2xl font-bold text-success-600 dark:text-success-400">{{ $importResults['imported'] }}
                    </p>
                </div>

                {{-- فشل --}}
                <div
                    class="rounded-lg border border-danger-200 dark:border-danger-800 bg-danger-50 dark:bg-danger-950/30 p-4 text-center">
                    <p class="text-xs text-danger-700 dark:text-danger-300 mb-1 flex items-center justify-center gap-1">
                        <x-heroicon-o-x-circle class="w-4 h-4" />
                        فشل
                    </p>
                    <p class="text-2xl font-bold text-danger-600 dark:text-danger-400">{{ $importResults['failed'] }}</p>
                </div>
            </div>

            @if(!empty($importResults['errors']))
                <div class="rounded-lg border border-danger-200 dark:border-danger-800 bg-danger-50 dark:bg-danger-950/30 p-4">
                    <h4 class="font-bold text-danger-800 dark:text-danger-200 mb-3 flex items-center gap-2">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                        الأخطاء:
                    </h4>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach($importResults['errors'] as $error)
                            <div
                                class="rounded bg-white dark:bg-gray-900 p-3 text-sm border border-danger-100 dark:border-danger-900">
                                <p class="font-medium text-danger-700 dark:text-danger-300">
                                    صف #{{ $error['row'] }}:
                                </p>
                                <ul class="list-disc list-inside text-danger-600 dark:text-danger-400 mt-1 space-y-1">
                                    @foreach($error['errors'] as $msg)
                                        <li>{{ $msg }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </x-filament::section>
    @endif
</x-filament-panels::page>