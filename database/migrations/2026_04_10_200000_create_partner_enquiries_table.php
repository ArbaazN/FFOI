<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('contact');
            $table->string('email');
            $table->string('preferred_territory');
            $table->string('city');
            $table->string('current_occupation_business');
            $table->text('partner_reason');
            $table->boolean('consent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_enquiries');
    }
};
