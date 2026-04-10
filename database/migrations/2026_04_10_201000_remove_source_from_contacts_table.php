<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('contacts', 'source')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->dropColumn('source');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('contacts', 'source')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->string('source')->default('contact_us')->after('email');
            });
        }
    }
};
