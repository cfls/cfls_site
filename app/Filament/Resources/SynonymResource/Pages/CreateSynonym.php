<?php

namespace App\Filament\Resources\SynonymResource\Pages;

use App\Filament\Resources\SynonymResource;
use App\Models\Synonym;
use App\Models\VideoTheme;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateSynonym extends CreateRecord
{
    protected static string $resource = SynonymResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $videoTheme = VideoTheme::findOrFail($data['video_theme_id']);

        $synonymWords = collect($data['synonyms'] ?? [])
            ->filter(fn ($s) => !empty(trim($s['word'] ?? '')))
            ->map(fn ($s) => trim($s['word']))
            ->unique()
            ->values();

        DB::transaction(function () use ($videoTheme, $synonymWords): void {
            $groupIds = SynonymResource::getGroupIds($videoTheme);
            Synonym::whereIn('video_theme_cloudinary_id', $groupIds)->delete();

            foreach ($synonymWords as $word) {
                Synonym::create([
                    'video_theme_cloudinary_id' => $videoTheme->id,
                    'word' => $word,
                ]);
            }
        });

        return $videoTheme;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Synonymes enregistrés')
            ->success();
    }
}
