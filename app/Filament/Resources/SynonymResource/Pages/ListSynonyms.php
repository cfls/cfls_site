<?php

namespace App\Filament\Resources\SynonymResource\Pages;

use App\Filament\Resources\SynonymResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSynonyms extends ListRecords
{
    protected static string $resource = SynonymResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * Apply the canonical-ID filter and synonym existence check here,
     * so the table shows one grouped row per display_title with synonyms.
     * getEloquentQuery() stays simple for record resolution (Edit page).
     */
    protected function getTableQuery(): Builder
    {
        // strip2 uses vt2 alias (subquery table); stripExprOuter uses the fully-qualified
        // outer table name so MySQL does not resolve the bare 'title' column to vt2.title.
        $strip2 = SynonymResource::stripExpr('vt2.title');
        $strip1 = SynonymResource::stripExprOuter();

        return parent::getTableQuery()
            ->whereRaw("id = (
                SELECT MIN(vt2.id)
                FROM video_themes_cloudinary vt2
                WHERE vt2.active = 1
                AND ({$strip2}) = ({$strip1})
            )")
            ->whereHas('synonyms');
    }
}
