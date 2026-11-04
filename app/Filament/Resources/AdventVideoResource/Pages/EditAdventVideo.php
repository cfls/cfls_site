<?php

namespace App\Filament\Resources\AdventVideoResource\Pages;

use App\Filament\Resources\AdventVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdventVideo extends EditRecord
{
    protected static string $resource = AdventVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
