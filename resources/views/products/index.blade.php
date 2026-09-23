@extends('layouts.app')

@section('title', 'المنتجات | متجري')

@section('content')

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold mb-1.5"
                style="color: var(--text-primary);">
                🛍️ جميع المنتجات
            </h1>
            <p class="text-[13px]" style="color: var(--text-secondary);">
                ابحث وصفّي حسب ما تريد
            </p>
        </div>

        {{-- Livewire Component (تمت إزالة الـ Grid الخارجي لأنه موجود داخل المكون بالفعل) --}}
        <div class="w-full">
            @livewire('product-search', [], key('product-search'))
        </div>

    </div>

@endsection