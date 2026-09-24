@php
    $hasFilters = $search || $category || $brand || $gender || $stock_status || $has_discount || $min_price || $max_price;
@endphp

<div class="space-y-8">

    {{-- Header --}}
    <div class="flex items-center justify-between pb-5 border-b border-stone-200 dark:border-stone-800">
        <h3 class="eyebrow">الفلاتر</h3>
        @if($hasFilters)
            <button type="button" wire:click="resetFilters"
                class="text-xs font-semibold text-gold hover:text-forest transition-colors">
                مسح الكل
            </button>
        @endif
    </div>

    {{-- Loading --}}
    <div wire:loading.delay class="flex items-center gap-2 text-xs text-ink-muted dark:text-cream/60">
        <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
        <span>جارٍ التحديث...</span>
    </div>

    {{-- 1. Search --}}
    <div>
        <label class="block text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
            البحث
        </label>
        <div class="relative">
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="ابحث عن منتج..."
                class="w-full h-11 pr-10 pl-3 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-cream focus:outline-none transition-colors"
                style="border-radius: 4px;">
            <svg class="w-4 h-4 absolute top-1/2 -translate-y-1/2 right-3 pointer-events-none text-ink-faint dark:text-cream/40"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    {{-- 2. Category --}}
    <div>
        <label class="block text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
            التصنيف
        </label>
        <select wire:model.live="category"
            class="w-full h-11 px-3 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-cream focus:outline-none transition-colors"
            style="border-radius: 4px;">
            <option value="">كل التصنيفات</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- 3. Brand --}}
    <div>
        <label class="block text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
            العلامة التجارية
        </label>
        <select wire:model.live="brand"
            class="w-full h-11 px-3 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-cream focus:outline-none transition-colors"
            style="border-radius: 4px;">
            <option value="">كل العلامات</option>
            @foreach($brands as $br)
                <option value="{{ $br->id }}">{{ $br->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- 4. Gender --}}
    <div>
        <label class="block text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
            الفئة
        </label>
        <div class="grid grid-cols-2 gap-2">
            @foreach([
                        '' => 'الكل',
                        'men' => 'رجالي',
                        'women' => 'نسائي',
                        'kids' => 'أطفال',
                    ] as $value => $label)
                    <button type="button" wire:click="$set('gender', '{{ $value }}')"
                        class="h-10 px-3 text-xs font-medium border transition-colors
                                       {{ $gender === ($value === '' ? null : $value) || ($gender === null && $value === '')
                ? 'bg-forest text-white border-forest'
                : 'border-stone-300 dark:border-stone-700 hover:border-forest hover:text-forest dark:hover:text-cream' }}" style="border-radius: 4px;">
                        {{ $label }}
                    </button>
            @endforeach
        </div>
    </div>

    {{-- 5. Price --}}
    <div>
        <label class="block text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
            نطاق السعر
        </label>
        <div class="grid grid-cols-2 gap-2">
            <input type="number" wire:model.live.debounce.600ms="min_price" placeholder="من"
                class="h-11 px-3 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest focus:outline-none transition-colors"
                style="border-radius: 4px;">
            <input type="number" wire:model.live.debounce.600ms="max_price" placeholder="إلى"
                class="h-11 px-3 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest focus:outline-none transition-colors"
                style="border-radius: 4px;">
        </div>
    </div>

    {{-- 6. Stock --}}
    <div>
        <label class="block text-xs font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
            حالة المخزون
        </label>
        <select wire:model.live="stock_status"
            class="w-full h-11 px-3 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest focus:outline-none transition-colors"
            style="border-radius: 4px;">
            <option value="">الكل</option>
            <option value="in_stock">متوفر</option>
            <option value="low_stock">كمية محدودة</option>
            <option value="out_of_stock">نفذ المخزون</option>
        </select>
    </div>

    {{-- 7. Discount --}}
    <div>
        <label class="flex items-center gap-3 cursor-pointer group">
            <input type="checkbox" wire:model.live="has_discount"
                class="w-4 h-4 rounded-none border-stone-300 dark:border-stone-700 text-gold focus:ring-gold focus:ring-offset-0"
                style="border-radius: 2px;">
            <span class="text-sm text-ink dark:text-cream group-hover:text-gold transition-colors">
                المنتجات المخفّضة فقط
            </span>
        </label>
    </div>
</div>