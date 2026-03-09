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
        Schema::create('membership_benefit', function (Blueprint $table) {
            $table->id();
            $table->string('Benefits')->nullable();
            $table->string('Honorary')->nullable();
            $table->string('Literacy')->nullable();
            $table->string('Student')->nullable();
            $table->string('Professional')->nullable();
            $table->string('Institutional')->nullable();
            $table->string('disclaaimer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_benefit');
    }
};
