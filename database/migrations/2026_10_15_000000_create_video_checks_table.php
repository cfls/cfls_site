<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_theme_cloudinary_id')
                  ->unique()
                  ->constrained('video_themes_cloudinary')
                  ->cascadeOnDelete();
            $table->string('public_id')->unique();
            // ok | descentrado | cabeza_cortada | sin_persona | revisar | error
            $table->string('grupo', 30)->index();
            $table->decimal('desviacion', 5, 3)->nullable();   // 0.150 = 15 % del ancho
            $table->unsignedTinyInteger('frames_con_persona')->default(0);
            $table->unsignedTinyInteger('frames_total')->default(0);
            $table->decimal('duracion', 8, 2)->nullable();     // segundos
            $table->boolean('revisado')->default(false)->index(); // corregido a mano
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_checks');
    }
};
