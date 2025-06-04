<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KamarResource\Pages;
use App\Filament\Resources\KamarResource\RelationManagers;
use Filament\Tables\Actions\CreateAction; 
use Filament\Tables\Actions\ImportAction; 
use App\Models\Kamar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KamarResource extends Resource
{
    protected static ?string $model = Kamar::class;
    protected static ?string $navigationLabel = 'Kamar';
    protected static ?string $navigationIcon = 'fas-bed';
    protected static ?string $navigationGroup = 'Keperluan Rumah Sakit';
    protected static ?string $modelLabel = 'Kamar'; 
    protected static ?string $pluralModelLabel = 'Kamar'; 
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_kamar')
                    ->label('ID Kamar')
                    ->default(function () {
                        $latest = \App\Models\Kamar::latest('id_kamar')->first();
                        $lastNumber = $latest ? (int) str_replace('KM', '', $latest->id_obat) : 0;
                        $newId = 'KM' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                        return $newId;
                    })
                    ->disabled()
                    ->dehydrated(true) // tetap dikirim walau disabled
                    ->required()
                    ->maxLength(20),

                Forms\Components\TextInput::make('gedung')
                ->label('Gedung')
                ->required(),

                Forms\Components\TextInput::make('lantai')
                ->label('Lantai')
                ->required(),

                Forms\Components\Select::make('tipe')
                    ->label('Tipe Kamar')
                    ->required()
                    ->options([
                        'Kelas 1' => 'Kelas 1',
                        'Kelas 2' => 'Kelas 2',
                        'ICU' => 'ICU',
                        'VIP' => 'VIP',
                    ]),

                Forms\Components\TextInput::make('no_kamar')
                ->label('No. Kamar')
                ->required(),

                Forms\Components\TextInput::make('no_bed')
                ->label('No. Bed')
                ->required(),

                Forms\Components\Select::make('status')
                ->label('Status Kamar')
                ->options([
                    'Terisi' => 'Terisi',
                    'Kosong' => 'Kosong',
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_kamar')
                    ->label('ID Kamar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('gedung')
                    ->label('Gedung')
                    ->sortable(),
                Tables\Columns\TextColumn::make('lantai')
                    ->label('Lantai')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe')
                    ->label('Tipe Kamar')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_kamar')
                    ->label('No. Kamar')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_bed')
                    ->label('No. Bed')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Kamar')
                    ->sortable()
                    ->searchable(),
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
                    ->label('Tambah Kamar'),
            ])
            ;
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
            'index' => Pages\ListKamars::route('/'),
            //'create' => Pages\CreateKamar::route('/create'),
            'edit' => Pages\EditKamar::route('/{record}/edit'),
        ];
    }
}
