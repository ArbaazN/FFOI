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
        Schema::create('membership', function (Blueprint $table) {
            $table->id();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_key')->nullable();
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->text('short_desc')->nullable();
            $table->string('headline')->nullable();
            $table->string('sub_headline')->nullable();
            $table->string('primary_cta')->nullable();
            $table->string('secondory_cta')->nullable();
            $table->text('what_ffoi_membership')->nullable();
            $table->text('what_ffoi_membership_not')->nullable();
            $table->string('what_ffoi_membership_desclaimer')->nullable();
            $table->text('why_ffoi_created')->nullable();
            $table->string('why_ffoi_created_desclaimer')->nullable();
            $table->text('why_ffoi_created_progress')->nullable();
            $table->string('category_status_desc')->nullable();
            $table->text('membership_status')->nullable();
            $table->text('anual_membership')->nullable();
            $table->text('life_membership')->nullable();
            $table->string('life_membership_disclaimer')->nullable();
            $table->string('primary_call_heading')->nullable();
            $table->string('primary_call_desc')->nullable();
            $table->string('primary_call_primary_CTA')->nullable();
            $table->string('primary_call_secondary_CTA')->nullable();
            $table->text('footer_text')->nullable();
            $table->text('next_build')->nullable();
            $table->string('next_build_disclaimer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership');
    }
};
