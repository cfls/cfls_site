<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurgeSyncTombstones extends Command
{
    protected $signature = 'sync:purge-tombstones {--days=90 : Minimum age in days of tombstones to purge}';

    protected $description = 'Hard-delete sync tombstones older than N days';

    public function handle(): int
    {
        $days   = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        $count = DB::table('synonyms')
            ->whereNotNull('deleted_at')
            ->where('deleted_at', '<', $cutoff)
            ->delete();

        Log::info('sync:purge-tombstones: tombstones purged', ['count' => $count, 'cutoff' => $cutoff]);
        $this->info("Tombstones purged: {$count}");

        return self::SUCCESS;
    }
}
