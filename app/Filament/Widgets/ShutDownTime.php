<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ShutDownTime extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Shut down Time per Division';
    protected static ?string $maxHeight = '200px';
    protected static ?string $pollingInterval = null;
    protected int | string | array $columnSpan = '1';

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
            DB::raw('COUNT(daily_uptimes.time) as time_count')
            )
            ->groupBy('laptops.DIVISI')
            ->get();

        $labels = [];
        $data = [];

        foreach ($stats as $stat) {
            if($stat->label != null)
            {
                $labels[] = $stat->label;
                $data[] = $stat->time_count;
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Shutdown Time',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }
}