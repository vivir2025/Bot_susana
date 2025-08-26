@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Editar Registro RIPS</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('ripsdata.update', $ripsdata->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Sección de Periodo y Régimen -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="year" class="form-label">Año *</label>
                                    <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                           id="year" name="year" value="{{ old('year', $ripsdata->year) }}" 
                                           min="2020" max="2030" required>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="month" class="form-label">Mes *</label>
                                    <select class="form-control @error('month') is-invalid @enderror" 
                                            id="month" name="month" required>
                                        <option value="">Seleccione un mes...</option>
                                        @foreach(range(1, 12) as $month)
                                            <option value="{{ $month }}" {{ old('month', $ripsdata->month) == $month ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::createFromFormat('m', $month)->translatedFormat('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="regimen" class="form-label">Régimen *</label>
                                    <select class="form-control @error('regimen') is-invalid @enderror" 
                                            id="regimen" name="regimen" required>
                                        <option value="">Seleccione un régimen...</option>
                                        @foreach(['CONTRIBUTIVO', 'SUBSIDIADO', 'PPNA', 'SOAT', 'ADRES','OTRAS VENTAS'] as $regimen)
                                            <option value="{{ $regimen }}" {{ old('regimen', $ripsdata->regimen) == $regimen ? 'selected' : '' }}>
                                                {{ $regimen }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('regimen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Valores Monetarios -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facturado" class="form-label">Valor Facturado *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control @error('facturado') is-invalid @enderror" 
                                               id="facturado" name="facturado" value="{{ old('facturado', $ripsdata->facturado) }}" 
                                               min="0" required>
                                        @error('facturado')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Consultas y Urgencias -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="consultas_especializada" class="form-label">Consultas Especializadas</label>
                                    <input type="number" class="form-control @error('consultas_especializada') is-invalid @enderror" 
                                           id="consultas_especializada" name="consultas_especializada" 
                                           value="{{ old('consultas_especializada', $ripsdata->consultas_especializada) }}" min="0">
                                    @error('consultas_especializada')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="interconsultas_hospitalaria" class="form-label">Interconsultas Hospitalarias</label>
                                    <input type="number" class="form-control @error('interconsultas_hospitalaria') is-invalid @enderror" 
                                           id="interconsultas_hospitalaria" name="interconsultas_hospitalaria" 
                                           value="{{ old('interconsultas_hospitalaria', $ripsdata->interconsultas_hospitalaria) }}" min="0">
                                    @error('interconsultas_hospitalaria')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="urgencias_general" class="form-label">Urgencias General</label>
                                    <input type="number" class="form-control @error('urgencias_general') is-invalid @enderror" 
                                           id="urgencias_general" name="urgencias_general" 
                                           value="{{ old('urgencias_general', $ripsdata->urgencias_general) }}" min="0">
                                    @error('urgencias_general')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="urgencias_especialista" class="form-label">Urgencias Especialista</label>
                                    <input type="number" class="form-control @error('urgencias_especialista') is-invalid @enderror" 
                                           id="urgencias_especialista" name="urgencias_especialista" 
                                           value="{{ old('urgencias_especialista', $ripsdata->urgencias_especialista) }}" min="0">
                                    @error('urgencias_especialista')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Hospitalarios -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="egresos_hospitalarios" class="form-label">Egresos Hospitalarios</label>
                                    <input type="number" class="form-control @error('egresos_hospitalarios') is-invalid @enderror" 
                                           id="egresos_hospitalarios" name="egresos_hospitalarios" 
                                           value="{{ old('egresos_hospitalarios', $ripsdata->egresos_hospitalarios) }}" min="0">
                                    @error('egresos_hospitalarios')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="imagenologia" class="form-label">Imagenología</label>
                                    <input type="number" class="form-control @error('imagenologia') is-invalid @enderror" 
                                           id="imagenologia" name="imagenologia" 
                                           value="{{ old('imagenologia', $ripsdata->imagenologia) }}" min="0">
                                    @error('imagenologia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="laboratorio" class="form-label">Laboratorio</label>
                                    <input type="number" class="form-control @error('laboratorio') is-invalid @enderror" 
                                           id="laboratorio" name="laboratorio" 
                                           value="{{ old('laboratorio', $ripsdata->laboratorio) }}" min="0">
                                    @error('laboratorio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="partos" class="form-label">Partos</label>
                                    <input type="number" class="form-control @error('partos') is-invalid @enderror" 
                                           id="partos" name="partos" 
                                           value="{{ old('partos', $ripsdata->partos) }}" min="0">
                                    @error('partos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Procedimientos -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cesareas" class="form-label">Cesáreas</label>
                                    <input type="number" class="form-control @error('cesareas') is-invalid @enderror" 
                                           id="cesareas" name="cesareas" 
                                           value="{{ old('cesareas', $ripsdata->cesareas) }}" min="0">
                                    @error('cesareas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cirugias" class="form-label">Cirugías</label>
                                    <input type="number" class="form-control @error('cirugias') is-invalid @enderror" 
                                           id="cirugias" name="cirugias" 
                                           value="{{ old('cirugias', $ripsdata->cirugias) }}" min="0">
                                    @error('cirugias')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="terapia_fisica" class="form-label">Terapia Física</label>
                                    <input type="number" class="form-control @error('terapia_fisica') is-invalid @enderror" 
                                           id="terapia_fisica" name="terapia_fisica" 
                                           value="{{ old('terapia_fisica', $ripsdata->terapia_fisica) }}" min="0">
                                    @error('terapia_fisica')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="terapia_respiratoria" class="form-label">Terapia Respiratoria</label>
                                    <input type="number" class="form-control @error('terapia_respiratoria') is-invalid @enderror" 
                                           id="terapia_respiratoria" name="terapia_respiratoria" 
                                           value="{{ old('terapia_respiratoria', $ripsdata->terapia_respiratoria) }}" min="0">
                                    @error('terapia_respiratoria')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Observaciones -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                              id="observaciones" name="observaciones" rows="3">{{ old('observaciones', $ripsdata->observaciones) }}</textarea>
                                    @error('observaciones')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-3">
                            <a href="{{ route('ripsdata.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection