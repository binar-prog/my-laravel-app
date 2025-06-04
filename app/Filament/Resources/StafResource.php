<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StafResource\Pages;
use App\Filament\Resources\StafResource\RelationManagers;
use Filament\Tables\Actions\CreateAction; 
use Filament\Tables\Actions\ImportAction; 
use App\Models\Staf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StafResource extends Resource
{
    protected static ?string $model = Staf::class;
    protected static ?string $navigationLabel = 'Staf';
    protected static ?string $navigationIcon = 'fas-users';
    protected static ?string $navigationGroup = 'Keperluan Rumah Sakit';
    protected static ?string $modelLabel = 'Staf'; 
    protected static ?string $pluralModelLabel = 'Staf'; 
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_staf')
                    ->label('ID Staf')
                    ->default(function () {
                        $latest = \App\Models\Staf::latest('id_staf')->first();
                        $lastNumber = $latest ? (int) str_replace('S', '', $latest->id_staf) : 0;
                        $newId = 'S' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                        return $newId;
                    })
                    ->disabled()
                    ->dehydrated(true) // tetap dikirim walau disabled
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('nama_staf')
                    ->label('Nama Staf')
                    ->required(),
                Forms\Components\Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P  ' => 'Perempuan'
                    ])
                    ->required(),

                Forms\Components\Select::make('nama_poli')
                    ->label('Poli Tugas')
                    ->relationship('poli', 'nama_poli')
                    ->required()
                    ->native(false)
                    ->preload(),

                Forms\Components\Select::make('jabatan')
                    ->label('Jabatan')
                    ->options([
                        'Admin' => 'Admin',
                        'Laboran' => 'Laboran'
                    ])
                    ->required(),
                Forms\Components\Select::make('unit_kerja')
                    ->label('Unit Kerja')
                    ->options([
                        'Poli' => 'Poli',
                        'Lab' =>   'Lab',
                        'Apotek' => 'Apotek'
                    ])
                    ->required(),
                Forms\Components\TextInput::make('no_telepon')
                    ->label('No. Telepon')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->native(false)
                    ->displayFormat('j F Y') // Format: 23 Mei 2025
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_staf')
                    ->label('ID Staf')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_staf')
                    ->label('Nama Staf')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_poli')
                    ->label('Nama Poli')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit_kerja')
                    ->label('Unit Kerja')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telepon')
                    ->label('No. Telepon')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tangagl_masuk')
                    ->label('Tangagl Masuk')
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
                    ->label('Tambah Staf'),
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
            'index' => Pages\ListStafs::route('/'),
            //'create' => Pages\CreateStaf::route('/create'),
            'edit' => Pages\EditStaf::route('/{record}/edit'),
        ];
    }
}
