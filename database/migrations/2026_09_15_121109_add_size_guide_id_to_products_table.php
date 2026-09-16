<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('size_guide_id')
                ->nullable()
                ->after('brand_id')
                ->constrained('size_guides')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['size_guide_id']);
            $table->dropColumn('size_guide_id');
        });
    }
};