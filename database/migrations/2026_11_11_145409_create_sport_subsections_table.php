<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sport_subsections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_section_id')
                ->constrained('sport_sections')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('url', 2048)->nullable();          // URL de Cloudinary
            $table->boolean('status')->default(true);         // activo / inactivo
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['sport_section_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sport_subsections');
    }
};
