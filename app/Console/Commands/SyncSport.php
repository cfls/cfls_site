<?php

namespace App\Console\Commands;

use App\Models\SportSubsection;
use App\Models\SportVideo;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\Search\SearchApi;
use Cloudinary\Configuration\Configuration;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SyncSport extends Command
{
    protected $signature = 'sport:sync
                            {root=Ekho Sport : Carpeta principal en Cloudinary}
                            {--dry-run : Solo mostrar, sin guardar en la base de datos}';

    protected $description = 'Sincroniza todos los videos de cada carpeta numerada de Cloudinary con sport_videos';

    public function handle(): int
    {
        Configuration::instance(env('CLOUDINARY_URL'));

        $root    = $this->argument('root');
        $folders = (new AdminApi())->subFolders($root, ['max_results' => 500])['folders'] ?? [];

        if (empty($folders)) {
            $this->error("No se encontraron subcarpetas en \"{$root}\".");
            return self::FAILURE;
        }

        $dry   = $this->option('dry-run');
        $rows  = [];
        $total = 0;

        foreach ($folders as $folder) {
            if (! preg_match('/^\s*(\d+)\s*\./', $folder['name'], $m)) {
                $rows[] = ['-', $folder['name'], 'Sin numero, ignorada', 0];
                continue;
            }

            $number     = (int) $m[1];
            $subsection = SportSubsection::where('video_number', $number)->first();
            $videos     = $this->videosIn($folder['path']);

            if (! $subsection) {
                $rows[] = [$number, $folder['name'], 'Sin subseccion en la BD', count($videos)];
                continue;
            }

            if (empty($videos)) {
                $rows[] = [$number, $folder['name'], 'Sin videos', 0];
                continue;
            }

            if (! $dry) {
                $keep = [];

                foreach ($videos as $i => $video) {
                    SportVideo::updateOrCreate(
                        ['public_id' => $video['public_id']],
                        [
                            'sport_subsection_id' => $subsection->id,
                            'title'      => $this->titleFor($video),
                            'url'        => $video['secure_url'],
                            'sort_order' => $i + 1,
                        ]
                    );
                    $keep[] = $video['public_id'];
                }

                // Borra de la BD los videos que ya no estan en esa carpeta de Cloudinary
                SportVideo::where('sport_subsection_id', $subsection->id)
                    ->whereNotIn('public_id', $keep)
                    ->delete();

                // Portada de la subseccion = primer video
                $subsection->update(['url' => $videos[0]['secure_url']]);
            }

            $total += count($videos);
            $rows[] = [$number, $folder['name'], 'OK', count($videos)];
        }

        usort($rows, fn ($a, $b) => (int) $a[0] <=> (int) $b[0]);

        $this->table(['N', 'Carpeta', 'Estado', 'Videos'], $rows);
        $this->info("Total: {$total} videos.");

        if ($dry) {
            $this->warn('Dry-run: no se ha guardado nada.');
        }

        return self::SUCCESS;
    }

    /** Todos los videos de una carpeta, en el orden en que se subieron. */
    private function videosIn(string $path): array
    {
        foreach (['asset_folder', 'folder'] as $field) {
            $result = (new SearchApi())
                ->expression("resource_type:video AND {$field}=\"{$path}\"")
                ->sortBy('created_at', 'asc')
                ->maxResults(500)
                ->execute();

            if (! empty($result['resources'])) {
                return $result['resources'];
            }
        }

        return [];
    }

    /** "Contrôle De Ballon" => "Contrôle de ballon" */
    private function titleFor(array $video): string
    {
        $name = $video['display_name'] ?? basename($video['public_id']);
        $name = preg_replace('/_[a-z0-9]{6}$/i', '', $name);   // quita el sufijo aleatorio de Cloudinary
        $name = str_replace(['_', '-'], ' ', $name);

        return Str::ucfirst(mb_strtolower(trim($name)));
    }
}
