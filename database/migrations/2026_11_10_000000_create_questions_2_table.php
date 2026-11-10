<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// questions_2 se creó a mano en la base; esta migración la documenta y la crea en entornos nuevos
// (tests). Si la tabla ya existe no hace nada, para no tocar los datos actuales.
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('questions_2')) {
            return;
        }

        Schema::create('questions_2', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('syllabu_id');
            $table->unsignedInteger('theme_id');
            $table->unsignedInteger('video_id')->nullable();
            $table->string('question_text')->default('Écrivez le mot correspondant au signe');
            $table->string('type', 20)->default('text');
            $table->json('options');
            $table->string('answer')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->index(['syllabu_id', 'theme_id', 'type'], 'idx_syllabu_theme_type');
        });
    }

    public function down(): void
    {
        // No se borra: la tabla existía antes de esta migración.
    }
};
