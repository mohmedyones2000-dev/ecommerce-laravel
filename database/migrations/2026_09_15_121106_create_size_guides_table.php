<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('size_guides', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // مثل: مقاسات الرجالي
            $table->text('description')->nullable(); // ملاحظات إضافية
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('size_guides');
    }
};