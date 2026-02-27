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
        Schema::create('webinar_upcoming_session', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('session_id')->nullable();
            $table->string('topic_name')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->string('mode')->nullable();
            $table->string('by')->nullable();
            $table->string('why_attend_section_heading')->nullable();
            $table->text('why_attend_section_points')->nullable();
            $table->string('why_learn_heading')->nullable();
            $table->text('why_learn_points')->nullable();
            $table->string('who_attend_heading')->nullable();
            $table->text('who_attend_points')->nullable();
            $table->string('who_attend_disclaimer')->nullable();
            $table->string('career_role_heading')->nullable();
            $table->text('career_role_points')->nullable();
            $table->string('career_role_disclaimer')->nullable();
            $table->string('how_session_help_heading')->nullable();
            $table->text('how_session_help_points')->nullable();
            $table->string('how_session_help_disclaimer')->nullable();
            $table->string('learn_with_ffoi_heading')->nullable();
            $table->text('learn_with_ffoi_points')->nullable();
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
        Schema::dropIfExists('webinar_upcoming_session');
    }
};
