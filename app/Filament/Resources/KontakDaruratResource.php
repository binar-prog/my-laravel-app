<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KontakDaruratResource\Pages;
use App\Filament\Resources\KontakDaruratResource\RelationManagers;
use Filament\Tables\Actions\CreateAction; 
use Filament\Tables\Actions\ImportAction; 
use App\Models\KontakDarurat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KontakDaruratResource extends Resource
{
    protected static ?string $model = KontakDarurat::class;
    protected static ?string $navigationLabel = 'Kontak Darurat';
    protected static ?string $navigationIcon = 'fas-address-book';
    protected static ?string $navigationGroup = 'Keperluan Pasien';
    protected static ?string $modelLabel = 'Kontak Darurat'; 
    protected static ?string $pluralModelLabel = 'Kontak Darurat'; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_kontak')
                    ->label('ID Kontak Darurat')
                    ->default(function () {
                        $latest = \App\Models\KontakDarurat::latest('id_kontak')->first();
                        $lastNumber = $latest ? (int) str_replace('KON', '', $latest->id_kontak) : 0;
                        $newId = 'KON' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                        return $newId;
                    })
                    ->disabled()
                    ->dehydrated(true) // tetap dikirim walau disabled
                    ->required()
                    ->maxLength(20),

                Forms\Components\TextInput::make('nama_kontak')
                    ->label('Nama Kontak')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('no_darurat')
                    ->label('No. Darurat')
                    ->required()
                    ->maxLength(15),

                Forms\Components\Select::make('hubungan')
                ->label('Hubungan')
                ->options([
                    'Suami' => 'Suami',
                    'Istri' => 'Istri',
                    'Anak' => 'Anak',
                    'Orang Tua' => 'Orang Tua',
                    'Saudara' => 'Saudara',
                ]),

                Forms\Components\Select::make('id_pasien')
                    ->label('Pilih Pasien')
                    ->relationship('pasien', 'nama_pasien') // Relasi ke model Pasien, kolom nama
                    ->searchable() // 👈 Enable pencarian
                    ->preload() // Load opsi awal (opsional)
                    ->required()
                    ->native(false), // Gunakan UI modern Filament



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_kontak')
                    ->label('ID Kontak')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_kontak')
                    ->label('Nama Kontak')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_darurat')
                    ->label('No. Darurat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hubungan')
                    ->label('Hubungan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pasien.nama_pasien')
                    ->label('Nama Pasien')
                    ->searchable() // Bisa dicari berdasarkan nama
                    ->sortable() // Bisa di-sort
                    ->tooltip(fn ($record) => 'ID: ' . $record->id_pasien) // Tampilkan ID sebagai tooltip
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
                    ->label('Tambah Kontak Darurat'),
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
            'index' => Pages\ListKontakDarurats::route('/'),
           // 'create' => Pages\CreateKontakDarurat::route('/create'),
            'edit' => Pages\EditKontakDarurat::route('/{record}/edit'),
        ];
    }
}
