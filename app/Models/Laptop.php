<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Laptop extends Model
{
    use HasFactory;
    protected $primaryKey = 'SN';

    protected $fillable = [
        'OWNER', 'PHASE', 'SITE', 'COMP NAME', 'COMP NAME REV', 'SN', 'TYPE UNIT', 'CLASSIFICATION UNIT', 'CATEGORY UNIT', 'OS', 'USER NAME', 'NRP', 'DIVISI'
    ];
    
    protected $casts = [
        'SN' => 'string',
    ];

    public function dailyUptimes()
    {
        return $this->hasMany(DailyUptime::class, 'laptop_sn', 'SN');
    }
    
}
