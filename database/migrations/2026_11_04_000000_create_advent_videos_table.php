<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advent_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedTinyInteger('day');                 // 1..25
            $table->string('video_url');                        // Facebook ou Instagram reel
            $table->boolean('is_published')->default(false)->index();
            $table->timestamps();

            $table->unique(['year', 'day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advent_videos');
    }
};
