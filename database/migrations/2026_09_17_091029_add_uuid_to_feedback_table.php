<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('feedback', 'uuid')) {
            Schema::table('feedback', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });
        }

        DB::statement('UPDATE feedback SET uuid = UUID() WHERE uuid IS NULL');

        DB::statement('ALTER TABLE feedback MODIFY uuid CHAR(36) NOT NULL');

        $hasUnique = collect(DB::select("SHOW INDEX FROM feedback WHERE Key_name = 'feedback_uuid_unique'"))->isNotEmpty();

        if (! $hasUnique) {
            Schema::table('feedback', function (Blueprint $table) {
                $table->unique('uuid');
            });
        }
    }

    public function down(): void
    {
        $hasUnique = collect(DB::select("SHOW INDEX FROM feedback WHERE Key_name = 'feedback_uuid_unique'"))->isNotEmpty();

        if ($hasUnique) {
            Schema::table('feedback', function (Blueprint $table) {
                $table->dropUnique(['uuid']);
            });
        }

        if (Schema::hasColumn('feedback', 'uuid')) {
            Schema::table('feedback', function (Blueprint $table) {
                $table->dropColumn('uuid');
            });
        }
    }
};
