<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // DROP + re-add PRIMARY KEY to force AUTO_INCREMENT — compatible with shared hosting
        DB::statement('ALTER TABLE suggestions MODIFY COLUMN id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE suggestions MODIFY COLUMN id BIGINT UNSIGNED NOT NULL');
    }
};
