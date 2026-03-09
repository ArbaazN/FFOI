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
        Schema::create('membership_type', function (Blueprint $table) {
            $table->id();
            $table->string('headline')->nullable();
            $table->string('sub_headline')->nullable();
            $table->text('short_desc')->nullable();
            $table->string('it_is_for')->nullable();
            $table->string('purpose')->nullable();
            $table->text('planned_access')->nullable();
            $table->text('contribute_through')->nullable();
            $table->text('priviledges')->nullable();
            $table->string('priviledges_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_type');
    }
};
