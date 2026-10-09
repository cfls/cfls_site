<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // video_themes_cloudinary was created without a PRIMARY KEY declaration.
        // Add it so foreign key references to this table work correctly.
        if (!$this->hasPrimaryKey('video_themes_cloudinary')) {
            DB::statement('ALTER TABLE video_themes_cloudinary ADD PRIMARY KEY (id)');
            DB::statement('ALTER TABLE video_themes_cloudinary MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        Schema::create('synonyms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_theme_cloudinary_id')
                  ->constrained('video_themes_cloudinary')
                  ->onDelete('cascade');
            $table->string('word');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('synonyms');
    }

    private function hasPrimaryKey(string $table): bool
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = 'PRIMARY'");
        return count($indexes) > 0;
    }
};
