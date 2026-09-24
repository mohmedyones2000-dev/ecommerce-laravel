@php
    $activeCount = collect([
        $search,
        $category,
        $brand,
        $gender,
        $stock_status,
        $has_discount,
        $min_price,
        $max_price,
    ])->filter(fn($v) => $v !== null && $v !== '' && $v !== false)->count();
@endphp

<div x-data="{ mobileOpen: false }" class="w-full">

    {{-- ══════════ RESULTS BAR ══════════ --}}
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 mb-10 border-b border-stone-200 dark:border-stone-800">

        <div class="flex items-center gap-4">
            {{-- Mobile filters button --}}
            <button type="button" @click="mobileOpen = true"
                class="lg:hidden inline-flex items-center gap-2 h-10 px-4 text-xs font-semibold border border-stone-300 dark:border-stone-700 hover:border-forest dark:hover:border-cream transition-colors"
                style="border-radius: 4px;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                الفلاتر
                @if($activeCount > 0)
                    <span
                        class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-gold"
                        style="border-radius: 2px;">
                        {{ $activeCount }}
                    </span>
                @endif
            </button>

            <p class="text-sm text-ink-muted dark:text-cream/60">
                <span class="font-bold text-ink dark:text-cream">{{ $products->total() }}</span>
                {{ $products->total() === 1 ? 'منتج' : 'منتجاً' }}
                @if($search)
                    <span class="opacity-60"> — «{{ $search }}»</span>
                @endif
            </p>
        </div>

        {{-- Sort --}}
        <div class="flex items-center gap-3">
            <label class="text-[10px] font-semibold tracking-[0.2em] uppercase text-ink-muted dark:text-cream/60">
                ترتيب
            </label>
            <select wire:model.live="sort"
                class="h-10 px-3 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-cream focus:outline-none transition-colors"
                style="border-radius: 4px;">
                <option value="latest">الأحدث</option>
                <option value="oldest">الأقدم</option>
                <option value="price_asc">السعر: الأقل أولاً</option>
                <option value="price_desc">السعر: الأعلى أولاً</option>
                <option value="name_asc">أبجدي: أ ← ي</option>
                <option value="name_desc">أبجدي: ي ← أ</option>
                <option value="rating">الأعلى تقييماً</option>
            </select>
        </div>
    </div>

    {{-- ══════════ MAIN GRID ══════════ --}}
    <div class="grid lg:grid-cols-12 gap-10 lg:gap-12">

        {{-- DESKTOP SIDEBAR --}}
        <aside class="hidden lg:block lg:col-span-3">
            <div class="sticky top-24 max-h-[calc(100vh-7rem)] overflow-y-auto no-scrollbar pl-2">
                @include('livewire.partials.filters', [
                    'categories' => $categories,
                    'brands' => $brands,
                ])
            </div>
        </aside>

        {{-- PRODUCTS --}}
        <div class="lg:col-span-9">
            @if($products->count() > 0)
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-12 md:gap-x-6">
                    @foreach($products as $product)
                        @include('layouts.partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                <div class="mt-20 pt-10 border-t border-stone-200 dark:border-stone-800">
                    {{ $products->links() }}
                </div>
            @else
                <div class="py-24 text-center">
                    <div class="w-20 h-20 mx-auto mb-8 flex items-center justify-center border border-stone-300 dark:border-stone-700"
                        style="border-radius: 4px;">
                        <svg class="w-8 h-8 text-ink-faint dark:text-cream/40" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="1.2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <span class="eyebrow block mb-4">— لا نتائج</span>

                    <h3 class="display-2 mb-4">
                        لم نجد ما
                        <span class="text-forest">تبحث عنه</span>
                    </h3>

                    <p class="text-ink-muted dark:text-cream/60 mb-10 max-w-sm mx-auto text-pretty">
                        جرّب تغيير الفلاتر أو البحث بكلمات أخرى.
                    </p>

                    <button type="button" wire:click="resetFilters" class="btn-solid">
                        إعادة تعيين الفلاتر
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════ MOBILE DRAWER ══════════ --}}
    <div x-show="mobileOpen" x-cloak class="lg:hidden fixed inset-0 z-[80]" @keydown.escape.window="mobileOpen = false">

        {{-- Backdrop --}}
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="mobileOpen = false"
            class="absolute inset-0 bg-ink/60 dark:bg-black/70"></div>

        {{-- Panel --}}
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="absolute inset-y-0 right-0 w-full max-w-sm bg-canvas dark:bg-zinc-950 flex flex-col">

            {{-- Drawer header --}}
            <div
                class="flex items-center justify-between px-6 py-5 border-b border-stone-200 dark:border-stone-800 shrink-0">
                <span class="eyebrow">الفلاتر</span>
                <button type="button" @click="mobileOpen = false"
                    class="w-10 h-10 flex items-center justify-center text-ink dark:text-cream hover:text-gold transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Drawer body --}}
            <div class="flex-1 overflow-y-auto px-6 py-8">
                @include('livewire.partials.filters', [
                    'categories' => $categories,
                    'brands' => $brands,
                ])
            </div>

            {{-- Drawer footer --}}
            <div class="px-6 py-5 border-t border-stone-200 dark:border-stone-800 shrink-0">
                <button type="button" @click="mobileOpen = false" class="btn-brand w-full">
                    عرض النتائج
                    <span class="opacity-60">({{ $products->total() }})</span>
                </button>
            </div>
        </div>
    </div>

</div>