<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersByAge extends Model
{
    use HasFactory;

    protected $fillable = [
        'year', 'month', 'age_group_0_1', 'age_group_1_4',
        'age_group_5_14', 'age_group_15_44', 'age_group_45_59',
        'age_group_60_plus'
    ];

    public function scopeByPeriod($query, $year, $month = null)
    {
        $query->where('year', $year);
        if ($month) {
            $query->where('month', $month);
        }
        return $query;
    }
};