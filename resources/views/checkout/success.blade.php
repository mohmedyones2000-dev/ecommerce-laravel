@extends('layouts.app')

@section('title', 'تم الطلب بنجاح | متجري')

@section('content')

    <div class="container mx-auto px-4 py-16 max-w-2xl">

        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center">

            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center text-6xl mx-auto mb-6">
                ✓
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-3">تم استلام طلبك بنجاح! 🎉</h1>
            <p class="text-gray-600 mb-8">شكراً لك على التسوق معنا. سنتواصل معك قريباً لتأكيد الطلب.</p>

            <div class="bg-gray-50 rounded-2xl p-6 mb-8 text-right">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">رقم الطلب:</span>
                    <span class="font-bold text-gray-900">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">الإجمالي:</span>
                    <span class="font-bold text-amber-500">${{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">الحالة:</span>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                        قيد المراجعة
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('orders.show', $order->id) }}"
                    class="bg-amber-500 text-white px-6 py-3 rounded-full font-semibold hover:bg-amber-600 transition">
                    عرض تفاصيل الطلب
                </a>
                <a href="{{ route('products.index') }}"
                    class="border-2 border-gray-200 text-gray-700 px-6 py-3 rounded-full font-semibold hover:border-amber-500 hover:text-amber-500 transition">
                    متابعة التسوق
                </a>
            </div>

        </div>
    </div>

@endsection