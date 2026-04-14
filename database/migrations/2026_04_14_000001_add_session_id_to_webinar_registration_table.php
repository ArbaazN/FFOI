<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webinar_registration', function (Blueprint $table) {
            $table->foreignId('session_id')
                ->nullable()
                ->after('webinar_id')
                ->constrained('webinar_upcoming_session')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('webinar_registration', function (Blueprint $table) {
            $table->dropConstrainedForeignId('session_id');
        });
    }
};
