<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanResource\Pages;
use App\Filament\Resources\TagihanResource\RelationManagers;
use Filament\Tables\Actions\CreateAction; 
use Filament\Tables\Actions\ImportAction; 
use App\Models\Tagihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;
    protected static ?string $navigationLabel = 'Tagihan';
    protected static ?string $navigationIcon = 'fas-money-bill';
    protected static ?string $navigationGroup = 'Keperluan Rumah Sakit';
    protected static ?string $modelLabel = 'Tagihan'; 
    protected static ?string $pluralModelLabel = 'Tagihan'; 

    protected static function updateTotalBiaya(Get $get, Set $set): void
    {
        $total = (float) $get('biaya_kunjungan') +
            (float) $get('biaya_pemeriksaan') +
            (float) $get('biaya_obat');

        $set('total_biaya', number_format($total, 2, '.', ''));
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_tagihan')
                    ->label('ID Tagihan')
                    ->default(function () {
                        $latest = \App\Models\Tagihan::latest('id_tagihan')->first();
                        $lastNumber = $latest ? (int) str_replace('TGH', '', $latest->id_tagihan) : 0;
                        $newId = 'TGH' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
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

                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal Tagihan')
                    ->native(false)
                    ->displayFormat('j F Y') // Format: 23 Mei 2025
                    ->required(),

                Forms\Components\TextInput::make('biaya_kunjungan')
                    ->label('Biaya Kunjungan')
                    ->prefix('Rp')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        static::updateTotalBiaya($get, $set);
                    })
                    ->debounce(300),

                Forms\Components\TextInput::make('biaya_pemeriksaan')
                    ->label('Biaya Pemeriksaan')
                    ->prefix('Rp')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        static::updateTotalBiaya($get, $set);
                    })
                    ->debounce(300),

                Forms\Components\TextInput::make('biaya_obat')
                    ->label('Biaya Obat')
                    ->prefix('Rp')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        static::updateTotalBiaya($get, $set);
                    })
                    ->debounce(300),

//                Forms\Components\TextInput::make('total_biaya')
//                    ->label('Total Biaya')
//                    ->prefix('Rp')
//                    ->numeric()
//                    ->dehydrated()
//                    ->default(function (Get $get) {
//                        return floatval($get('biaya_kunjungan')) +
//                            floatval($get('biaya_pemeriksaan')) +
//                            floatval($get('biaya_obat'));
//                    })
//                    ->disabled(),

                Forms\Components\TextInput::make('total_biaya')
                    ->label('Total Biaya')
                    ->prefix('Rp')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\Select::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->options([
                        'Tunai' => 'Tunai',
                        'Kartu Kredit' => 'Kartu Kredit',
                        'Debit' => 'Debit',
                        'QRIS' => 'QRIS',
                        'Transfer Bank' => 'Transfer Bank',
                        'BPJS' => 'BPJS',
                        'Asuransi' => 'Asuransi',
                    ])
                    ->required()
                    ->searchable(),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_tagihan')
                ->label('ID Tagihan')
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('pasien.nama_pasien')
                    ->label('Nama Pasien')
                    ->searchable() // Bisa dicari berdasarkan nama
                    ->sortable() // Bisa di-sort
                    ->tooltip(fn ($record) => 'ID: ' . $record->id_pasien),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->translatedFormat('j F Y'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('biaya_kunjungan')
                ->label('Biaya Kunjungan')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 2, ',', '.'))
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('biaya_pemeriksaan')
                ->label('Biaya Pemeriksaan')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 2, ',', '.'))
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('biaya_obat')
                ->label('Biaya Obat')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 2, ',', '.'))
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('total_biaya')
                    ->label('Total Biaya')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 2, ',', '.'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
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
                    ->label('Tambah Tagihan'),
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
            'index' => Pages\ListTagihans::route('/'),
            //'create' => Pages\CreateTagihan::route('/create'),
            'edit' => Pages\EditTagihan::route('/{record}/edit'),
        ];
    }
}
