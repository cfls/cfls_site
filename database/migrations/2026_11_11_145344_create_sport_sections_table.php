<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sport_sections', function (Blueprint $table) {
            $table->id();
            $table->string('number', 10)->nullable();      // I, II, III...
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->unsignedSmallInteger('page')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sport_sections');
    }
};
