<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokterResource\Pages;
use App\Filament\Resources\DokterResource\RelationManagers;
use Filament\Tables\Actions\CreateAction; 
use Filament\Tables\Actions\ImportAction; 
use App\Models\Dokter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DokterResource extends Resource
{
    protected static ?string $model = Dokter::class;
    protected static ?string $navigationLabel = 'Dokter';
    protected static ?string $navigationIcon = "fas-user-doctor";
    protected static ?string $navigationGroup = 'Keperluan Rumah Sakit';
    protected static ?string $modelLabel = 'Dokter'; // Untuk singular: "Dokter"
    protected static ?string $pluralModelLabel = 'Dokter'; // Untuk plural: "Dokter" (jika Anda ingin tetap "Dokter" saja)
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_dokter')
                    ->label('ID Dokter')
                    ->default(function () {
                        $latest = \App\Models\Dokter::latest('id_dokter')->first();
                        $lastNumber = $latest ? (int) str_replace('D', '', $latest->id_dokter) : 0;
                        $newId = 'D' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                        return $newId;
                    })
                    ->disabled()
                    ->dehydrated(true) // tetap dikirim walau disabled
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('no_kualifikasi')
                    ->label('No. Kualifikasi')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('nama_dokter')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('jenis_kelamin')
                    ->required()
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),

                Forms\Components\Select::make('nama_poli')
                    ->label('Poli Tugas')
                    ->relationship('poli', 'nama_poli')
                    ->required()
                    ->native(false)
                    ->preload(),

                Forms\Components\TextInput::make('spesialisasi')
                    ->label('Spesialisasi')
                    ->required()
                    ->maxLength(50),

                Forms\Components\TextInput::make('no_telepon')
                    ->label('No. Telepon')
                    ->required()
                    ->maxLength(15),

                Forms\Components\DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->native(false)
                    ->displayFormat('j F Y') // Format: 23 Mei 2025
                    ->required(),

                Forms\Components\Select::make('hari_praktik')
                    ->label('Hari Praktik')
                    ->options([
                        'senin' => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu' => 'Rabu',
                        'kamis' => 'Kamis',
                        'jumat' => 'Jumat',
                        'sabtu' => 'Sabtu',
                        'minggu' => 'Minggu',
                    ])
                    ->required(),

                Forms\Components\TimePicker::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->required()
                    ->seconds(false) // Sembunyikan detik
                    ->minutesStep(15) // Interval 15 menit
                    ->displayFormat('H:i') // Format tampilan: 14:30
                    ->native(false), // Gunakan UI Filament (bukan browser default)

                Forms\Components\TimePicker::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->required()
                    ->seconds(false)
                    ->minutesStep(15)
                    ->displayFormat('H:i')
                    ->native(false)
                    ->rules([
                        'after:jam_mulai' // Validasi: harus setelah jam_mulai
                    ]),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_dokter')
                ->label('ID Dokter')
                ->sortable(),
                Tables\Columns\TextColumn::make('no_kualifikasi')
                ->label('No. Kualifikasi')
                ->sortable(),
                Tables\Columns\TextColumn::make('nama_dokter')
                ->label('Nama Dokter')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                ->label('Jenis Kelamin')
                ->sortable(),
                Tables\Columns\TextColumn::make('nama_poli')
                    ->label('Poli Tugas')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('spesialisasi')
                    ->label('Spesialisasi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telepon')
                    ->label('No. Telepon')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->translatedFormat('j F Y'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('hari_praktik')
                    ->label('Hari Praktik')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jam_selesai')
                    ->label('Jam Selesai')
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
                    ->label('Tambah Dokter'),
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
            'index' => Pages\ListDokters::route('/'),
            //'create' => Pages\CreateDokter::route('/create'),
            'edit' => Pages\EditDokter::route('/{record}/edit'),
        ];
    }
}
