<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sport_subsections', function (Blueprint $table) {
            $table->unsignedTinyInteger('video_number')->nullable()->unique()->after('sport_section_id');
        });
    }

    public function down(): void
    {
        Schema::table('sport_subsections', function (Blueprint $table) {
            $table->dropUnique(['video_number']);
            $table->dropColumn('video_number');
        });
    }
};
