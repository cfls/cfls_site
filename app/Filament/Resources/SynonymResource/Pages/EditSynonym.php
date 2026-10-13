<?php

namespace App\Filament\Resources\SynonymResource\Pages;

use App\Filament\Resources\SynonymResource;
use App\Models\Synonym;
use App\Models\VideoTheme;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditSynonym extends EditRecord
{
    protected static string $resource = SynonymResource::class;

    /**
     * Resolve by plain ID — bypasses the canonical filter in getEloquentQuery()
     * so any VideoTheme ID (including non-canonical ones) opens correctly.
     */
    protected function resolveRecord(int|string $key): Model
    {
        return VideoTheme::query()
            ->where('active', true)
            ->with('synonyms')
            ->findOrFail($key);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('delete')
                ->label('Supprimer les synonymes')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Supprimer les synonymes')
                ->modalDescription('Voulez-vous supprimer tous les synonymes pour ce mot ? Cette action est irréversible.')
                ->action(function (): void {
                    $record = $this->getRecord();
                    Synonym::whereIn(
                        'video_theme_cloudinary_id',
                        SynonymResource::getGroupIds($record)
                    )->delete();

                    Notification::make()->title('Synonymes supprimés')->success()->send();
                    $this->redirect(SynonymResource::getUrl('index'));
                }),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        $synonyms = Synonym::whereIn(
            'video_theme_cloudinary_id',
            SynonymResource::getGroupIds($record)
        )
            ->orderBy('word')
            ->pluck('word')
            ->unique()
            ->map(fn ($w) => ['word' => $w])
            ->values()
            ->toArray();

        $data['synonyms'] = $synonyms;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $synonymWords = collect($data['synonyms'] ?? [])
            ->filter(fn ($s) => !empty(trim($s['word'] ?? '')))
            ->map(fn ($s) => trim($s['word']))
            ->unique()
            ->values();

        DB::transaction(function () use ($record, $synonymWords): void {
            $groupIds = SynonymResource::getGroupIds($record);

            $existing = Synonym::whereIn('video_theme_cloudinary_id', $groupIds)
                ->pluck('word')
                ->map(fn ($w) => trim($w));

            $toDelete = $existing->diff($synonymWords);
            $toCreate = $synonymWords->diff($existing);

            if ($toDelete->isNotEmpty()) {
                Synonym::whereIn('video_theme_cloudinary_id', $groupIds)
                    ->whereIn('word', $toDelete)
                    ->delete();
            }

            foreach ($toCreate as $word) {
                Synonym::create([
                    'video_theme_cloudinary_id' => $record->id,
                    'word'                      => $word,
                ]);
            }
        });

        return $record;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Synonymes mis à jour')
            ->success();
    }
}
