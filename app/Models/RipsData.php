<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RipsData extends Model
{
    use HasFactory;

    protected $fillable = [
        'year', 'month', 'regimen', 'facturado',
        'consultas_especializada', 'interconsultas_hospitalaria',
        'urgencias_general', 'urgencias_especialista',
        'egresos_hospitalarios', 'imagenologia', 'laboratorio',
        'partos', 'cesareas', 'cirugias', 'terapia_fisica',
        'terapia_respiratoria', 'observaciones'
    ];

    protected $casts = [
        'facturado' => 'decimal:2',
    ];

    public function scopeByPeriod($query, $year, $month = null)
    {
        $query->where('year', $year);
        if ($month) {
            $query->where('month', $month);
        }
        return $query;
    }

    public function scopeByTrimester($query, $year, $trimester)
    {
        $months = [
            1 => [1, 2, 3],
            2 => [4, 5, 6],
            3 => [7, 8, 9],
            4 => [10, 11, 12]
        ];

        return $query->where('year', $year)
                    ->whereIn('month', $months[$trimester]);
    }
};