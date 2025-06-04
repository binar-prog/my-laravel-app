<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KunjunganResource\Pages;
use App\Filament\Resources\KunjunganResource\RelationManagers;
use Filament\Tables\Actions\CreateAction; 
use Filament\Tables\Actions\ImportAction; 
use App\Models\Kunjungan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KunjunganResource extends Resource
{
    protected static ?string $model = Kunjungan::class;
    protected static ?string $navigationLabel = 'Kunjungan';
    protected static ?string $navigationIcon = 'fas-calendar-check';
    protected static ?string $navigationGroup = 'Keperluan Pasien';
    protected static ?string $modelLabel = 'Kunjungan'; 
    protected static ?string $pluralModelLabel = 'Kunjungan'; 
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_kunjungan')
                    ->label('ID Kunjungan')
                    ->default(function () {
                        $latest = \App\Models\Kunjungan::latest('id_kunjungan')->first();
                        $lastNumber = $latest ? (int) str_replace('KUN', '', $latest->id_kunjungan) : 0;
                        $newId = 'KUN' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
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
                    ->native(false), // Gunakan UI modern Filament

                Forms\Components\Select::make('id_dokter')
                    ->label('Pilih Dokter')
                    ->relationship('dokter', 'nama_dokter') // Relasi ke model Pasien, kolom nama
                    ->searchable() // 👈 Enable pencarian
                    ->preload() // Load opsi awal (opsional)
                    ->required()
                    ->native(false), // Gunakan UI modern Filament

                Forms\Components\Select::make('nama_poli')
                    ->label('Poli Tugas')
                    ->relationship('poli', 'nama_poli')
                    ->required()
                    ->native(false)
                    ->preload(),

                Forms\Components\DateTimePicker::make('tanggal_jam')
                    ->label('Tanggal & Jam Kunjungan')
                    ->displayFormat('j F Y H:i')
                    ->seconds(false) // Sembunyikan detik
                    ->minutesStep(15) // Kelipatan 15 menit
                    ->required()
                    ->columnSpan(1)
                    ->required()
                    ->native(false),

                Forms\Components\TextInput::make('diagnosis')
                    ->label('Diagnosis')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_kunjungan')
                ->label('ID Kunjungan')
                ->sortable()
                ->searchable(),

                Tables\Columns\TextColumn::make('pasien.nama_pasien')
                    ->label('Nama Pasien')
                    ->searchable() // Bisa dicari berdasarkan nama
                    ->sortable() // Bisa di-sort
                    ->tooltip(fn ($record) => 'ID: ' . $record->id_pasien), // Tampilkan ID sebagai tooltip

                Tables\Columns\TextColumn::make('dokter.nama_dokter')
                    ->label('Nama Dokter')
                    ->searchable() // Bisa dicari berdasarkan nama
                    ->sortable() // Bisa di-sort
                    ->tooltip(fn ($record) => 'ID: ' . $record->id_dokter), // Tampilkan ID sebagai tooltip

                Tables\Columns\TextColumn::make('nama_poli')
                    ->label('Poli Kunjungan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_jam')
                    ->label('Tanggal & Jam Kunjungan')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->translatedFormat('j F Y H:i')
                    )
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('diagnosis')
                ->label('Diagnosis')
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
                    ->label('Tambah Kunjungan'),
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
            'index' => Pages\ListKunjungans::route('/'),
           // 'create' => Pages\CreateKunjungan::route('/create'),
            'edit' => Pages\EditKunjungan::route('/{record}/edit'),
        ];
    }
}
