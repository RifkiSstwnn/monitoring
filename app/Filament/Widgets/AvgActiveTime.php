<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AvgActiveTime extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Average Start Time per Division';
    protected static ?string $maxHeight = '200px';
    protected static ?string $pollingInterval = null;
    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $stats = DB::table('daily_uptimes')
            ->join('laptops', 'daily_uptimes.laptop_sn', '=', 'laptops.sn')
            ->whereNotNull('laptops.DIVISI')
            ->select(
                'laptops.DIVISI as label',
                DB::raw('AVG(TIME_TO_SEC(daily_uptimes.time)) as avg_seconds')
            )
            ->groupBy('laptops.DIVISI')
            ->get();

        $labels = [];
        $data = [];

        foreach ($stats as $stat) {
            $hours = floor($stat->avg_seconds / 3600);
            $minutes = floor(($stat->avg_seconds % 3600) / 60);
            $labels[] = "{$stat->label}, [{$hours}: {$minutes}]";
            $data[] = round($stat->avg_seconds / 3600, 2);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Average Start Time (hours)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

}