@extends('layouts.app')

@section('title', 'تم إلغاء الاشتراك | متجري')

@section('content')

    <div class="container mx-auto px-4 py-16 max-w-2xl">
        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center text-6xl mx-auto mb-6">
                📭
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-3">تم إلغاء اشتراكك</h1>
            <p class="text-gray-600 mb-8">
                نأسف لرؤيتك تذهب! تم إلغاء اشتراكك من النشرة البريدية بنجاح.
                <br>
                يمكنك دائماً الاشتراك مرة أخرى في أي وقت.
            </p>

            <a href="{{ route('home') }}"
                class="inline-block bg-amber-500 text-white px-8 py-3 rounded-full font-semibold hover:bg-amber-600 transition">
                العودة للصفحة الرئيسية
            </a>
        </div>
    </div>

@endsection