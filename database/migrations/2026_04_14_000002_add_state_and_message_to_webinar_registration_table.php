<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webinar_registration', function (Blueprint $table) {
            $table->string('state')->nullable()->after('contact');
            $table->text('message')->nullable()->after('topic_interested_in');
        });
    }

    public function down(): void
    {
        Schema::table('webinar_registration', function (Blueprint $table) {
            $table->dropColumn(['state', 'message']);
        });
    }
};
