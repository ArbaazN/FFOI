<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();

            $table->string('site_name')->nullable();
            $table->string('site_title')->nullable();
            $table->string('tagline')->nullable();

            // Media
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('footer_logo')->nullable();
            $table->string('og_image')->nullable();

            // Contact Information
            $table->string('email')->nullable();
            $table->string('support_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pincode')->nullable();
            $table->string('map_link')->nullable();
            $table->string('map_url')->nullable();

            // Social Media Links
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('telegram')->nullable();

            // LinkedIn Plugin
            $table->string('linkedin_company_id')->nullable();
            $table->boolean('linkedin_share_enable')->default(0);

            // SEO Settings
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('google_analytics_id')->nullable();
            $table->string('meta_author')->nullable();

            // OG Extra Fields
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();

            $table->text('admission_form_link')->nullable();
            $table->text('google_analytics_code')->nullable();
            $table->text('google_tag_manager_code')->nullable();
            $table->text('facebook_pixel_code')->nullable();
            $table->text('linkedin_insight_code')->nullable();

            // Footer Info
            $table->text('footer_text')->nullable();
            $table->string('copyright')->nullable();

            // Website Configuration
            $table->boolean('maintenance_mode')->default(0);
            $table->boolean('enable_registration')->default(1);
            $table->boolean('is_active')->default(1);
        
            // $table->text('header_custom_scripts')->nullable()->after('linkedin_insight_code');
            // $table->text('footer_custom_scripts')->nullable()->after('header_custom_scripts');

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
        Schema::dropIfExists('website_settings');
    }
};
