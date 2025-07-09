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

    <!-- Sección de gráfica comparativa -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Visualización Comparativa</h3>
                        <div>
                            <select id="chartType" class="form-control form-control-sm" style="width: 150px;">
                                <option value="bar">Barras</option>
                                <option value="line">Líneas</option>
                                <option value="pie">Pastel</option>
                                <option value="doughnut">Dona</option>
                                <option value="radar">Radar</option>
                            </select>
                            <button id="downloadChart" class="btn btn-sm btn-success ml-2">
                                <i class="fas fa-download"></i> Descargar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="comparisonChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/plugins/plugin.zoom.min.js"></script>

<script>
// Variables globales
let comparisonChart = null;
let chartData = null;

// Script para manejar las comparaciones dinámicas
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar gráfica de facturación
    initFacturacionChart();
    
    const comparisonType = document.getElementById('comparisonType');
    const comparisonForm = document.getElementById('comparisonForm');
    
    comparisonType.addEventListener('change', function() {
        updateComparisonForm(this.value);
    });
    
    // Configurar evento para cambiar tipo de gráfica
    document.getElementById('chartType').addEventListener('change', function() {
        if (comparisonChart) {
            comparisonChart.config.type = this.value;
            comparisonChart.update();
        }
    });
    
    // Configurar evento para descargar gráfica
    document.getElementById('downloadChart').addEventListener('click', function() {
        if (comparisonChart) {
            const link = document.createElement('a');
            link.download = 'comparacion-rips.png';
            link.href = comparisonChart.toBase64Image('image/png', 1);
            link.click();
        } else {
            alert('No hay datos para descargar');
        }
    });
    
    // Inicializar con comparación mensual
    updateComparisonForm('month');
});

function initFacturacionChart() {
    const ctx = document.getElementById('facturacionChart').getContext('2d');
    
    // Datos desde el servidor (puedes usar @json($facturacionData) si tienes datos reales)
    const facturacionData = @json($facturacionData ?? []);
    
    let labels = [];
    let data = [];
    
    if (facturacionData.length > 0) {
        labels = facturacionData.map(item => item.regimen);
        data = facturacionData.map(item => item.total);
    } else {
        // Datos de ejemplo si no hay datos reales
        labels = ['Contributivo', 'Subsidiado', 'Excepción', 'Particular', 'Otro'];
        data = [12000000, 8000000, 3000000, 2000000, 500000];
    }
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Facturación por Régimen',
                data: data,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('es-CO', {
                                style: 'currency',
                                currency: 'COP'
                            }).format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('es-CO', {
                                style: 'currency',
                                currency: 'COP',
                                maximumFractionDigits: 0
                            }).format(value);
                        }
                    }
                }
            }
        }
    });
}

