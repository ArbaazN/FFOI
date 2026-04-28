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
            $table->string('best_of_industries_heading')->nullable();
            $table->text('image_new')->nullable();
            $table->text('name_new')->nullable();
            $table->text('Designation_new')->nullable();
            $table->text('Description_new')->nullable();
            $table->text('Areaofexperties_new')->nullable();
            $table->text('logo_image1_new')->nullable();
            $table->text('logo_image2_new')->nullable();
            $table->text('linkedIn_new')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinar_upcoming_session', function (Blueprint $table) {
            $table->dropColumn('best_of_industries_heading');
            $table->dropColumn('image_new');
            $table->dropColumn('name_new');
            $table->dropColumn('Designation_new');
            $table->dropColumn('Description_new');
            $table->dropColumn('Areaofexperties_new');
            $table->dropColumn('logo_image1_new');
            $table->dropColumn('logo_image2_new');
            $table->dropColumn('linkedIn_new');
        });
    }
};
