<?php

namespace App\Filament\Resources\KontakDaruratResource\Pages;

use App\Filament\Resources\KontakDaruratResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKontakDarurat extends EditRecord
{
    protected static string $resource = KontakDaruratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
