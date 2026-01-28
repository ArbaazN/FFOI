<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('author')->nullable();
            $table->boolean('feature_content')->default(0)->comment('0 = no, 1 = yes');
            $table->string('images')->nullable(); // or json()
            $table->foreignId('category_id')->nullable()->constrained('blog_categories')->nullOnDelete();
            $table->boolean('status')->default(1)->comment('0 = inactive, 1 = active');
            $table->date('publish_date')->nullable();
            $table->text('content')->nullable();
            $table->timestamp('published_at')->nullable();

            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 170)->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
