<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            // Type d'événement : présentation du 17/04 ou stage VV
            $table->string('type')->default('presentation')->after('email');

            // Champs spécifiques au stage Visual Vernacular
            $table->string('prenom')->nullable()->after('nom');
            $table->string('profil')->nullable()->after('type');         // adulte | adolescent
            $table->boolean('irhov')->default(false)->after('profil');
            $table->unsignedSmallInteger('prix')->nullable()->after('irhov');
        });
    }

    public function down(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->dropColumn(['type', 'prenom', 'profil', 'irhov', 'prix']);
        });
    }
};