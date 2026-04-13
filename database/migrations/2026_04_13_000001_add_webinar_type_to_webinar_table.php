<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('webinar', function (Blueprint $table) {
            $table->string('webinar_type')->default('upcoming')->after('slug');
            $table->string('meeting_link')->nullable()->after('webinar_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinar', function (Blueprint $table) {
            $table->dropColumn('meeting_link');
            $table->dropColumn('webinar_type');
        });
    }
};
