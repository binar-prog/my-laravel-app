<?php

namespace App\Filament\Resources\PreskripsiResource\Pages;

use App\Filament\Resources\PreskripsiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreskripsis extends ListRecords
{
    protected static string $resource = PreskripsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\CreateAction::make(),
        ];
    }
}
