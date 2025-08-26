<?php

namespace App\Http\Controllers;

use App\Models\RipsData;
use Illuminate\Http\Request;

class RipsDataController extends Controller
{

       public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $ripsData = RipsData::orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->paginate(20);
        
        return view('ripsdata.index', compact('ripsData'));
    }

    public function create()
    {
        $regimenes = ['Contributivo', 'Subsidiado', 'Excepción', 'Particular', 'Otro'];
        return view('ripsdata.create', compact('regimenes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
        'year' => 'required|integer|min:2020|max:2030',
        'month' => 'required|integer|min:1|max:12',
        'regimen' => 'required|string|max:50',
        'facturado' => 'required|numeric|min:0',
        'consultas_especializada' => 'nullable|integer|min:0',
        'interconsultas_hospitalaria' => 'nullable|integer|min:0',
        'urgencias_general' => 'nullable|integer|min:0',
        'urgencias_especialista' => 'nullable|integer|min:0',
        'egresos_hospitalarios' => 'nullable|integer|min:0',
        'imagenologia' => 'nullable|integer|min:0',
        'laboratorio' => 'nullable|integer|min:0',
        'partos' => 'nullable|integer|min:0',
        'cesareas' => 'nullable|integer|min:0',
        'cirugias' => 'nullable|integer|min:0',
        'terapia_fisica' => 'nullable|integer|min:0',
        'terapia_respiratoria' => 'nullable|integer|min:0',
        'observaciones' => 'nullable|integer|min:0'
    ]);

        // Verificar si ya existe un registro para ese año, mes y régimen
        $exists = RipsData::where('year', $validated['year'])
                    ->where('month', $validated['month'])
                    ->where('regimen', $validated['regimen'])
                    ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'regimen' => 'Ya existe un registro para este año, mes y régimen'
            ]);
        }

        RipsData::create($validated);

        return redirect()->route('ripsdata.index')
            ->with('success', 'Datos RIPS creados exitosamente');
    }

    public function edit(RipsData $ripsdata)
    {
        $regimenes = ['Contributivo', 'Subsidiado', 'Excepción', 'Particular', 'Otro'];
        return view('ripsdata.edit', compact('ripsdata', 'regimenes'));
    }

    public function update(Request $request, RipsData $ripsdata)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
            'regimen' => 'required|string|max:50',
            'facturado' => 'required|numeric|min:0',
            'consultas_especializada' => 'nullable|integer|min:0',
            'interconsultas_hospitalaria' => 'nullable|integer|min:0',
            'urgencias_general' => 'nullable|integer|min:0',
            'urgencias_especialista' => 'nullable|integer|min:0',
            'egresos_hospitalarios' => 'nullable|integer|min:0',
            'imagenologia' => 'nullable|integer|min:0',
            'laboratorio' => 'nullable|integer|min:0',
            'partos' => 'nullable|integer|min:0',
            'cesareas' => 'nullable|integer|min:0',
            'cirugias' => 'nullable|integer|min:0',
            'terapia_fisica' => 'nullable|integer|min:0',
            'terapia_respiratoria' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|integer|min:0'
        ]);

        $ripsdata->update($validated);

        return redirect()->route('ripsdata.index')
            ->with('success', 'Datos RIPS actualizados exitosamente');
    }

    public function destroy(RipsData $ripsdata)
    {
        $ripsdata->delete();
        return redirect()->route('ripsdata.index')
            ->with('success', 'Datos RIPS eliminados exitosamente');
    }
}