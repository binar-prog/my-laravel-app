<?php

namespace App\Filament\Resources\PerawatResource\Pages;

use App\Filament\Resources\PerawatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerawats extends ListRecords
{
    protected static string $resource = PerawatResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\CreateAction::make(),
        ];
    }
}
