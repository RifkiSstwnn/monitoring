<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatDivision extends BaseWidget
{
    protected static ?int $sort = 3;    
    public function getDescription(): ?string
    {
        return 'STATS DIVISI.';
    }

    protected function getStats(): array
    {

        $highest_uptime_div = DB::table('daily_uptimes')
            ->join('laptops', 'daily_uptimes.laptop_sn', '=', 'laptops.sn')
            ->select('laptops.DIVISI', DB::raw('SUM(daily_uptimes.uptime) as total_uptime'), DB::raw('COUNT(DISTINCT daily_uptimes.laptop_sn) as count'))
            ->whereNotNull('laptops.DIVISI')
            ->groupBy('laptops.DIVISI')
            ->orderBy('total_uptime', 'desc')
            ->first();
        
        $lowest_uptime_div = DB::table('daily_uptimes')
            ->join('laptops', 'daily_uptimes.laptop_sn', '=', 'laptops.sn')
            ->select('laptops.DIVISI', DB::raw('SUM(daily_uptimes.uptime) as total_uptime'), DB::raw('COUNT(DISTINCT daily_uptimes.laptop_sn) as count'))
            ->whereNotNull('laptops.DIVISI')
            ->groupBy('laptops.DIVISI')
            ->orderBy('total_uptime', 'asc')
            ->first();
            
        $highest_idle_div = DB::table('daily_uptimes')
            ->join('laptops', 'daily_uptimes.laptop_sn', '=', 'laptops.sn')
            ->select('laptops.DIVISI', DB::raw('SUM(daily_uptimes.idle_time) as total_idle_time'), DB::raw('COUNT(DISTINCT daily_uptimes.laptop_sn) as count'))
            ->whereNotNull('laptops.DIVISI')
            ->groupBy('laptops.DIVISI')
            ->orderBy('total_idle_time', 'desc')
            ->first();
            
        $lowest_idle_div = DB::table('daily_uptimes')
            ->join('laptops', 'daily_uptimes.laptop_sn', '=', 'laptops.sn')
            ->select('laptops.DIVISI', DB::raw('SUM(daily_uptimes.idle_time) as total_idle_time'), DB::raw('COUNT(DISTINCT daily_uptimes.laptop_sn) as count'))
            ->whereNotNull('laptops.DIVISI')
            ->groupBy('laptops.DIVISI')
            ->orderBy('total_idle_time', 'asc')
            ->first();

        return [
            Stat::make('Uptime Tertinggi', $highest_uptime_div->DIVISI)
                ->description('Divisi dengan Total Uptime Tertinggi')
                ->icon('heroicon-m-arrow-up')
                ->color('success'),

            Stat::make('Uptime Terendah', $lowest_uptime_div->DIVISI)
                ->description('Divisi dengan Total Uptime Terendah')
                ->icon('heroicon-m-arrow-down')
                ->color('danger'),

            Stat::make('Idle Tertinggi', $highest_idle_div->DIVISI)
                ->description('Divisi dengan Total Idle Tertinggi')
                ->icon('heroicon-m-arrow-up')
                ->color('success'),

            Stat::make('Idle Terendah', $lowest_idle_div->DIVISI)
                ->description('Divisi dengan Total Idle Terendah')
                ->icon('heroicon-m-arrow-down')
                ->color('danger'),
        ];
    }

    protected function getColumns(): int
    {
        return 2;
    }
}
