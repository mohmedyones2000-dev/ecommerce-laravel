<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('size_guide_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('size_guide_id')->constrained()->cascadeOnDelete();
            $table->string('size'); // S, M, L, XL
            $table->string('chest')->nullable(); // الصدر
            $table->string('waist')->nullable(); // الخصر
            $table->string('hips')->nullable(); // الأرداف
            $table->string('length')->nullable(); // الطول
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('size_guide_items');
    }
};