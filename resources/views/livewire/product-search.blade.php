<div>
    {{-- ============================================ --}}
    {{-- ✅ FILTERS SIDEBAR --}}
    {{-- ============================================ --}}
    <div class="bg-white rounded-xl border shadow-sm p-5 sticky top-4" 
         style="border-color: var(--border-light);">

        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-lg" style="color: var(--text-primary);">
                🔍 الفلاتر
            </h3>
            <button type="button" 
                    wire:click="resetFilters"
                    class="text-[12px] font-medium transition-colors"
                    style="color: var(--gold);">
                🔄 إعادة تعيين
            </button>
        </div>

        {{-- ✅ Loading Indicator --}}
        <div wire:loading class="mb-4">
            <div class="flex items-center gap-2 text-[12px] text-blue-600">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                <span>جاري البحث...</span>
            </div>
        </div>

        <div class="space-y-5">

            {{-- ✅ 1. البحث النصي --}}
            <div>
                <label class="block text-[12px] font-semibold mb-2" 
                       style="color: var(--text-primary);">
                    البحث النصي
                </label>
                <div class="relative">
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="ابحث باسم المنتج أو الوصف..."
                           class="w-full h-10 pr-10 pl-3 rounded-lg border text-[13px]"
                           style="border-color: var(--border-light);">
                    <svg class="w-4 h-4 absolute top-1/2 -translate-y-1/2 right-3 pointer-events-none"
                         style="color: var(--text-tertiary);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- ✅ 2. التصنيف --}}
            <div>
                <label class="block text-[12px] font-semibold mb-2"
                       style="color: var(--text-primary);">
                    التصنيف
                </label>
                <select wire:model.live="category"
                        class="w-full h-10 px-3 rounded-lg border text-[13px]"
                        style="border-color: var(--border-light);">
                    <option value="">كل التصنيفات</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ✅ 3. الماركة --}}
            <div>
                <label class="block text-[12px] font-semibold mb-2"
                       style="color: var(--text-primary);">
                    الماركة
                </label>
                <select wire:model.live="brand"
                        class="w-full h-10 px-3 rounded-lg border text-[13px]"
                        style="border-color: var(--border-light);">
                    <option value="">كل الماركات</option>
                    @foreach($brands as $br)
                        <option value="{{ $br->id }}">{{ $br->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ✅ 4. الجنس --}}
            <div>
                <label class="block text-[12px] font-semibold mb-2"
                       style="color: var(--text-primary);">
                    الفئة
                </label>
                <select wire:model.live="gender"
                        class="w-full h-10 px-3 rounded-lg border text-[13px]"
                        style="border-color: var(--border-light);">
                    <option value="">الكل</option>
                    <option value="men">👔 رجالي</option>
                    <option value="women">👗 نسائي</option>
                    <option value="kids">🧒 أطفال</option>
                </select>
            </div>

            {{-- ✅ 5. نطاق السعر --}}
            <div>
                <label class="block text-[12px] font-semibold mb-2"
                       style="color: var(--text-primary);">
                    نطاق السعر
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number"
                           wire:model.live.debounce.500ms="min_price"
                           placeholder="من"
                           class="w-full h-10 px-3 rounded-lg border text-[13px]"
                           style="border-color: var(--border-light);">
                    <input type="number"
                           wire:model.live.debounce.500ms="max_price"
                           placeholder="إلى"
                           class="w-full h-10 px-3 rounded-lg border text-[13px]"
                           style="border-color: var(--border-light);">
                </div>
            </div>

            {{-- ✅ 6. حالة المخزون --}}
            <div>
                <label class="block text-[12px] font-semibold mb-2"
                       style="color: var(--text-primary);">
                    حالة المخزون
                </label>
                <select wire:model.live="stock_status"
                        class="w-full h-10 px-3 rounded-lg border text-[13px]"
                        style="border-color: var(--border-light);">
                    <option value="">الكل</option>
                    <option value="in_stock">✅ متوفر</option>
                    <option value="low_stock">⚠️ كمية قليلة</option>
                    <option value="out_of_stock">⛔ نفذ المخزون</option>
                </select>
            </div>

            {{-- ✅ 7. عرض المنتجات المخفضة --}}
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox"
                           wire:model.live="has_discount"
                           class="w-4 h-4 rounded">
                    <span class="text-[13px]" style="color: var(--text-primary);">
                        💰 عرض المنتجات المخفّضة فقط
                    </span>
                </label>
            </div>

        </div>
    

    {{-- ============================================ --}}
    {{-- ✅ RESULTS + SORTING --}}
    {{-- ============================================ --}}
    <div class="bg-white rounded-xl border shadow-sm p-4 mb-5"
         style="border-color: var(--border-light);">

        <div class="flex items-center justify-between flex-wrap gap-3">
            <p class="text-[13px]" style="color: var(--text-secondary);">
                <strong style="color: var(--gold);">
                    {{ $products->total() }}
                </strong>
                منتج
                @if($search)
                    للبحث عن: "<strong>{{ $search }}</strong>"
                @endif
            </p>

            <div class="flex items-center gap-2">
                <label class="text-[12px]" style="color: var(--text-secondary);">
                    ترتيب حسب:
                </label>
                <select wire:model.live="sort"
                        class="h-9 px-3 rounded-lg border text-[12px]"
                        style="border-color: var(--border-light);">
                    <option value="latest">الأحدث</option>
                    <option value="oldest">الأقدم</option>
                    <option value="price_asc">السعر: الأقل أولاً</option>
                    <option value="price_desc">السعر: الأعلى أولاً</option>
                    <option value="name_asc">أبجدي: أ → ي</option>
                    <option value="name_desc">أبجدي: ي → أ</option>
                    <option value="rating">الأعلى تقييماً</option>
                </select>
            </div>
        </div>
    </div>

    {{-- ✅ Products Grid --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
            @foreach($products as $product)
                @include('layouts.partials.product-card', ['product' => $product])
            @endforeach
        </div>

        {{-- ✅ Pagination مع withQueryString --}}
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl border p-16 text-center"
             style="border-color: var(--border-light);">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-lg font-bold mb-2" style="color: var(--text-primary);">
                لا توجد نتائج
            </h3>
            <p class="text-[13px] mb-5" style="color: var(--text-secondary);">
                جرب تغيير الفلاتر أو البحث بكلمات أخرى
            </p>
            <button wire:click="resetFilters"
                    class="px-6 py-2.5 rounded-lg text-white font-medium text-[13px]"
                    style="background-color: var(--gold);">
                🔄 إعادة تعيين الفلاتر
            </button>
        </div>
    @endif
</div>