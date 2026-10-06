<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE suggestions MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE suggestions MODIFY id BIGINT UNSIGNED NOT NULL');
    }
};
