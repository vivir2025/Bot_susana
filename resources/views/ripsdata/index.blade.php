@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Registros RIPS</h3>
                        <a href="{{ route('ripsdata.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nuevo Registro
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Año</th>
                                    <th>Mes</th>
                                    <th>Régimen</th>
                                    <th>Facturado</th>
                                    <th>Consultas</th>
                                    <th>Interconsultas</th>
                                    <th>Urgencias G</th>
                                    <th>Urgencias E</th>
                                    <th>Egresos</th>
                                    <th>Imagenología</th>
                                    <th>Laboratorio</th>
                                    <th>Partos</th>
                                    <th>Cesáreas</th>
                                    <th>Cirugías</th>
                                    <th>Terapia Física</th>
                                    <th>Terapia Resp.</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ripsData as $data)
                                <tr>
                                    <td>{{ $data->year }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('m', $data->month)->translatedFormat('F') }}</td>
                                    <td>{{ $data->regimen }}</td>
                                    <td>${{ number_format($data->facturado, 2) }}</td>
                                    <td>{{ $data->consultas_especializada }}</td>
                                    <td>{{ $data->interconsultas_hospitalaria }}</td>
                                    <td>{{ $data->urgencias_general }}</td>
                                    <td>{{ $data->urgencias_especialista }}</td>
                                    <td>{{ $data->egresos_hospitalarios }}</td>
                                    <td>{{ $data->imagenologia }}</td>
                                    <td>{{ $data->laboratorio }}</td>
                                    <td>{{ $data->partos }}</td>
                                    <td>{{ $data->cesareas }}</td>
                                    <td>{{ $data->cirugias }}</td>
                                    <td>{{ $data->terapia_fisica }}</td>
                                    <td>{{ $data->terapia_respiratoria }}</td>
                                    <td>{{ Str::limit($data->observaciones, 30) }}</td>
                                    <td>
                                        <a href="{{ route('ripsdata.edit', $data->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('ripsdata.destroy', $data->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este registro?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $ripsData->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection