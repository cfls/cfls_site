<?php

namespace App\Filament\Resources\AdventVideoResource\Pages;

use App\Filament\Resources\AdventVideoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdventVideo extends CreateRecord
{
    protected static string $resource = AdventVideoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
