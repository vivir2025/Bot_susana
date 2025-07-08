<?php

namespace App\Http\Controllers;

use App\Models\RipsData;
use App\Models\UsersByAge;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        $facturacionData = RipsData::byPeriod($currentYear, $currentMonth)
            ->selectRaw('regimen, SUM(facturado) as total')
            ->groupBy('regimen')
            ->get();

        $comparisonData = $this->getComparisonData($currentYear, $currentMonth);
        
        return view('dashboard.index', compact('facturacionData', 'comparisonData'));
    }

    public function compare(Request $request)
    {
        $type = $request->input('type'); // 'month', 'quarter', 'year'
        $period1 = $request->input('period1');
        $period2 = $request->input('period2');
        
        $data = $this->getComparisonByType($type, $period1, $period2);
        
        return response()->json($data);
    }

    private function getComparisonData($year, $month)
    {
        $currentPeriod = RipsData::byPeriod($year, $month)->sum('facturado');
        $previousPeriod = RipsData::byPeriod($year, $month - 1)->sum('facturado');
        $previousYear = RipsData::byPeriod($year - 1, $month)->sum('facturado');
        
        return [
            'current' => $currentPeriod,
            'previous_month' => $previousPeriod,
            'previous_year' => $previousYear,
            'growth_month' => $previousPeriod > 0 ? (($currentPeriod - $previousPeriod) / $previousPeriod) * 100 : 0,
            'growth_year' => $previousYear > 0 ? (($currentPeriod - $previousYear) / $previousYear) * 100 : 0,
        ];
    }

    private function getComparisonByType($type, $period1, $period2)
    {
        switch ($type) {
            case 'month':
                return $this->compareMonths($period1, $period2);
            case 'quarter':
                return $this->compareQuarters($period1, $period2);
            case 'year':
                return $this->compareYears($period1, $period2);
            default:
                return [];
        }
    }

    private function compareMonths($period1, $period2)
    {
        // period1 y period2 formato: "2025-01"
        list($year1, $month1) = explode('-', $period1);
        list($year2, $month2) = explode('-', $period2);
        
        $data1 = RipsData::byPeriod($year1, $month1)->get();
        $data2 = RipsData::byPeriod($year2, $month2)->get();
        
        return [
            'period1' => $data1,
            'period2' => $data2,
            'comparison' => $this->calculateComparison($data1, $data2)
        ];
    }

    private function compareQuarters($period1, $period2)
    {
        // period1 y period2 formato: "2025-Q1"
        list($year1, $quarter1) = explode('-Q', $period1);
        list($year2, $quarter2) = explode('-Q', $period2);
        
        $data1 = RipsData::byTrimester($year1, $quarter1)->get();
        $data2 = RipsData::byTrimester($year2, $quarter2)->get();
        
        return [
            'period1' => $data1,
            'period2' => $data2,
            'comparison' => $this->calculateComparison($data1, $data2)
        ];
    }

    private function compareYears($year1, $year2)
    {
        $data1 = RipsData::byPeriod($year1)->get();
        $data2 = RipsData::byPeriod($year2)->get();
        
        return [
            'period1' => $data1,
            'period2' => $data2,
            'comparison' => $this->calculateComparison($data1, $data2)
        ];
    }

    private function calculateComparison($data1, $data2)
    {
        $total1 = $data1->sum('facturado');
        $total2 = $data2->sum('facturado');
        
        $variance = $total2 > 0 ? (($total1 - $total2) / $total2) * 100 : 0;
        
        return [
            'total_period1' => $total1,
            'total_period2' => $total2,
            'variance_percentage' => round($variance, 2),
            'absolute_difference' => $total1 - $total2
        ];
    }
}