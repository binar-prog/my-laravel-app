<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerawatResource\Pages;
use App\Filament\Resources\PerawatResource\RelationManagers;
use Filament\Tables\Actions\CreateAction; 
use Filament\Tables\Actions\ImportAction; 
use App\Models\Perawat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerawatResource extends Resource
{
    protected static ?string $model = Perawat::class;
    protected static ?string $navigationLabel = 'Perawat';
    protected static ?string $navigationIcon = 'fas-user-nurse';
    protected static ?string $navigationGroup = 'Keperluan Rumah Sakit';
    protected static ?string $modelLabel = 'Perawat'; 
    protected static ?string $pluralModelLabel = 'Perawat'; 
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_perawat')
                    ->label('ID Perawat')
                    ->default(function () {
                        $latest = \App\Models\Perawat::latest('id_perawat')->first();
                        $lastNumber = $latest ? (int) str_replace('P', '', $latest->id_perawat) : 0;
                        $newId = 'P' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
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
                Forms\Components\TextInput::make('nama_perawat')
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

                Forms\Components\TextInput::make('no_telepon')
                ->required()
                ->maxLength(15),

                Forms\Components\DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->native(false)
                    ->displayFormat('j F Y')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_perawat')
                ->label('ID Perawat')->searchable()
                    ->sortable(),


                Tables\Columns\TextColumn::make('no_kualifikasi')
                ->label('No. Kualifikasi')
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('nama_perawat')
                ->label('Nama Perawat')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                ->label('Jenis Kelamin')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('nama_poli')
                ->label('Nama Poli')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('no_telepon')
                ->label('No. Telepon')
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_masuk')
                ->label('Tanggal Masuk')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->translatedFormat('j F Y'))
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
                    ->label('Tambah Perawat'),
            ]);;
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
            'index' => Pages\ListPerawats::route('/'),
            //'create' => Pages\CreatePerawat::route('/create'),
            'edit' => Pages\EditPerawat::route('/{record}/edit'),
        ];
    }
}
