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
        Schema::create('video_quiz_items', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Ejemplo: "Saludo en LESCO"
            $table->foreignId('video_theme_cloudinary_id')->nullable()->constrained('video_themes_cloudinary')->onDelete('cascade');
            $table->string('question'); // Pregunta
            $table->json('options'); // Respuestas posibles
            $table->string('correct_answer'); // Respuesta correcta
            $table->boolean('active')->default(true);
            $table->foreignId('syllabu_id')->nullable()->constrained('syllabus')->onDelete('cascade');
            $table->foreignId('theme_id')->nullable()->constrained('themes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_quiz_items');
    }
};
