<?php

namespace App\Filament\Widgets;

use App\Models\Kunjungan;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class KunjunganHariIni extends BaseWidget
{
    protected static ?string $heading = 'Kunjungan Pasien Hari Ini';
    protected static ?int $sort = 2;

    protected function getTableQuery(): Builder
    {
        return Kunjungan::query()
            ->whereDate('tanggal_jam', now())
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('pasien.nama_pasien')
                ->label('Nama Pasien')
                ->searchable()
                ->sortable() // Tambahkan sortable
                ->weight('medium') // Sedikit lebih tebal
                ->color('primary') // Warna teks primary (biru default Filament)
                ->wrap(), // Memastikan teks panjang tidak terpotong

            Tables\Columns\TextColumn::make('dokter.nama_dokter')
                ->label('Dokter')
                ->searchable()
                ->sortable()
                ->icon('heroicon-o-user-circle') // Tambahkan ikon
                ->color('info') // Warna teks info
                ->wrap(),

            Tables\Columns\TextColumn::make('tanggal_jam')
                ->label('Jam')
                ->dateTime('H:i')
                ->sortable()
                ->color('secondary') // Warna teks sekunder
                ->icon('heroicon-o-clock'), // Ikon jam

            Tables\Columns\TextColumn::make('nama_poli')
                ->label('Poli')
                ->searchable()
                ->sortable()
                ->badge() // Tampilkan sebagai badge
                ->color(function (string $state) {
                    // Contoh logika warna berdasarkan nama poli
                    if (str_contains(strtolower($state), 'gigi')) {
                        return 'warning'; // Misalnya poli gigi warna kuning
                    }
                    if (str_contains(strtolower($state), 'umum')) {
                        return 'success'; // Misalnya poli umum warna hijau
                    }
                    return 'info'; // Default
                }),
        ];
    }

    // Opsi tambahan untuk estetika dan fungsionalitas
    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [5, 10, 25, 50]; // Pilihan jumlah baris per halaman
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'Tidak ada kunjungan hari ini';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Cek kembali besok atau pastikan tanggal sudah sesuai.';
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-clipboard-document-list';
    }
}