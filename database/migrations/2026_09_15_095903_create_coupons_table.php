<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->enum('type', ['percentage', 'fixed']); // نسبة أو مبلغ ثابت
            $table->decimal('value', 10, 2); // قيمة الخصم
            $table->decimal('min_order_amount', 10, 2)->default(0); // الحد الأدنى للطلب
            $table->integer('usage_limit')->nullable(); // الحد الأقصى للاستخدام (null = غير محدود)
            $table->integer('used_count')->default(0); // عدد مرات الاستخدام
            $table->date('expires_at')->nullable(); // تاريخ الانتهاء
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};