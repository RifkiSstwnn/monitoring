<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use App\Models\DailyUptime;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;    

    protected function getStats(): array
    {
        // Query untuk total uptime per divisi
        $uptimeStats = DB::table('daily_uptimes')
            ->join('laptops', 'daily_uptimes.laptop_sn', '=', 'laptops.sn')
            ->select(
                'laptops.DIVISI',
                DB::raw('SUM(daily_uptimes.uptime) as total_uptime'),
                DB::raw('SUM(daily_uptimes.idle_time) as total_idle_time'),
                DB::raw('COUNT(DISTINCT daily_uptimes.laptop_sn) as count')
            )
            ->whereNotNull('laptops.DIVISI')
            ->groupBy('laptops.DIVISI')
            ->get(); // Menggunakan get() untuk mendapatkan semua divisi

        // Menghitung total uptime dari semua divisi
        $total_uptime_seconds = $uptimeStats->sum('total_uptime');
        $sum_idle = $uptimeStats->sum('total_idle_time');
        $total_laptop= $uptimeStats->sum('count');
        // Rata-rata uptime dari setiap laptop berdasarkan record terbaru (date & time terbaru)
        $uptime_day_avg = DB::table('daily_uptimes as d1')
            ->whereRaw('(d1.date, d1.time) = (
                SELECT d2.date, d2.time FROM daily_uptimes as d2 
                WHERE d2.laptop_sn = d1.laptop_sn 
                ORDER BY d2.date DESC, d2.time DESC LIMIT 1
            )')
            ->selectRaw('AVG(d1.uptime) as avg_uptime')
            ->first();

        // Rata-rata idle time dari setiap laptop berdasarkan record terbaru (date & time terbaru)
        $idle_day_avg = DB::table('daily_uptimes as d1')
            ->whereRaw('(d1.date, d1.time) = (
                SELECT d2.date, d2.time FROM daily_uptimes as d2 
                WHERE d2.laptop_sn = d1.laptop_sn 
                ORDER BY d2.date DESC, d2.time DESC LIMIT 1
            )')
            ->selectRaw('AVG(d1.idle_time) as avg_idle_time')
            ->first();


        // Menghindari pembagian dengan nol
        $avg_uptime = ($total_uptime_seconds / $total_laptop);
        // dd($total_uptime_seconds);
        $avg_idle = ($sum_idle / $total_laptop);

        // Konversi waktu dari detik ke jam & menit
        $formatted_uptime = $this->convertToHours($total_uptime_seconds);
        $formatted_avg = $this->convertToHours($avg_uptime);
        $formatted_avg_day = $this->convertToHours($uptime_day_avg->avg_uptime ?? 0);
        $idle_avg_day = $this->convertToHours($idle_day_avg->avg_idle_time ?? 0);
        $sum_idle_time = $this->convertToHours($sum_idle ?? 0);
        $formatted_avg_idle = $this->convertToHours($avg_idle);

        return [
            Stat::make('Total Uptime', $formatted_uptime)
                ->description('Total Seluruh Waktu Aktif Laptop')
                ->icon('heroicon-m-arrow-trending-up'),

            Stat::make('Rata - rata Uptime', $formatted_avg)
                ->description('Rata-rata Waktu Uptime Laptop')
                ->icon('heroicon-m-arrow-trending-down'),
            
            Stat::make('Rata - rata Uptime per Hari', $formatted_avg_day)
                ->description('Rata-rata Waktu Uptime per Hari (Semua Laptop)')
                ->icon('heroicon-m-arrow-trending-down'),

            Stat::make('Total Idle Time', $sum_idle_time)
                ->description('Total Seluruh Waktu Idle Laptop')
                ->icon('heroicon-m-arrow-trending-up'),

            Stat::make('Rata - rata Idle Time', $formatted_avg_idle)
                ->description('Rata-rata Waktu Idle Time Laptop')
                ->icon('heroicon-m-arrow-trending-down'),
            
            Stat::make('Rata - rata Idle Time per Hari', $idle_avg_day)
                ->description('Rata-rata Waktu Idle Time per Hari (Semua Laptop)')
                ->icon('heroicon-m-arrow-trending-down'),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }

    private function convertToHours($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        return sprintf('%d H : %d M', $hours, $minutes);
    }
}
