<?php

namespace Database\Seeders;

use App\Models\DailyUptime;
use App\Models\Laptop;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DailyUptimeSeeder extends Seeder
{
    public function run()
    {
        Laptop::all()->each(function (Laptop $laptop) {
            $start_date = Carbon::today()->subDays(rand(30, 60));
            $days = rand(5, 20); // 5-20 hari data

            for ($i = 0; $i < $days; $i++) {
                $date = $start_date->copy()->addDays($i);
                $time = sprintf('%02d:%02d:%02d', rand(0, 23), rand(0, 59), rand(0, 59));
                
                DailyUptime::create([
                    'laptop_sn' => $laptop->SN,
                    'date' => $date,
                    'time' => $time,
                    'uptime' => rand(14400, 43200), // 4-12 jam
                    'idle_time' => rand(0, 21600), // 0-6 jam
                ]);
            }
        });
    }
}
