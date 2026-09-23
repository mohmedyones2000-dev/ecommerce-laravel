@extends('layouts.app')

@section('title', 'المنتجات | متجري')

@section('content')

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold mb-1.5" style="color: var(--text-primary);">
                @if(request('category'))
                    {{ $categories->where('slug', request('category'))->first()->name ?? 'المنتجات' }}
                @elseif(request('search'))
                    نتائج البحث: "{{ request('search') }}"
                @else
                    جميع المنتجات
                @endif
            </h1>
            <p class="text-[13px]" style="color: var(--text-secondary);">
                {{ $products->total() }} منتج متاح
            </p>
        </div>

        <div class="grid md:grid-cols-4 gap-6">

            <aside class="md:col-span-1">
                <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div class="rounded-xl border sticky top-20" style="background-color: var(--bg-primary); border-color: var(--border-light);">

                        <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color: var(--border-light);">
                            <h3 class="text-[14px] font-semibold" style="color: var(--text-primary);">الفلاتر</h3>
                            @if(request()->anyFilled(['category', 'brand', 'min_price', 'max_price', 'min_rating', 'in_stock', 'has_discount']))
                                <a href="{{ route('products.index', request()->only('search')) }}"
                                   class="text-[11px] font-semibold transition-colors duration-150"
                                   style="color: #dc2626;"
                                   onmouseover="this.style.opacity='0.7';"
                                   onmouseout="this.style.opacity='1';">
                                    مسح الكل
                                </a>
                            @endif
                        </div>

                        <div class="p-5 space-y-6">

                            <div>
                                <label class="block text-[12px] font-semibold mb-3" style="color: var(--text-primary);">التصنيف</label>
                                <div class="space-y-2 max-h-48 overflow-y-auto">
                                    @foreach($categories as $category)
                                        <label class="flex items-center gap-2.5 text-[13px] cursor-pointer group">
                                            <input type="radio" name="category" value="{{ $category->slug }}"
                                                   {{ request('category') == $category->slug ? 'checked' : '' }}
                                                   class="w-4 h-4 rounded-full border-gray-300 focus:ring-2"
                                                   style="--tw-ring-color: var(--gold); accent-color: var(--gold);">
                                            <span class="transition-colors duration-150 group-hover:text-[color:var(--gold)]"
                                                  style="color: var(--text-secondary);">
                                                {{ $category->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            @if($brands->count())
                                <div class="pt-5 border-t" style="border-color: var(--border-light);">
                                    <label class="block text-[12px] font-semibold mb-3" style="color: var(--text-primary);">العلامة التجارية</label>
                                    <div class="space-y-2 max-h-48 overflow-y-auto">
                                        @foreach($brands as $brand)
                                            <label class="flex items-center gap-2.5 text-[13px] cursor-pointer group">
                                                <input type="checkbox" name="brand[]" value="{{ $brand->id }}"
                                                       {{ in_array($brand->id, (array) request('brand', [])) ? 'checked' : '' }}
                                                       class="w-4 h-4 rounded border-gray-300 focus:ring-2"
                                                       style="--tw-ring-color: var(--gold); accent-color: var(--gold);">
                                                <span class="transition-colors duration-150 group-hover:text-[color:var(--gold)]"
                                                      style="color: var(--text-secondary);">
                                                    {{ $brand->name }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="pt-5 border-t" style="border-color: var(--border-light);">
                                <label class="block text-[12px] font-semibold mb-3" style="color: var(--text-primary);">السعر</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="min_price" placeholder="من"
                                           value="{{ request('min_price') }}"
                                           class="w-full h-9 border rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                                           style="background-color: var(--bg-primary); border-color: var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
                                    <span class="text-[12px]" style="color: var(--text-tertiary);">-</span>
                                    <input type="number" name="max_price" placeholder="إلى"
                                           value="{{ request('max_price') }}"
                                           class="w-full h-9 border rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                                           style="background-color: var(--bg-primary); border-color: var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
                                </div>
                            </div>

                            <div class="pt-5 border-t" style="border-color: var(--border-light);">
                                <label class="block text-[12px] font-semibold mb-3" style="color: var(--text-primary);">التقييم</label>
                                <div class="space-y-2">
                                    @for($i = 4; $i >= 1; $i--)
                                        <label class="flex items-center gap-2.5 text-[13px] cursor-pointer group">
                                            <input type="radio" name="min_rating" value="{{ $i }}"
                                                   {{ request('min_rating') == $i ? 'checked' : '' }}
                                                   class="w-4 h-4 rounded-full border-gray-300 focus:ring-2"
                                                   style="--tw-ring-color: var(--gold); accent-color: var(--gold);">
                                            <div class="flex items-center gap-1.5">
                                                <span style="color: var(--gold);">{{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }}</span>
                                                <span class="text-[11px]" style="color: var(--text-tertiary);">وأعلى</span>
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <div class="pt-5 border-t space-y-3" style="border-color: var(--border-light);">
                                <label class="flex items-center gap-2.5 text-[13px] cursor-pointer group">
                                    <input type="checkbox" name="in_stock" value="1"
                                           {{ request('in_stock') == '1' ? 'checked' : '' }}
                                           class="w-4 h-4 rounded border-gray-300 focus:ring-2"
                                           style="--tw-ring-color: var(--gold); accent-color: var(--gold);">
                                    <span class="font-medium transition-colors duration-150 group-hover:text-[color:var(--gold)]"
                                          style="color: var(--text-primary);">
                                        المتوفر فقط
                                    </span>
                                </label>

                                <label class="flex items-center gap-2.5 text-[13px] cursor-pointer group">
                                    <input type="checkbox" name="has_discount" value="1"
                                           {{ request('has_discount') == '1' ? 'checked' : '' }}
                                           class="w-4 h-4 rounded border-gray-300 focus:ring-2"
                                           style="--tw-ring-color: var(--gold); accent-color: var(--gold);">
                                    <span class="font-medium transition-colors duration-150 group-hover:text-[color:var(--gold)]"
                                          style="color: var(--text-primary);">
                                        العروض فقط
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="p-4 border-t" style="border-color: var(--border-light);">
                            <button type="submit"
                                    class="w-full h-10 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
                                    style="background-color: var(--gold);">
                                تطبيق الفلاتر
                            </button>
                        </div>
                    </div>
                </form>
            </aside>

            <div class="md:col-span-3">

                <div class="rounded-xl border p-3 mb-5 flex flex-wrap items-center gap-3" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <span class="text-[12px] font-medium" style="color: var(--text-secondary);">ترتيب حسب:</span>

                    <div class="flex flex-wrap gap-1.5">
                        @php
                            $currentSort = request('sort', 'latest');
                            $sortOptions = [
                                'latest' => 'الأحدث',
                                'price_low' => 'الأرخص',
                                'price_high' => 'الأغلى',
                                'rating' => 'الأعلى تقييماً',
                                'popular' => 'الأكثر مبيعاً',
                            ];
                        @endphp

                        @foreach($sortOptions as $key => $label)
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $key]) }}"
                               class="px-3 py-1.5 rounded-lg text-[12px] font-medium transition-all duration-150"
                               @if($currentSort === $key)
                                   style="background-color: var(--gold); color: white;"
                               @else
                                   style="background-color: var(--bg-tertiary); color: var(--text-secondary);"
                                   onmouseover="this.style.backgroundColor='var(--gold-soft)'; this.style.color='var(--gold)';"
                                   onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-secondary)';"
                               @endif>
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                @if($products->count())
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-5 mb-8">
                        @foreach($products as $product)
                            @include('layouts.partials.product-card', ['product' => $product])
                        @endforeach
                    </div>

                    <div class="flex justify-center">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="rounded-xl border p-14 text-center" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                        <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5" style="background-color: var(--bg-tertiary);">
                            <svg class="w-8 h-8" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2" style="color: var(--text-primary);">لا توجد منتجات</h3>
                        <p class="text-[13px] mb-6" style="color: var(--text-secondary);">لم نجد أي منتجات تطابق بحثك أو فلاترك.</p>
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
                           style="background-color: var(--gold);">
                            عرض جميع المنتجات
                        </a>
                    </div>
                @endif
            </div>

        </div>

    </div>

@endsection