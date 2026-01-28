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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            // File details
            $table->string('file_name');       // hero.jpg
            $table->string('file_path');       // pages/home/hero.jpg
            $table->string('file_url');        // /storage/pages/home/hero.jpg
            $table->string('mime_type')->nullable();   // image/jpeg
            $table->string('type')->nullable();        // image, video, document
            $table->integer('size')->nullable();       // in bytes

            // Optional: which page uploaded this (not required)
            $table->unsignedBigInteger('page_id')->nullable();
            $table->string('field')->nullable(); // hero_image, gallery, campus_video

            // User tracking
            $table->unsignedBigInteger('uploaded_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('page_id')->references('id')->on('pages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
