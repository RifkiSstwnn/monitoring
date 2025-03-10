<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyUptime extends Model
{
    use HasFactory;
    protected $fillable = [
        'laptop_sn',
        'date',
        'time',
        'uptime',
        'idle_time',
    ];

// DailyUptime Model
public function laptop()
{
    return $this->belongsTo(Laptop::class, 'laptop_sn', 'SN');
}

}
