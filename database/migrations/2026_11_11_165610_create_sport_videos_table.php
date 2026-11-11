<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sport_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_subsection_id')
                ->constrained('sport_subsections')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('title');                          // "Contrôle de ballon"
            $table->string('public_id')->unique();            // id del vídeo en Cloudinary
            $table->string('url', 2048);                      // URL de Cloudinary
            $table->boolean('status')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['sport_subsection_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sport_videos');
    }
};
