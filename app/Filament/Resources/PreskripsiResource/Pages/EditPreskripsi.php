<?php

namespace App\Filament\Resources\PreskripsiResource\Pages;

use App\Filament\Resources\PreskripsiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreskripsi extends EditRecord
{
    protected static string $resource = PreskripsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
