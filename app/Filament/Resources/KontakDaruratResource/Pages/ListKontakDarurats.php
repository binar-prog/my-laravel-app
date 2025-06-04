<?php

namespace App\Filament\Resources\KontakDaruratResource\Pages;

use App\Filament\Resources\KontakDaruratResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKontakDarurats extends ListRecords
{
    protected static string $resource = KontakDaruratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
