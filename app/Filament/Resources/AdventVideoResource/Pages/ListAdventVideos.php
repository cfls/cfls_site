<?php

namespace App\Filament\Resources\AdventVideoResource\Pages;

use App\Filament\Resources\AdventVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdventVideos extends ListRecords
{
    protected static string $resource = AdventVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