function updateComparisonForm(type) {
    let formHtml = '';
    
    switch(type) {
        case 'month':
            formHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <label>Mes 1:</label>
                        <input type="month" id="period1" class="form-control" value="${getCurrentYearMonth()}">
                    </div>
                    <div class="col-md-6">
                        <label>Mes 2:</label>
                        <input type="month" id="period2" class="form-control" value="${getPreviousYearMonth()}">
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
                            <option value="${getCurrentYear()}-Q1">${getCurrentYear()} Q1</option>
                            <option value="${getCurrentYear()-1}-Q4">${getCurrentYear()-1} Q4</option>
                            <option value="${getCurrentYear()-1}-Q3">${getCurrentYear()-1} Q3</option>
                            <option value="${getCurrentYear()-1}-Q2">${getCurrentYear()-1} Q2</option>
                            <option value="${getCurrentYear()-1}-Q1">${getCurrentYear()-1} Q1</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Trimestre 2:</label>
                        <select id="period2" class="form-control">
                            <option value="${getCurrentYear()-1}-Q1">${getCurrentYear()-1} Q1</option>
                            <option value="${getCurrentYear()-1}-Q2">${getCurrentYear()-1} Q2</option>
                            <option value="${getCurrentYear()-1}-Q3">${getCurrentYear()-1} Q3</option>
                            <option value="${getCurrentYear()-1}-Q4">${getCurrentYear()-1} Q4</option>
                            <option value="${getCurrentYear()}-Q1">${getCurrentYear()} Q1</option>
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
                        <input type="number" id="period1" class="form-control" value="${getCurrentYear()}" min="2020" max="2030">
                    </div>
                    <div class="col-md-6">
                        <label>Año 2:</label>
                        <input type="number" id="period2" class="form-control" value="${getCurrentYear()-1}" min="2020" max="2030">
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
    
    document.getElementById('comparisonForm').innerHTML = formHtml;
}

function getCurrentYear() {
    return new Date().getFullYear();
}

function getCurrentYearMonth() {
    const now = new Date();
    return `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
}

function getPreviousYearMonth() {
    const now = new Date();
    let year = now.getFullYear();
    let month = now.getMonth(); // 0-based
    
    if (month === 0) {
        month = 12;
        year--;
    }
    
    return `${year}-${String(month).padStart(2, '0')}`;
}

function compareData() {
    const type = document.getElementById('comparisonType').value;
    const period1 = document.getElementById('period1').value;
    const period2 = document.getElementById('period2').value;
    
    console.log('Comparando:', { type, period1, period2 }); // Debug
    
    // Mostrar carga
    document.getElementById('comparisonResults').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    
    fetch('/dashboard/compare', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            type: type,
            period1: period1,
            period2: period2
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos recibidos:', data); // Debug
        displayComparisonResults(data);
        renderComparisonChart(data);
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('comparisonResults').innerHTML = `
            <div class="alert alert-danger">
                Error al cargar los datos. Por favor intente nuevamente.
            </div>
        `;
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
                            <h6>${comparison.period1_label}</h6>
                            <h4>${formatCurrency(comparison.total_period1)}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6>${comparison.period2_label}</h6>
                            <h4>${formatCurrency(comparison.total_period2)}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card ${comparison.variance_percentage >= 0 ? 'bg-success' : 'bg-danger'} text-white">
                        <div class="card-body">
                            <h6>Variación</h6>
                            <h4>${comparison.variance_percentage.toFixed(2)}%</h4>
                            <small>${formatCurrency(Math.abs(comparison.absolute_difference))}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    resultsDiv.innerHTML = html;
}

function renderComparisonChart(data) {
    const ctx = document.getElementById('comparisonChart').getContext('2d');
    const chartType = document.getElementById('chartType').value;
    
    console.log('Renderizando gráfica con datos:', data); // Debug
    
    // Verificar que los datos necesarios existen
    if (!data.labels || !data.comparison || !data.comparison.period1_data || !data.comparison.period2_data) {
        console.error('Datos incompletos para la gráfica:', data);
        return;
    }
    
    // Destruir gráfica anterior si existe
    if (comparisonChart) {
        comparisonChart.destroy();
    }
    
    // Configurar colores según el tipo de gráfica
    let backgroundColor1, backgroundColor2;
    
    if (chartType === 'pie' || chartType === 'doughnut' || chartType === 'radar') {
        backgroundColor1 = [
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(153, 102, 255, 0.7)'
        ];
        backgroundColor2 = [
            'rgba(255, 206, 86, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(199, 199, 199, 0.7)',
            'rgba(83, 102, 255, 0.7)'
        ];
    } else {
        backgroundColor1 = 'rgba(54, 162, 235, 0.7)';
        backgroundColor2 = 'rgba(255, 206, 86, 0.7)';
    }
    
    // Crear la nueva gráfica
    comparisonChart = new Chart(ctx, {
        type: chartType,
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: data.comparison.period1_label,
                    data: data.comparison.period1_data,
                    backgroundColor: backgroundColor1,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: data.comparison.period2_label,
                    data: data.comparison.period2_data,
                    backgroundColor: backgroundColor2,
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== undefined) {
                                label += formatCurrency(context.parsed.y);
                            } else if (context.parsed !== undefined) {
                                label += formatCurrency(context.parsed);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: chartType !== 'pie' && chartType !== 'doughnut' && chartType !== 'radar' ? {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value, true);
                        }
                    }
                }
            } : {}
        }
    });
}

function formatCurrency(value, shortForm = false) {
    if (value >= 1000000) {
        return shortForm ? 
            '$' + (value / 1000000).toFixed(1) + 'M' :
            new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(value);
    } else {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(value);
    }
}
</script>


@endsection