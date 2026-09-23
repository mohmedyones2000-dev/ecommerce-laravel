<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ============ EXPORT SECTION ============ --}}
        <x-filament::section>
            <x-slot name="heading">
                📤 تصدير المنتجات
            </x-slot>

            <x-slot name="description">
                قم بتصدير المنتجات إلى ملف CSV (يفتح في Excel)
            </x-slot>

            <div class="space-y-4">
                {{-- فلاتر --}}
                <div>
                    <label class="block text-sm font-medium mb-2">التصنيف (اختياري)</label>
                    <select wire:model="export_category_id"
                            class="w-full border-gray-300 rounded-lg">
                        <option value="">كل التصنيفات</option>
                        @foreach(\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">الحالة</label>
                    <select wire:model="export_is_active"
                            class="w-full border-gray-300 rounded-lg">
                        <option value="">الكل</option>
                        <option value="1">النشطة فقط</option>
                        <option value="0">غير النشطة</option>
                    </select>
                </div>

                {{-- زر التصدير --}}
                <x-filament::button
                    wire:click="export"
                    icon="heroicon-o-arrow-down-tray"
                    color="success"
                    size="lg"
                    class="w-full">
                    📥 تصدير إلى CSV
                </x-filament::button>
            </div>
        </x-filament::section>

        {{-- ============ IMPORT SECTION ============ --}}
        <x-filament::section>
            <x-slot name="heading">
                📥 استيراد المنتجات
            </x-slot>

            <x-slot name="description">
                ارفع ملف CSV لإضافة منتجات دفعة واحدة
            </x-slot>

            <div class="space-y-4">
                {{-- تحميل القالب --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800 mb-2">
                        💡 ننصح بتحميل القالب أولاً لفهم الصيغة المطلوبة
                    </p>
                    <x-filament::button
                        wire:click="downloadTemplate"
                        icon="heroicon-o-document-arrow-down"
                        color="info"
                        size="sm">
                        📋 تحميل قالب CSV
                    </x-filament::button>
                </div>

                {{-- رفع الملف --}}
                <div>
                    <label class="block text-sm font-medium mb-2">ملف CSV *</label>
                    <input type="file"
                           wire:model="file"
                           accept=".csv,.txt"
                           class="w-full border-gray-300 rounded-lg">
                    @error('file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- زر الاستيراد --}}
                <x-filament::button
                    wire:click="import"
                    icon="heroicon-o-arrow-up-tray"
                    color="primary"
                    size="lg"
                    class="w-full"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>📤 استيراد من CSV</span>
                    <span wire:loading>⏳ جاري الاستيراد...</span>
                </x-filament::button>
            </div>
        </x-filament::section>
    </div>

    {{-- ============ IMPORT RESULTS ============ --}}
    @if($showImportResults)
        <x-filament::section class="mt-6">
            <x-slot name="heading">
                📊 نتائج الاستيراد
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500 mb-1">إجمالي الصفوف</p>
                    <p class="text-2xl font-bold">{{ $importResults['total'] }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <p class="text-xs text-green-600 mb-1">✅ نُجح</p>
                    <p class="text-2xl font-bold text-green-600">{{ $importResults['imported'] }}</p>
                </div>
                <div class="bg-red-50 rounded-lg p-4 text-center">
                    <p class="text-xs text-red-600 mb-1">❌ فشل</p>
                    <p class="text-2xl font-bold text-red-600">{{ $importResults['failed'] }}</p>
                </div>
            </div>

            @if(!empty($importResults['errors']))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h4 class="font-bold text-red-800 mb-3">❌ الأخطاء:</h4>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach($importResults['errors'] as $error)
                            <div class="bg-white rounded p-3 text-sm">
                                <p class="font-medium text-red-700">صف #{{ $error['row'] }}:</p>
                                <ul class="list-disc list-inside text-red-600 mt-1">
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