<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // webinar_upcoming_session_category table
        Schema::table('webinar_upcoming_session_category', function (Blueprint $table) {
            $table->string('image')->nullable()->after('id');
        });

        // webinar_upcoming_session table
        Schema::table('webinar_upcoming_session', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('id');
            $table->string('image')->nullable()->after('why_learn_points');
            $table->string('image_attend')->nullable()->after('career_role_disclaimer');
        });

        // webinar table
        Schema::table('webinar', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('id');
            $table->string('image')->nullable()->after('desc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinar_upcoming_session_category', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('webinar_upcoming_session', function (Blueprint $table) {
            $table->dropColumn(['banner_image', 'image', 'image_attend']);
        });

        Schema::table('webinar', function (Blueprint $table) {
            $table->dropColumn(['banner_image', 'image']);
        });
        
    }
};
