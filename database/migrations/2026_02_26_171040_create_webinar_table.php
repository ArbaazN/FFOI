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
        Schema::create('webinar', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('short_desc')->nullable();
            $table->text('desc')->nullable();
            $table->text('perfect_for_desc')->nullable();
            $table->string('perfect_for_desclaimer')->nullable();
            $table->text('works_desc')->nullable();
            $table->string('why_ffoi_heading')->nullable();
            $table->text('why_ffoi_desc')->nullable();
            $table->text('faqs_question')->nullable();
            $table->text('faqs_answer')->nullable();
            $table->string('final_CTA_desc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webinar');
    }
};
