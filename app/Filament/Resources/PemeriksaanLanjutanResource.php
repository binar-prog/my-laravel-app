<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemeriksaanLanjutanResource\Pages;
use App\Filament\Resources\PemeriksaanLanjutanResource\RelationManagers;
use Filament\Tables\Actions\CreateAction; 
use Filament\Tables\Actions\ImportAction; 
use App\Models\PemeriksaanLanjutan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PemeriksaanLanjutanResource extends Resource
{
    protected static ?string $model = PemeriksaanLanjutan::class;
    protected static ?string $navigationLabel = 'Pemeriksaan Lanjutan';
    protected static ?string $navigationIcon = 'fas-search-plus';
    protected static ?string $navigationGroup = 'Keperluan Pasien';
    protected static ?string $modelLabel = 'Pemeriksaan Lanjutan'; 
    protected static ?string $pluralModelLabel = 'Pemeriksaan Lanjutan'; 
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_pemeriksaan')
                    ->label('ID Pemeriksaan Lanjutan')
                    ->default(function () {
                        $latest = \App\Models\PemeriksaanLanjutan::latest('id_pemeriksaan')->first();
                        $lastNumber = $latest ? (int) str_replace('PEM-', '', $latest->id_pemeriksaan) : 0;
                        $newId = 'PEM-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
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

                Forms\Components\Select::make('id_dokter')
                    ->label('Pilih Dokter')
                    ->relationship('dokter', 'nama_dokter') // Relasi ke model Pasien, kolom nama
                    ->searchable() // 👈 Enable pencarian
                    ->preload() // Load opsi awal (opsional)
                    ->required()
                    ->native(false),

                Forms\Components\DatePicker::make('tanggal_pemeriksaan')
                    ->label('Tanggal Pemeriksaan')
                    ->native(false)
                    ->displayFormat('j F Y') // Format: 23 Mei 2025
                    ->required(),

                Forms\Components\Select::make('jenis_pemeriksaan')
                    ->label('Jenis Pemeriksaan')
                    ->options([
                        'MRI' => 'MRI',
                        'Tes Darah' => 'Tes Darah',
                        'CT Scan' => 'CT Scan',
                        'Rontgen' => 'Rontgen',
                        'USG' => 'USG',
                        'EKG' => 'EKG',
                        'Endoskopi' => 'Endoskopi',
                        'Tes Urin' => 'Tes Urin',
                        'Biopsi' => 'Biopsi',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('hasil_pemeriksaan')
                    ->label('Hasil Pemeriksaan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_pemeriksaan')
                ->label('ID Pemeriksaan')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('pasien.nama_pasien')
                    ->label('Nama Pasien')
                    ->searchable() // Bisa dicari berdasarkan nama
                    ->sortable() // Bisa di-sort
                    ->tooltip(fn ($record) => 'ID: ' . $record->id_pasien),

                Tables\Columns\TextColumn::make('dokter.nama_dokter')
                    ->label('Nama Dokter')
                    ->searchable() // Bisa dicari berdasarkan nama
                    ->sortable() // Bisa di-sort
                    ->tooltip(fn ($record) => 'ID: ' . $record->id_dokter),

                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->translatedFormat('j F Y'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('hasil_pemeriksaan')
                    ->label('Hasil Pemeriksaan')
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
            'index' => Pages\ListPemeriksaanLanjutans::route('/'),
            //'create' => Pages\CreatePemeriksaanLanjutan::route('/create'),
            'edit' => Pages\EditPemeriksaanLanjutan::route('/{record}/edit'),
        ];
    }
}
