<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $map = [
        'revisar'     => 'a_reviser',
        'descentrado' => 'decentre',
        'sin_persona' => 'sans_personne',
        'regrabar'    => 'a_refaire',
        'error'       => 'erreur',
    ];

    public function up(): void
    {
        foreach ($this->map as $old => $new) {
            DB::table('video_checks')->where('grupo', $old)->update(['grupo' => $new]);
        }
    }

    public function down(): void
    {
        foreach ($this->map as $old => $new) {
            DB::table('video_checks')->where('grupo', $new)->update(['grupo' => $old]);
        }
    }
};
