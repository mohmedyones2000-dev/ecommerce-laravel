@extends('layouts.app')

@section('title', 'المنتجات | ' . ($siteSettings->site_name ?? 'متجري'))
@section('description', 'تصفح جميع المنتجات في متجرنا')

@section('content')

    <div class="container-x pt-16 lg:pt-24 pb-20">

        {{-- Page Header --}}
        <header class="mb-12 lg:mb-16">
            <nav class="flex items-center gap-3 text-xs uppercase tracking-widest text-ink-faint dark:text-cream/40 mb-8"
                aria-label="breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-forest transition-colors">الرئيسية</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="text-ink dark:text-cream">المنتجات</span>
            </nav>

            <span class="eyebrow block mb-4">— المجموعة الكاملة</span>
            <h1 class="display-2">
                جميع
                <span class="text-forest">المنتجات</span>
            </h1>
        </header>

        {{-- Livewire Search --}}
        @livewire('product-search', [], key('product-search'))
    </div>

@endsection