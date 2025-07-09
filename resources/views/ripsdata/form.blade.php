@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>{{ isset($ripsdata) ? 'Editar' : 'Crear' }} Datos RIPS</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($ripsdata) ? route('ripsdata.update', $ripsdata->id) : route('ripsdata.store') }}">
                        @csrf
                        @if(isset($ripsdata))
                            @method('PUT')
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="year" class="form-label">Año</label>
                                <input type="number" class="form-control" id="year" name="year" 
                                    value="{{ old('year', $ripsdata->year ?? date('Y')) }}" min="2020" max="2030" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="month" class="form-label">Mes</label>
                                <select class="form-control" id="month" name="month" required>
                                    <option value="">Seleccione...</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('month', $ripsdata->month ?? '') == $i ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="regimen" class="form-label">Régimen</label>
                                <select class="form-control" id="regimen" name="regimen" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($regimenes as $regimen)
                                        <option value="{{ $regimen }}" {{ old('regimen', $ripsdata->regimen ?? '') == $regimen ? 'selected' : '' }}>
                                            {{ $regimen }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="facturado" class="form-label">Valor Facturado</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="facturado" name="facturado" 
                                        value="{{ old('facturado', $ripsdata->facturado ?? '') }}" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="consultas_especializada" class="form-label">Consultas Especializada</label>
                                <input type="number" class="form-control" id="consultas_especializada" name="consultas_especializada" 
                                    value="{{ old('consultas_especializada', $ripsdata->consultas_especializada ?? 0) }}" min="0">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="interconsultas_hospitalaria" class="form-label">Interconsultas Hospitalaria</label>
                                <input type="number" class="form-control" id="interconsultas_hospitalaria" name="interconsultas_hospitalaria" 
                                    value="{{ old('interconsultas_hospitalaria', $ripsdata->interconsultas_hospitalaria ?? 0) }}" min="0">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="urgencias_general" class="form-label">Urgencias General</label>
                                <input type="number" class="form-control" id="urgencias_general" name="urgencias_general" 
                                    value="{{ old('urgencias_general', $ripsdata->urgencias_general ?? 0) }}" min="0">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="urgencias_especialista" class="form-label">Urgencias Especialista</label>
                                <input type="number" class="form-control" id="urgencias_especialista" name="urgencias_especialista" 
                                    value="{{ old('urgencias_especialista', $ripsdata->urgencias_especialista ?? 0) }}" min="0">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="egresos_hospitalarios" class="form-label">Egresos Hospitalarios</label>
                                <input type="number" class="form-control" id="egresos_hospitalarios" name="egresos_hospitalarios" 
                                    value="{{ old('egresos_hospitalarios', $ripsdata->egresos_hospitalarios ?? 0) }}" min="0">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="imagenologia" class="form-label">Imagenología</label>
                                <input type="number" class="form-control" id="imagenologia" name="imagenologia" 
                                    value="{{ old('imagenologia', $ripsdata->imagenologia ?? 0) }}" min="0">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="laboratorio" class="form-label">Laboratorio</label>
                                <input type="number" class="form-control" id="laboratorio" name="laboratorio" 
                                    value="{{ old('laboratorio', $ripsdata->laboratorio ?? 0) }}" min="0">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="partos" class="form-label">Partos</label>
                                <input type="number" class="form-control" id="partos" name="partos" 
                                    value="{{ old('partos', $ripsdata->partos ?? 0) }}" min="0">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="cesareas" class="form-label">Cesáreas</label>
                                <input type="number" class="form-control" id="cesareas" name="cesareas" 
                                    value="{{ old('cesareas', $ripsdata->cesareas ?? 0) }}" min="0">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="cirugias" class="form-label">Cirugías</label>
                                <input type="number" class="form-control" id="cirugias" name="cirugias" 
                                    value="{{ old('cirugias', $ripsdata->cirugias ?? 0) }}" min="0">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="terapia_fisica" class="form-label">Terapia Física</label>
                                <input type="number" class="form-control" id="terapia_fisica" name="terapia_fisica" 
                                    value="{{ old('terapia_fisica', $ripsdata->terapia_fisica ?? 0) }}" min="0">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="terapia_respiratoria" class="form-label">Terapia Respiratoria</label>
                                <input type="number" class="form-control" id="terapia_respiratoria" name="terapia_respiratoria" 
                                    value="{{ old('terapia_respiratoria', $ripsdata->terapia_respiratoria ?? 0) }}" min="0">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="3">{{ old('observaciones', $ripsdata->observaciones ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('ripsdata.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection