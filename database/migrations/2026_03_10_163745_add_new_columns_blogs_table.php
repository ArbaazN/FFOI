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
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('author_image')->nullable();
            $table->text('author_desc')->nullable();
            $table->text('faqs_question')->nullable();
            $table->text('faqs_answer')->nullable();
            $table->string('fb_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('insta_url')->nullable();
            $table->string('linkedIn_url')->nullable();
            $table->string('yt_url')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('author_image');
            $table->dropColumn('author_desc');
            $table->dropColumn('faqs_question');
            $table->dropColumn('faqs_answer');
            $table->dropColumn('fb_url');
            $table->dropColumn('twitter_url');
            $table->dropColumn('insta_url');
            $table->dropColumn('linkedIn_url');
            $table->dropColumn('yt_url');

        });
    }
};
