<?php

namespace App\Filament\Widgets;

use App\Models\Kunjungan;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class DokterStatsOverview2 extends ChartWidget
{
    protected static ?string $heading = 'Grafik Jumlah Kunjungan Pasien per Hari';

    // Gantikan properti $chart dengan method getType()
    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        return $this->getKunjunganPerHari();
    }

    protected function getKunjunganPerHari(): array
    {
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('D, d M');

            $count = Kunjungan::whereDate('tanggal_jam', $date)->count();
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Kunjungan',
                    'data' => $data,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.4,
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Hari',
                    ],
                ],
            ],
        ];
    }
}
