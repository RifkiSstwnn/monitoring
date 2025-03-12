<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use function floor;
use function round;

class BlogPostsChart extends ChartWidget
{
    protected static ?int $sort = 1;
    protected static ?string $heading = 'Total Uptime per Division';
    
    protected function getFilters(): array
    {
        return [
            'total' => 'Total Uptime',
            'average' => 'Average Uptime',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter ?? 'total'; // Default filter ke 'total'

        $stats = DB::table('daily_uptimes')
        ->join('laptops', 'daily_uptimes.laptop_sn', '=', 'laptops.sn')
        ->select('laptops.DIVISI', DB::raw('SUM(daily_uptimes.uptime) as total_uptime'), DB::raw('COUNT(DISTINCT daily_uptimes.laptop_sn) as count'))
        ->whereNotNull('laptops.DIVISI')
        ->groupBy('laptops.DIVISI')
        ->get();
    
        $labels = [];
        $values = [];

        foreach ($stats as $stat) {
            if($stat->DIVISI != null)
            {
                $total_seconds = $stat->total_uptime;
                $hours = floor($total_seconds / 3600);
                $minutes = floor(($total_seconds % 3600) / 60);
                
                // $labels[] = "{$stat->DIVISI}, {$hours}h {$minutes}m";
                $labels[] = "{$stat->DIVISI}";
                
                if ($filter === 'total') {
                    $values[] = round($total_seconds / 3600, 2);
                } else {
                    $values[] = round($total_seconds / 3600 / $stat->count, 2);
                }
            }

        }

        return [
            'datasets' => [
                [
                    'label' => $filter === 'total' ? 'Total Uptime (hours)' : 'Average Uptime (hours)',
                    'data' => $values,
                    'backgroundColor' => [
                        $filter === 'total' ? 'rgba(255, 99, 132, 0.5)' : 'rgba(54, 162, 235, 0.5)',
                    ],
                    'borderColor' => 'rgba(0, 0, 0, 0.1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bisa diganti 'pie', 'line', dll.
    }
}