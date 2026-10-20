<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncVideoChecks extends Command
{
    protected $signature = 'videos:sync-checks {--dry-run : Muestra los registros faltantes sin insertar}';
    protected $description = 'Inserta en video_checks los registros activos de video_themes_cloudinary que faltan';

    public function handle(): int
    {
        $missing = DB::table('video_themes_cloudinary as vtc')
            ->leftJoin('video_checks as vc', 'vtc.id', '=', 'vc.video_theme_cloudinary_id')
            ->where('vtc.active', 1)
            ->whereNull('vc.id')
            ->select('vtc.id', 'vtc.title', 'vtc.url')
            ->get();

        if ($missing->isEmpty()) {
            $this->info('No hay registros faltantes. video_checks está sincronizado.');
            return 0;
        }

        $this->info("Registros faltantes encontrados: {$missing->count()}");
        $this->table(['vtc.id', 'title', 'url'], $missing->map(fn ($r) => [
            $r->id, $r->title, $r->url ?? '(null)',
        ])->toArray());

        if ($this->option('dry-run')) {
            $this->warn('Modo dry-run: no se insertó nada.');
            return 0;
        }

        $insertados = 0;
        $omitidos   = 0;

        foreach ($missing as $row) {
            $publicId = $this->extractPublicId($row->url ?? '');

            if ($publicId === '') {
                $this->warn("ID {$row->id} «{$row->title}» sin URL válida — omitido.");
                $omitidos++;
                continue;
            }

            $existing = DB::table('video_checks')->where('public_id', $publicId)->first();

            DB::table('video_checks')->insert([
                'video_theme_cloudinary_id' => $row->id,
                'public_id'                 => $publicId,
                'grupo'                     => $existing->grupo ?? 'a_reviser',
                'desviacion'                => $existing->desviacion ?? null,
                'frames_con_persona'        => $existing->frames_con_persona ?? 0,
                'frames_total'              => $existing->frames_total ?? 0,
                'duracion'                  => $existing->duracion ?? null,
                'revisado'                  => $existing->revisado ?? false,
                'created_at'                => now(),
                'updated_at'                => now(),
            ]);

            $source = $existing ? ' (copiado de vtc=' . $existing->video_theme_cloudinary_id . ')' : '';
            $this->line(" + Insertado: {$publicId}{$source}");
            $insertados++;
        }

        $this->info("Completado: {$insertados} insertados, {$omitidos} omitidos.");

        return $omitidos > 0 ? 1 : 0;
    }

    private function extractPublicId(string $url): string
    {
        if ($url === '') {
            return '';
        }

        $path = parse_url($url, PHP_URL_PATH) ?? '';
        // /cloud/video/upload/v123456/public_id.ext  OR  /cloud/video/upload/public_id.ext
        $pos = strpos($path, '/upload/');
        if ($pos === false) {
            return '';
        }

        $after = substr($path, $pos + strlen('/upload/'));
        // strip optional version segment v<digits>/
        $after = preg_replace('#^v\d+/#', '', $after);
        // remove extension
        $after = pathinfo($after, PATHINFO_FILENAME);

        return urldecode($after);
    }
}
