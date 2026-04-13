<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('webinar_upcoming_session', 'webinar_type')) {
            Schema::table('webinar_upcoming_session', function (Blueprint $table) {
                $table->string('webinar_type')->default('upcoming')->after('slug');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('webinar_upcoming_session', 'webinar_type')) {
            Schema::table('webinar_upcoming_session', function (Blueprint $table) {
                $table->dropColumn('webinar_type');
            });
        }
    }
};
