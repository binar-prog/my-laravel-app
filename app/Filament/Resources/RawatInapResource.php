<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RawatInapResource\Pages;
use App\Filament\Resources\RawatInapResource\RelationManagers;
use Filament\Tables\Actions\CreateAction; 
use Filament\Tables\Actions\ImportAction; 
use App\Models\Kamar;
use App\Models\RawatInap;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RawatInapResource extends Resource
{
    protected static ?string $model = RawatInap::class;
    protected static ?string $navigationLabel = 'Rawat Inap';
    protected static ?string $navigationIcon = 'fas-bed-pulse';
    protected static ?string $navigationGroup = 'Keperluan Pasien';
    protected static ?string $modelLabel = 'Rawat Inap'; 
    protected static ?string $pluralModelLabel = 'Rawat Inap'; 
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_rawat')
                    ->label('ID Rawat Inap')
                    ->default(function () {
                        $latest = \App\Models\RawatInap::latest('id_rawat')->first();
                        $lastNumber = $latest ? (int) str_replace('RAW', '', $latest->id_rawat) : 0;
                        $newId = 'RAW' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                        return $newId;
                    })
                    ->disabled()
                    ->dehydrated(true) // tetap dikirim walau disabled
                    ->required()
                    ->maxLength(20),

                Forms\Components\Select::make('id_pasien')
                    ->label('Pilih Pasien')
                    ->relationship('pasien', 'nama_pasien') // Relasi ke model Pasien, kolom nama
                    ->searchable() // 👈 Enable pencarian
                    ->preload() // Load opsi awal (opsional)
                    ->required()
                    ->native(false),

                Forms\Components\Select::make('id_kamar')
                    ->label('Pilih Kamar Kosong')
                    ->options(function () {
                        return Kamar::where('status', 'kosong')
                            ->pluck('id_kamar', 'id_kamar'); // value => label
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->native(false)
                    ->displayFormat('j F Y') // Format: 23 Mei 2025
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->native(false)
                    ->displayFormat('j F Y') // Format: 23 Mei 2025
                    ->required()
                    ->afterOrEqual('tanggal_masuk'),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_rawat')
                    ->label('ID Rawat Inap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pasien.nama_pasien')
                    ->label('Nama Pasien')
                    ->searchable() // Bisa dicari berdasarkan nama
                    ->sortable() // Bisa di-sort
                    ->tooltip(fn ($record) => 'ID: ' . $record->id_pasien),
                Tables\Columns\TextColumn::make('id_kamar')
                    ->label('ID Kamar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->translatedFormat('j F Y'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->translatedFormat('j F Y'))
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
                    ->label('Tambah Data'),
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
            'index' => Pages\ListRawatInaps::route('/'),
            //'create' => Pages\CreateRawatInap::route('/create'),
            'edit' => Pages\EditRawatInap::route('/{record}/edit'),
        ];
    }
}
