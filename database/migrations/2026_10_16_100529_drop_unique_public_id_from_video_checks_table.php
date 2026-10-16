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
        Schema::table('video_checks', function (Blueprint $table) {
            $table->dropUnique('video_checks_public_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('video_checks', function (Blueprint $table) {
            $table->unique('public_id');
        });
    }
};
