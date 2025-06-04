<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObatResource\Pages;
use App\Filament\Resources\ObatResource\RelationManagers;
use Filament\Tables\Actions\CreateAction; 
use Filament\Tables\Actions\ImportAction; 
use App\Models\Obat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObatResource extends Resource
{
    protected static ?string $model = Obat::class;
    protected static ?string $navigationLabel = 'Obat';
    protected static ?string $navigationIcon = 'fas-pills';
    protected static ?string $navigationGroup = 'Keperluan Farmasi';
    protected static ?string $modelLabel = 'Obat'; 
    protected static ?string $pluralModelLabel = 'Obat'; 
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_obat')
                    ->label('ID Obat')
                    ->default(function () {
                        $latest = \App\Models\Obat::latest('id_obat')->first();
                        $lastNumber = $latest ? (int) str_replace('OB', '', $latest->id_obat) : 0;
                        $newId = 'OB' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                        return $newId;
                    })
                    ->disabled()
                    ->dehydrated(true) // tetap dikirim walau disabled
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('nama_obat')
                ->label('Nama Obat')
                ->required()
                ->maxLength(100),

                Forms\Components\TextInput::make('stok')
                    ->label('Stok')
                    ->required(),

                Forms\Components\TextInput::make('harga_satuan')
                ->label('Harga Satuan (Rp)')
                    ->numeric()
                    ->required()
                    ->prefix('Rp')
                    ->suffix('/item')
                    ->inputMode('decimal')  // Mode input desimal
                    ->step(0.01)  // Langkah increment 0.01 untuk 2 digit desimal
                    ->minValue(0)  // Nilai minimal 0
                    ->maxValue(9999999.99)  // Maksimal sesuai dengan 10 digit (termasuk 2 desimal)
                    ->rules([
                        'numeric',
                        'regex:/^\d{1,7}(\.\d{1,2})?$/'  // Validasi format: max 7 digit utama + 2 desimal
                    ])
                    ->columnSpan(1),

                Forms\Components\Select::make('jenis')
                ->label('Jenis Obat')
                ->required()
                ->options([
                    'Tablet' => 'Tablet',
                    'Kapsul' => 'Kapsul',
                    'Sirup' => 'Sirup',
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_obat')
                    ->label('ID Obat')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_obat')
                    ->label('Nama Obat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_satuan')
                    ->label('Harga')
                    ->formatStateUsing(function ($state) {
                        return 'Rp ' . number_format($state, 0, ',', '.') . ' /item';
                        // Hasil: Rp 1.500.000 /item
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis Obat')

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
                    ->label('Tambah Obat'),
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
            'index' => Pages\ListObats::route('/'),
            //'create' => Pages\CreateObat::route('/create'),
            'edit' => Pages\EditObat::route('/{record}/edit'),
        ];
    }
}
