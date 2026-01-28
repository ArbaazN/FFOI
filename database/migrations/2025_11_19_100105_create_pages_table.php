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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pageable_id')->nullable();
            $table->string('pageable_type')->nullable();

            $table->string('title');
            $table->string('slug')->unique();
            
            $table->json('original_content')->nullable();
            $table->json('content');

            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keys')->nullable();

            // Advanced SEO
            $table->string('canonical_url')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('robots')->default('index,follow');

            $table->boolean('show_in_menu')->default(false);
            $table->integer('menu_order')->default(0);
            $table->string('external_url')->nullable();

            $table->enum('status', ['draft', 'published', 'scheduled', 'deleted'])->default('published');
            $table->timestamp('publish_at')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
