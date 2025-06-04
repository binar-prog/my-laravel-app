<?php

// SESUAIKAN NAMESPACE INI DENGAN LOKASI FILE ANDA
// Contoh jika di app/Filament/Widgets/
namespace App\Filament\Widgets;

// Contoh jika di app/Filament/Resources/DokterResource/Widgets/
// namespace App\Filament\Resources\DokterResource\Widgets;

use App\Models\Dokter; // Model ini pasti sudah ada
use App\Models\Pasien; // Asumsi: App\Models\Pasien
use App\Models\Obat;   // Asumsi: App\Models\Obat
use App\Models\Tagihan; // Asumsi: App\Models\Tagihan
use Carbon\Carbon;      // Untuk bekerja dengan tanggal
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DokterStatsOverview extends BaseWidget
{
    // Ini akan membuat widget ditampilkan dalam 4 kolom.
    protected static ?int $columns = 4;

    protected function getStats(): array
    {
        // 1. Total Dokter Aktif
        // Asumsi semua data di tabel Dokter adalah dokter aktif.
        $totalDokterAktif = Dokter::count();

        // 2. Pasien Rawat Inap Hari Ini
        // Berdasarkan kolom yang ada, kita asumsikan ini adalah
        // pasien yang terdaftar (dibuat) hari ini.
        // Jika ada kolom lain yang mengindikasikan rawat inap (misal 'is_rawat_inap' boolean),
        // gunakan itu. Untuk saat ini, kita pakai 'created_at'.
        $pasienRawatInapHariIni = Pasien::whereDate('created_at', Carbon::today())->count();
        // CATATAN: Jika Anda memiliki logika rawat inap yang lebih spesifik (misal, pasien masuk tapi belum keluar),
        // Anda perlu menambahkan kolom di tabel pasien/rawat_inap untuk status tersebut.

        // 3. Jumlah Obat Hampir Habis
        // Menggunakan ambang batas tetap (misal, stok <= 10) karena tidak ada 'stok_minimum'.
        $jumlahObatHampirHabis = Obat::where('stok', '<=', 10)->count();
        // CATATAN: Sesuaikan angka 10 dengan ambang batas "hampir habis" yang Anda inginkan.

        // 4. Total Tagihan Hari Ini
        // Menggunakan kolom 'Tanggal' dan 'Total Biaya' yang Anda sebutkan.
        $totalTagihanHariIni = Tagihan::whereDate('tanggal', Carbon::today())->sum('total_biaya');
        $formattedTotalTagihan = 'Rp ' . number_format($totalTagihanHariIni, 0, ',', '.');


        return [
            Stat::make('Total Dokter Aktif', $totalDokterAktif)
                ->description('Dokter yang tersedia')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Pasien Terdaftar Hari Ini', $pasienRawatInapHariIni) // Ubah label sesuai asumsi baru
                ->description('Pasien yang terdaftar hari ini')
                ->descriptionIcon('heroicon-m-user-plus') // Icon yang lebih sesuai
                ->color('info'),

            Stat::make('Obat Hampir Habis', $jumlahObatHampirHabis)
                ->description('Stok di bawah batas aman')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),

            Stat::make('Total Tagihan Hari Ini', $formattedTotalTagihan)
                ->description('Pendapatan kotor hari ini')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),
        ];
    }
}