<?php

namespace App\Filament\Resources\PemeriksaanLanjutanResource\Pages;

use App\Filament\Resources\PemeriksaanLanjutanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPemeriksaanLanjutans extends ListRecords
{
    protected static string $resource = PemeriksaanLanjutanResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\CreateAction::make(),
        ];
    }
}
