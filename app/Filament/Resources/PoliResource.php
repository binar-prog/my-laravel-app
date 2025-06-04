<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PoliResource\Pages;
use App\Filament\Resources\PoliResource\RelationManagers;
use Filament\Tables\Actions\CreateAction; 
use Filament\Tables\Actions\ImportAction; 
use App\Models\Poli;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PoliResource extends Resource
{
    protected static ?string $model = Poli::class;
    protected static ?string $navigationLabel = 'Poli';
    protected static ?string $navigationIcon = 'fas-hand-holding-medical';
    protected static ?string $navigationGroup = 'Keperluan Rumah Sakit';
    protected static ?string $modelLabel = 'Poli'; // Untuk singular: "Poli"
    protected static ?string $pluralModelLabel = 'Poli'; // Untuk plural: "Poli" (jika Anda ingin tetap "Poli" saja)
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_poli')
                    ->label('Nama Poli')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('jumlah_dokter')
                    ->label('Jumlah Dokter')
                    ->required(),
                Forms\Components\TextInput::make('jumlah_perawat')
                    ->label('Jumlah Perawat')
                    ->required(),
                Forms\Components\TextInput::make('jumlah_staf')
                    ->label('Jumlah Staf')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_poli')
                ->label('Nama Poli')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_dokter')
                ->label('Jumlah Dokter')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_perawat')
                ->label('Jumlah Perawat')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_staf')
                ->label('Jumlah Staf')
                ->searchable()
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([ // <<< TAMBAHKAN BAGIAN INI <<<
                CreateAction::make()
                    ->label('Tambah Poli'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPolis::route('/'),
           // 'create' => Pages\CreatePoli::route('/create'),
            'edit' => Pages\EditPoli::route('/{record}/edit'),
        ];
    }
}
