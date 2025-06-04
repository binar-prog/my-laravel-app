<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreskripsiResource\Pages;
use App\Filament\Resources\PreskripsiResource\RelationManagers;
use Filament\Tables\Actions\CreateAction; 
use Filament\Tables\Actions\ImportAction; 
use App\Models\Preskripsi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PreskripsiResource extends Resource
{
    protected static ?string $model = Preskripsi::class;
    protected static ?string $navigationLabel = 'Preskripsi';

    protected static ?string $navigationIcon = 'fas-file-prescription';
    protected static ?string $navigationGroup = 'Keperluan Farmasi';
    protected static ?string $modelLabel = 'Preskripsi'; 
    protected static ?string $pluralModelLabel = 'Preskripsi'; 
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_preskripsi')
                    ->label('ID Preskripsi')
                    ->default(function () {
                        $latest = \App\Models\Preskripsi::latest('id_preskripsi')->first();
                        $lastNumber = $latest ? (int) str_replace('PRE', '', $latest->id_preskripsi) : 0;
                        $newId = 'PRE' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
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

                Forms\Components\Select::make('id_kunjungan')
                ->label('Pilih Kunjungan')
                ->relationship('kunjungan', 'id_kunjungan')
                ->searchable()
                ->preload()
                ->required()
                ->native(false),

                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->native(false)
                    ->displayFormat('j F Y') // Format: 23 Mei 2025
                    ->required(),

                Forms\Components\Select::make('dosis')
                ->label('Dosis')
                ->options([
                    '1X Sehari' => '1X Sehari',
                    '2x Sehari' => '2x Sehari',
                    '3X Sehari' => '3X Sehari',
                ])
                ->required(),

                Forms\Components\TextInput::make('jumlah')
                ->label('Jumlah')
                ->required(),

                Forms\Components\TextInput::make('cara_penggunaan')
                ->label('Cara Penggunaan')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_preskripsi')
                ->label('ID Preskripsi')
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('pasien.nama_pasien')
                    ->label('Nama Pasien')
                    ->searchable() // Bisa dicari berdasarkan nama
                    ->sortable() // Bisa di-sort
                    ->tooltip(fn ($record) => 'ID: ' . $record->id_pasien),

                Tables\Columns\TextColumn::make('id_kunjungan')
                    ->label('ID Kunjungan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal ')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->translatedFormat('j F Y'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('dosis')
                    ->label('Dosis')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cara_penggunaan')
                    ->label('Cara Penggunaan')
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
                    ->label('Tambah Preskripsi'),
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
            'index' => Pages\ListPreskripsis::route('/'),
           // 'create' => Pages\CreatePreskripsi::route('/create'),
            'edit' => Pages\EditPreskripsi::route('/{record}/edit'),
        ];
    }
}
