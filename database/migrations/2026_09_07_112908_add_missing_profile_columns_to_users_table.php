<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Backfill user columns that were introduced directly on production MySQL
 * without accompanying migrations. Guarded so it only adds the columns
 * missing on the current environment (fresh sqlite for tests, no-op on
 * production).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(1)->after('role');
            }
            if (! Schema::hasColumn('users', 'telephone')) {
                $table->string('telephone')->nullable();
            }
            if (! Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable();
            }
            if (! Schema::hasColumn('users', 'ville')) {
                $table->string('ville')->nullable();
            }
            if (! Schema::hasColumn('users', 'province')) {
                $table->string('province')->nullable();
            }
            if (! Schema::hasColumn('users', 'region')) {
                $table->string('region')->nullable();
            }
            if (! Schema::hasColumn('users', 'society')) {
                $table->string('society')->nullable();
            }
            if (! Schema::hasColumn('users', 'session_active_id')) {
                $table->string('session_active_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Never drop these columns automatically — they hold production data.
    }
};
