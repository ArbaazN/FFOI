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
        Schema::table('webinar_upcoming_session', function (Blueprint $table) {
            $table->string('instructor_image')->nullable();
            $table->string('instructor_name')->nullable();
            $table->string('instructor_designation')->nullable();
            $table->string('instructor_experience')->nullable();
            $table->string('instructor_desc')->nullable();
            $table->string('instructor_logo_image1')->nullable();
            $table->string('instructor_logo_image2')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinar_upcoming_session', function (Blueprint $table) {
            $table->dropColumn('instructor_image');
            $table->dropColumn('instructor_name');
            $table->dropColumn('instructor_designation');
            $table->dropColumn('instructor_experience');
            $table->dropColumn('instructor_desc');
            $table->dropColumn('instructor_logo_image1');
            $table->dropColumn('instructor_logo_image2');
        });
    }
};
