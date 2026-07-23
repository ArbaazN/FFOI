<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->string('product_code')->nullable()->after('description');
            $table->string('product_image')->nullable()->after('product_code');
            $table->text('product_description')->nullable()->after('product_image');
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['product_code', 'product_image', 'product_description']);
        });
    }
};
