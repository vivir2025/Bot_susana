@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Dashboard RIPS - Hospital Susana López de Valencia</h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Facturación por Régimen</h3>
                </div>
                <div class="card-body">
                    <canvas id="facturacionChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Comparación de Períodos</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <select id="comparisonType" class="form-control">
                            <option value="month">Comparar Meses</option>
                            <option value="quarter">Comparar Trimestres</option>
                            <option value="year">Comparar Años</option>
                        </select>
                    </div>
                    <div id="comparisonForm"></div>
                    <div id="comparisonResults"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Script para manejar las comparaciones dinámicas
document.addEventListener('DOMContentLoaded', function() {
    const comparisonType = document.getElementById('comparisonType');
    const comparisonForm = document.getElementById('comparisonForm');
    const comparisonResults = document.getElementById('comparisonResults');
    
    comparisonType.addEventListener('change', function() {
        updateComparisonForm(this.value);
    });
    
    function updateComparisonForm(type) {
        // Generar formulario dinámico basado en el tipo
        let formHtml = '';
        
        switch(type) {
            case 'month':
                formHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <label>Mes 1:</label>
                            <input type="month" id="period1" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Mes 2:</label>
                            <input type="month" id="period2" class="form-control">
                        </div>
                    </div>
                `;
                break;
            case 'quarter':
                formHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <label>Trimestre 1:</label>
                            <select id="period1" class="form-control">
                                <option value="2025-Q1">2025 Q1</option>
                                <option value="2024-Q4">2024 Q4</option>
                                <option value="2024-Q3">2024 Q3</option>
                                <option value="2024-Q2">2024 Q2</option>
                                <option value="2024-Q1">2024 Q1</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Trimestre 2:</label>
                            <select id="period2" class="form-control">
                                <option value="2024-Q1">2024 Q1</option>
                                <option value="2024-Q2">2024 Q2</option>
                                <option value="2024-Q3">2024 Q3</option>
                                <option value="2024-Q4">2024 Q4</option>
                                <option value="2025-Q1">2025 Q1</option>
                            </select>
                        </div>
                    </div>
                `;
                break;
            case 'year':
                formHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <label>Año 1:</label>
                            <input type="number" id="period1" class="form-control" value="2025" min="2020" max="2030">
                        </div>
                        <div class="col-md-6">
                            <label>Año 2:</label>
                            <input type="number" id="period2" class="form-control" value="2024" min="2020" max="2030">
                        </div>
                    </div>
                `;
                break;
        }
        
        formHtml += `
            <div class="row mt-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary" onclick="compareData()">Comparar</button>
                </div>
            </div>
        `;
        
        comparisonForm.innerHTML = formHtml;
    }
    
    // Inicializar con comparación mensual
    updateComparisonForm('month');
});

function compareData() {
    const type = document.getElementById('comparisonType').value;
    const period1 = document.getElementById('period1').value;
    const period2 = document.getElementById('period2').value;
    
    fetch('/dashboard/compare', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: type,
            period1: period1,
            period2: period2
        })
    })
    .then(response => response.json())
    .then(data => {
        displayComparisonResults(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function displayComparisonResults(data) {
    const resultsDiv = document.getElementById('comparisonResults');
    const comparison = data.comparison;
    
    const html = `
        <div class="mt-3">
            <h5>Resultados de la Comparación</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6>Período 1</h6>
                            <h4>$${comparison.total_period1.toLocaleString()}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6>Período 2</h6>
                            <h4>$${comparison.total_period2.toLocaleString()}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card ${comparison.variance_percentage >= 0 ? 'bg-success' : 'bg-danger'} text-white">
                        <div class="card-body">
                            <h6>Variación</h6>
                            <h4>${comparison.variance_percentage}%</h4>
                            <small>$${comparison.absolute_difference.toLocaleString()}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    resultsDiv.innerHTML = html;
}
</script>
@endsection