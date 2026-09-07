<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL only — sqlite (test suite) skips.
        if (\DB::getDriverName() === 'mysql') {
            \DB::statement('ALTER TABLE questions MODIFY COLUMN type VARCHAR(50) NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (\DB::getDriverName() === 'mysql') {
            \DB::statement("ALTER TABLE questions MODIFY COLUMN type ENUM('choice', 'text', 'video-choice', 'yes-no') NOT NULL");
        }
    }
};
