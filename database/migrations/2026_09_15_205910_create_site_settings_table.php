<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('متجري');
            $table->string('email')->default('info@matjari.com');
            $table->string('phone')->default('+970 599 123 456');
            $table->string('address')->default('فلسطين - غزة');
            $table->string('whatsapp')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('working_hours_weekday')->default('السبت - الخميس: 9ص - 6م');
            $table->string('working_hours_weekend')->default('الجمعة: مغلق');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};