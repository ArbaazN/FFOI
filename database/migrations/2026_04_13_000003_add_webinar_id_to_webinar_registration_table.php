<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webinar_registration', function (Blueprint $table) {
            $table->foreignId('webinar_id')->nullable()->after('id')->constrained('webinar')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('webinar_registration', function (Blueprint $table) {
            $table->dropConstrainedForeignId('webinar_id');
        });
    }
};
