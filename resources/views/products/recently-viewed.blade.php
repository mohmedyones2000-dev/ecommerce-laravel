@extends('layouts.app')

@section('title', 'المنتجات المشاهدة حديثاً | متجري')

@section('content')

    <div class="container mx-auto px-4 py-8">

        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">👀 المنتجات المشاهدة حديثاً</h1>
            <p class="text-gray-600">آخر المنتجات التي تصفحتها</p>
        </div>

        @if($products->count())
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    @include('layouts.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl p-16 text-center border border-gray-100">
                <div class="text-6xl mb-4">👀</div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">لا توجد مشاهدات</h2>
                <p class="text-gray-600 mb-6">لم تتصفح أي منتجات بعد. ابدأ باستكشاف منتجاتنا!</p>
                <a href="{{ route('products.index') }}"
                    class="inline-block bg-amber-500 text-white px-8 py-3 rounded-full font-semibold hover:bg-amber-600 transition">
                    تصفح المنتجات
                </a>
            </div>
        @endif

    </div>

@endsection