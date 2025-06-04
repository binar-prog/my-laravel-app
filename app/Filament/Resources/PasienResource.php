<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PasienResource\Pages;
use App\Filament\Resources\PasienResource\RelationManagers;
use Filament\Tables\Actions\CreateAction; 
use Filament\Tables\Actions\ImportAction; 
use App\Models\Pasien;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PasienResource extends Resource
{
    protected static ?string $model = Pasien::class;
    protected static ?string $navigationLabel = 'Pasien';
    protected static ?string $navigationIcon = 'fas-user-injured';
    protected static ?string $navigationGroup = 'Keperluan Pasien';
    protected static ?string $modelLabel = 'Pasien'; 
    protected static ?string $pluralModelLabel = 'Pasien'; 
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_pasien')
                    ->label('ID Pasien')
                    ->default(function () {
                        $latest = \App\Models\Pasien::latest('id_pasien')->first();
                        $lastNumber = $latest ? (int) str_replace('PAS', '', $latest->id_pasien) : 0;
                        $newId = 'PAS' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                        return $newId;
                    })
                    ->disabled()
                    ->dehydrated(true) // tetap dikirim walau disabled
                    ->required()
                    ->maxLength(20),

                Forms\Components\TextInput::make('nik')
                    ->required()
                    ->maxLength(16),

                Forms\Components\TextInput::make('nama_pasien')
                    ->required()
                    ->maxLength(100),

                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->native(false)
                    ->displayFormat('j F Y') // Format: 23 Mei 2025
                    ->required(),

                Forms\Components\Select::make('jenis_kelamin')
                    ->required()
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),

                Forms\Components\Textarea::make('alamat')
                    ->required(),

                Forms\Components\TextInput::make('no_telepon')
                    ->required()
                    ->maxLength(15),

                Forms\Components\Select::make('status_asuransi')
                    ->label('Status Asuransi')
                    ->required()
                    ->options([
                        'BPJS' => 'BPJS',
                        'Asuransi Swasta' => 'Asuransi Swasta',
                        'Umum' => 'Umum',
                    ])
                    ->native(false) // biar tampilannya lebih modern (optional)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_pasien')->label('ID Pasien'),
                Tables\Columns\TextColumn::make('nik')->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_pasien')->label('Nama')->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->translatedFormat('j F Y')),

                Tables\Columns\TextColumn::make('jenis_kelamin')->label('JK'),
                Tables\Columns\TextColumn::make('status_asuransi')->label('Asuransi'),
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
                    ->label('Tambah Pasien'),
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
            'index' => Pages\ListPasiens::route('/'),
           // 'create' => Pages\CreatePasien::route('/create'),
            'edit' => Pages\EditPasien::route('/{record}/edit'),
        ];
    }
}
