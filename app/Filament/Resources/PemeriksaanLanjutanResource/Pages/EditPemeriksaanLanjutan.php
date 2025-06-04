<?php

namespace App\Filament\Resources\PemeriksaanLanjutanResource\Pages;

use App\Filament\Resources\PemeriksaanLanjutanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPemeriksaanLanjutan extends EditRecord
{
    protected static string $resource = PemeriksaanLanjutanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
