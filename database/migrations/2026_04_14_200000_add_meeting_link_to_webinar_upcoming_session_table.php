<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webinar_upcoming_session', function (Blueprint $table) {
            $table->string('meeting_link')->nullable()->after('webinar_type');
        });
    }

    public function down(): void
    {
        Schema::table('webinar_upcoming_session', function (Blueprint $table) {
            $table->dropColumn('meeting_link');
        });
    }
};
