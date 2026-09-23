<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // ✅ إضافة السعر (nullable = إذا كانت NULL يستخدم سعر المنتج)
            $table->decimal('price', 10, 2)->nullable()->after('stock_quantity');

            // ✅ إضافة سعر الخصم
            $table->decimal('discount_price', 10, 2)->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['price', 'discount_price']);
        });
    }
};