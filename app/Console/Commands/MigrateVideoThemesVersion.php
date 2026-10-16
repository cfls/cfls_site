<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateVideoThemesVersion extends Command
{
    protected $signature = 'videos:migrate-themes-version';
    protected $description = 'Migra datos de video_themes_cloudinary_version a video_themes_cloudinary';

    public function handle(): int
    {
        if (! Schema::hasTable('video_themes_cloudinary_version')) {
            $this->error('La tabla video_themes_cloudinary_version no existe.');
            return 1;
        }

        $rows = DB::table('video_themes_cloudinary_version')->get();

        if ($rows->isEmpty()) {
            $this->info('La tabla video_themes_cloudinary_version está vacía. Nada que migrar.');
            return 0;
        }

        $this->info("Encontrados {$rows->count()} registros. Iniciando migración…");

        $destCols = Schema::getColumnListing('video_themes_cloudinary');

        $migrados = 0;
        $omitidos = 0;
        $errores  = 0;

        foreach ($rows as $row) {
            $data = collect((array) $row)
                ->only($destCols)
                ->forget('id')
                ->toArray();

            if (empty($data['slug'])) {
                $this->warn("Fila sin slug, omitida.");
                $omitidos++;
                continue;
            }

            try {
                $existe = DB::table('video_themes_cloudinary')
                    ->where('slug', $data['slug'])
                    ->exists();

                if ($existe) {
                    DB::table('video_themes_cloudinary')
                        ->where('slug', $data['slug'])
                        ->update(array_merge($data, ['updated_at' => now()]));
                } else {
                    DB::table('video_themes_cloudinary')->insert(
                        array_merge($data, ['created_at' => now(), 'updated_at' => now()])
                    );
                }

                $migrados++;
            } catch (\Throwable $e) {
                $this->error("Error en slug [{$data['slug']}]: " . $e->getMessage());
                $errores++;
            }
        }

        $this->info("Migración completada: {$migrados} migrados, {$omitidos} omitidos, {$errores} errores.");

        return $errores > 0 ? 1 : 0;
    }
}
