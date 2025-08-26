@extends('layouts.app')

@section('content')

<style>
    /* Contenedores principales */
#singleChartContainer, #doubleChartContainer {
    position: relative;
    width: 100%;
    min-height: 400px;
}

/* Espaciado extra para gráficas de línea */
.line-chart-container {
    padding-top: 80px; /* Espacio para etiquetas de valores */
}

/* Espaciado adicional para etiquetas rotadas en barras */
.bar-chart-container {
    padding-bottom: 60px; /* Espacio extra para etiquetas rotadas */
}

/* Mejoras específicas para gráficas de barras y líneas */
.chart-container {
    position: relative;
    overflow: visible;
}

/* Estilo para las líneas conectoras en gráficas de línea */
.line-chart-connector {
    stroke-dasharray: 3, 2;
    stroke-width: 1;
    opacity: 0.7;
}

/* Estilo para los puntos mejorados en líneas */
.enhanced-line-point {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

/* Ajustes para gráficas circulares */
.doughnut-pie-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
}

/* Leyenda mejorada */
.chartjs-legend {
    max-width: 100%;
    overflow-y: auto;
    padding: 10px;
}

.chartjs-legend-item {
    margin: 8px 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.chartjs-legend-item:hover {
    opacity: 0.8;
}

.chartjs-legend-item-hidden {
    opacity: 0.5;
}

.chartjs-legend-item-hidden text {
    text-decoration: line-through !important;
}

/* Ajustes para radar */
.radar-container {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Valores en gráficas de línea/barras */
.chartjs-value-label {
    font-size: 11px;
    font-weight: bold;
    fill: #333;
}

/* Asegurar que las etiquetas del radar no se corten */
#comparisonChart1, #comparisonChart2 {
    margin: 0 auto;
    max-width: 100%;
}

/* Mejorar el espaciado de las etiquetas */
.chartjs-radar-point-label {
    white-space: pre-line;
    text-align: center;
    padding: 5px;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Mejoras específicas para gráficas de barras y líneas */
.chart-container {
    position: relative;
    overflow: visible;
}

/* Espaciado adicional para etiquetas rotadas en barras */
.bar-chart-container {
    padding-bottom: 60px; /* Espacio extra para etiquetas rotadas */
}

/* Estilo para las etiquetas de valor */
.value-label {
    font-family: Arial, sans-serif;
    font-weight: bold;
    font-size: 10px;
    text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
}

/* Asegurar que los valores no se superpongan */
.chart-values-container {
    position: relative;
    z-index: 10;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .value-label {
        font-size: 8px;
    }
    
    .bar-chart-container {
        padding-bottom: 80px;
    }
}

/* Mejorar la legibilidad de los valores en fondos oscuros */
.chart-value-background {
    background-color: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    padding: 2px 4px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h4 h-md-3">Dashboard RIPS - Hospital Susana López de Valencia</h1>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="h5">Facturación por Régimen (Ejemplo datos)</h3>
                </div>
                <div class="card-body" style="position: relative; height: 300px;">
                    <canvas id="facturacionChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="h5">Comparación de Períodos</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="small">Tipo de Comparación:</label>
                        <select id="comparisonType" class="form-control form-control-sm">
                            <option value="month">Comparar Meses</option>
                            <option value="quarter">Comparar Trimestres</option>
                            <option value="year">Comparar Años</option>
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <label class="small">Indicador a Comparar:</label>
                        <select id="dataType" class="form-control form-control-sm">
                            <option value="facturado">Facturación</option>
                            <option value="consultas_especializada">Consultas Especializadas</option>
                            <option value="interconsultas_hospitalaria">Interconsultas Hospitalarias</option>
                            <option value="urgencias_general">Urgencias General</option>
                            <option value="urgencias_especialista">Urgencias Especialista</option>
                            <option value="egresos_hospitalarios">Egresos Hospitalarios</option>
                            <option value="imagenologia">Imagenología</option>
                            <option value="laboratorio">Laboratorio</option>
                            <option value="partos">Partos</option>
                            <option value="cesareas">Cesáreas</option>
                            <option value="cirugias">Cirugías</option>
                            <option value="terapia_fisica">Terapia Física</option>
                            <option value="terapia_respiratoria">Terapia Respiratoria</option>
                            <option value="observaciones">Observaciones</option>

                        </select>
                    </div>
                    <div id="comparisonForm"></div>
                    <div id="comparisonResults" class="mt-2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de gráfica comparativa -->
    <div class="row mt-3">
        <div class="col-md-12 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="h5">Visualización Comparativa</h3>
                        <div class="d-flex align-items-center">
                            <select id="chartType" class="form-control form-control-sm mr-2" style="width: 150px;">
                                <option value="bar">Barras</option>
                                <option value="line">Líneas</option>
                                <option value="pie">Pastel - Por Régimen</option>
                                <option value="doughnut">Dona - Por Régimen</option>
                                <option value="pie-total">Pastel - Total Períodos</option>
                                <option value="doughnut-total">Dona - Total Períodos</option>
                            </select>
                            <button id="downloadChart" class="btn btn-sm btn-success">
                                <i class="fas fa-download"></i> Descargar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Contenedor para gráficas únicas -->
                    <div id="singleChartContainer" style="display: none; position: relative; height: 400px;">
                        <canvas id="comparisonChart"></canvas>
                    </div>
                    
                  <!-- Contenedor para gráficas dobles -->
<div id="doubleChartContainer" style="display: none;">
    <div class="row justify-content-center">
        <div class="col-md-6 mb-3 mb-md-0 d-flex flex-column align-items-center">
            <h5 id="chart1Title" class="text-center mb-2" style="width: 100%"></h5>
            <div style="position: relative; width: 100%; max-width: 420px; height: 350px;">
                <canvas id="comparisonChart1" style="display: block; margin: 0 auto; width: 100% !important; height: 350px !important;"></canvas>
            </div>
        </div>
        <div class="col-md-6 d-flex flex-column align-items-center">
            <h5 id="chart2Title" class="text-center mb-2" style="width: 100%"></h5>
            <div style="position: relative; width: 100%; max-width: 420px; height: 350px;">
                <canvas id="comparisonChart2" style="display: block; margin: 0 auto; width: 100% !important; height: 350px !important;"></canvas>
            </div>
        </div>
    </div>
</div>
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
let comparisonChart1 = null;
let comparisonChart2 = null;
let chartData = null;
let currentChartData = null;
let currentDataType = 'facturado';

// Paleta de colores más amplia
const colorPalette = [
    'rgba(54, 162, 235, 0.7)',
    'rgba(255, 99, 132, 0.7)',
    'rgba(75, 192, 192, 0.7)',
    'rgba(255, 206, 86, 0.7)',
    'rgba(153, 102, 255, 0.7)',
    'rgba(255, 159, 64, 0.7)',
    'rgba(199, 199, 199, 0.7)',
    'rgba(83, 102, 255, 0.7)',
    'rgba(255, 99, 255, 0.7)',
    'rgba(132, 255, 99, 0.7)'
];

const borderColorPalette = [
    'rgba(54, 162, 235, 1)',
    'rgba(255, 99, 132, 1)',
    'rgba(75, 192, 192, 1)',
    'rgba(255, 206, 86, 1)',
    'rgba(153, 102, 255, 1)',
    'rgba(255, 159, 64, 1)',
    'rgba(199, 199, 199, 1)',
    'rgba(83, 102, 255, 1)',
    'rgba(255, 99, 255, 1)',
    'rgba(132, 255, 99, 1)'
];

// Script para manejar las comparaciones dinámicas
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar gráfica de facturación
    initFacturacionChart();
    
    const comparisonType = document.getElementById('comparisonType');
    const dataType = document.getElementById('dataType');
    const comparisonForm = document.getElementById('comparisonForm');
    
    comparisonType.addEventListener('change', function() {
        updateComparisonForm(this.value);
    });
    
    dataType.addEventListener('change', function() {
        currentDataType = this.value;
        if (currentChartData) {
            renderComparisonChart(currentChartData);
        }
    });
    
    // Configurar evento para cambiar tipo de gráfica
    document.getElementById('chartType').addEventListener('change', function() {
        if (currentChartData) {
            renderComparisonChart(currentChartData);
        }
    });
    
    // Configurar evento para descargar gráfica
    document.getElementById('downloadChart').addEventListener('click', function() {
        downloadChartAsImage();
    });
    
    // Inicializar con comparación mensual
    updateComparisonForm('month');
    
    // Ajustar gráficas al cambiar tamaño de pantalla
    window.addEventListener('resize', function() {
        if (comparisonChart) comparisonChart.resize();
        if (comparisonChart1) comparisonChart1.resize();
        if (comparisonChart2) comparisonChart2.resize();
    });
});

function initFacturacionChart() {
    const ctx = document.getElementById('facturacionChart').getContext('2d');
    
    // Datos desde el servidor
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
                backgroundColor: colorPalette.slice(0, labels.length),
                borderColor: borderColorPalette.slice(0, labels.length),
                borderWidth: 1
            }]
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
                            return new Intl.NumberFormat('es-CO', {
                                style: 'currency',
                                currency: 'COP'
                            }).format(context.raw);
                        }
                    }
                },
                datalabels: {
                    display: false // Configurar plugin datalabels si lo incluyes
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
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="small">Mes 1:</label>
                        <input type="month" id="period1" class="form-control form-control-sm" value="${getCurrentYearMonth()}">
                    </div>
                    <div class="col-md-6">
                        <label class="small">Mes 2:</label>
                        <input type="month" id="period2" class="form-control form-control-sm" value="${getPreviousYearMonth()}">
                    </div>
                </div>
            `;
            break;
     case 'quarter':
    formHtml = `
        <div class="row mt-2">
            <div class="col-md-6">
                <label class="small">Año 1:</label>
                <input type="number" id="yearInput1" class="form-control form-control-sm" 
                       value="${getCurrentYear()}" min="2000" max="2100" 
                       onchange="updateQuarterOptions(1)">
                <label class="small mt-2">Trimestre 1:</label>
                <select id="period1" class="form-control form-control-sm">
                    <option value="${getCurrentYear()}-Q1">Q1</option>
                    <option value="${getCurrentYear()}-Q2">Q2</option>
                    <option value="${getCurrentYear()}-Q3">Q3</option>
                    <option value="${getCurrentYear()}-Q4">Q4</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="small">Año 2:</label>
                <input type="number" id="yearInput2" class="form-control form-control-sm" 
                       value="${getCurrentYear()-1}" min="2000" max="2100" 
                       onchange="updateQuarterOptions(2)">
                <label class="small mt-2">Trimestre 2:</label>
                <select id="period2" class="form-control form-control-sm">
                    <option value="${getCurrentYear()-1}-Q1">Q1</option>
                    <option value="${getCurrentYear()-1}-Q2">Q2</option>
                    <option value="${getCurrentYear()-1}-Q3">Q3</option>
                    <option value="${getCurrentYear()-1}-Q4">Q4</option>
                </select>
            </div>
        </div>
    `;
    break;
        case 'year':
            formHtml = `
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="small">Año 1:</label>
                        <input type="number" id="period1" class="form-control form-control-sm" value="${getCurrentYear()}" min="2020" max="2030">
                    </div>
                    <div class="col-md-6">
                        <label class="small">Año 2:</label>
                        <input type="number" id="period2" class="form-control form-control-sm" value="${getCurrentYear()-1}" min="2020" max="2030">
                    </div>
                </div>
            `;
            break;
    }
    
    formHtml += `
        <div class="row mt-2">
            <div class="col-12">
                <button type="button" class="btn btn-primary btn-sm btn-block" onclick="compareData()">Comparar</button>
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
function generateYearOptions(selectedYear) {
    let options = '';
    const currentYear = getCurrentYear();
    for (let year = currentYear; year >= 2020; year--) {
        options += `<option value="${year}" ${year == selectedYear ? 'selected' : ''}>${year}</option>`;
    }
    return options;
}

function updateQuarterOptions(selectorNumber) {
    // Obtenemos el input del año y el select del trimestre
    const yearInput = document.getElementById(`yearInput${selectorNumber}`);
    const quarterSelect = document.getElementById(`period${selectorNumber}`);
    
    // Validamos que el año sea un número válido
    const selectedYear = parseInt(yearInput.value);
    if (isNaN(selectedYear) || selectedYear < 2000 || selectedYear > 2100) {
        // Si el año no es válido, mostramos un mensaje y reseteamos
        console.error("Año inválido. Debe estar entre 2000 y 2100.");
        yearInput.value = getCurrentYear(); // Establecemos el año actual como valor por defecto
        return;
    }
    
    // Guardamos el trimestre seleccionado actualmente (si existe)
    const currentValue = quarterSelect.value;
    let currentQuarter = null;
    
    if (currentValue) {
        const parts = currentValue.split('-');
        if (parts.length === 2 && parts[1].startsWith('Q')) {
            currentQuarter = parts[1];
        }
    }
    
    // Generamos las nuevas opciones de trimestre
    quarterSelect.innerHTML = `
        <option value="${selectedYear}-Q1">Q1</option>
        <option value="${selectedYear}-Q2">Q2</option>
        <option value="${selectedYear}-Q3">Q3</option>
        <option value="${selectedYear}-Q4">Q4</option>
    `;
    
    // Mantenemos el trimestre seleccionado anteriormente si es posible
    if (currentQuarter) {
        const newValue = `${selectedYear}-${currentQuarter}`;
        if (quarterSelect.querySelector(`option[value="${newValue}"]`)) {
            quarterSelect.value = newValue;
        }
    }
}

function getPreviousYearMonth() {
    const now = new Date();
    let year = now.getFullYear();
    let month = now.getMonth();
    
    if (month === 0) {
        month = 12;
        year--;
    }
    
    return `${year}-${String(month).padStart(2, '0')}`;
}

function compareData() {
    const type = document.getElementById('comparisonType').value;
    const dataType = document.getElementById('dataType').value;
    
    // Para trimestres, usamos los valores de los selectores period1 y period2 directamente
    const period1 = document.getElementById('period1').value;
    const period2 = document.getElementById('period2').value;
    
    console.log('Comparando:', { type, dataType, period1, period2 });
    
    // Mostrar carga
    document.getElementById('comparisonResults').innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    
    fetch('/susana/public/dashboard/compare', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            type: type,
            data_type: dataType,
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
        console.log('Datos recibidos:', data);
        currentChartData = data;
        displayComparisonResults(data);
        renderComparisonChart(data);
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('comparisonResults').innerHTML = `
            <div class="alert alert-danger py-2">
                Error al cargar los datos. Por favor intente nuevamente.
            </div>
        `;
    });
}

function displayComparisonResults(data) {
    const resultsDiv = document.getElementById('comparisonResults');
    const comparison = data.comparison;
    const dataTypeLabel = document.getElementById('dataType').options[document.getElementById('dataType').selectedIndex].text;
    
    const isCurrency = currentDataType === 'facturado';
    const html = `
        <div class="mt-2">
            <h6 class="text-center mb-2">Resultados de la Comparación - ${dataTypeLabel}</h6>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="card bg-info text-white">
                        <div class="card-body p-2 text-center">
                            <small>${comparison.period1_label}</small>
                            <h5 class="mb-0">${isCurrency ? formatCurrency(comparison.total_period1) : formatNumber(comparison.total_period1)}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="card bg-warning text-white">
                        <div class="card-body p-2 text-center">
                            <small>${comparison.period2_label}</small>
                            <h5 class="mb-0">${isCurrency ? formatCurrency(comparison.total_period2) : formatNumber(comparison.total_period2)}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="card ${comparison.variance_percentage >= 0 ? 'bg-success' : 'bg-danger'} text-white">
                        <div class="card-body p-2 text-center">
                            <small>Variación</small>
                            <h5 class="mb-0">${comparison.variance_percentage.toFixed(2)}%</h5>
                            <small class="d-block">${isCurrency ? formatCurrency(Math.abs(comparison.absolute_difference)) : formatNumber(Math.abs(comparison.absolute_difference))}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    resultsDiv.innerHTML = html;
}

function renderComparisonChart(data) {
    const chartType = document.getElementById('chartType').value;
    
    console.log('Renderizando gráfica con datos:', data);
    
    // Verificar que los datos necesarios existen
    if (!data.labels || !data.comparison || !data.comparison.period1_data || !data.comparison.period2_data) {
        console.error('Datos incompletos para la gráfica:', data);
        return;
    }
    
    // Destruir gráficas anteriores
    destroyAllCharts();
    
    // Determinar si usar gráfica simple o doble
    const useDoubleChart = ['pie', 'doughnut', 'radar'].includes(chartType);
    const useSingleChart = ['bar', 'line', 'pie-total', 'doughnut-total'].includes(chartType);
    
    if (useDoubleChart) {
        showDoubleChartContainer();
        renderDoubleChart(data, chartType);
    } else if (useSingleChart) {
        showSingleChartContainer();
        renderSingleChart(data, chartType);
    }
}

function showSingleChartContainer() {
    document.getElementById('singleChartContainer').style.display = 'block';
    document.getElementById('doubleChartContainer').style.display = 'none';
}

function showDoubleChartContainer() {
    document.getElementById('singleChartContainer').style.display = 'none';
    document.getElementById('doubleChartContainer').style.display = 'block';
}

function destroyAllCharts() {
    if (comparisonChart) {
        comparisonChart.destroy();
        comparisonChart = null;
    }
    if (comparisonChart1) {
        comparisonChart1.destroy();
        comparisonChart1 = null;
    }
    if (comparisonChart2) {
        comparisonChart2.destroy();
        comparisonChart2 = null;
    }
}

function renderSingleChart(data, chartType) {
    const ctx = document.getElementById('comparisonChart').getContext('2d');
    
    let chartConfig = {};
    
    if (chartType === 'pie-total' || chartType === 'doughnut-total') {
        chartConfig = createTotalPeriodChart(data, chartType);
    } else {
        chartConfig = createNormalChart(data, chartType);
    }
    
    comparisonChart = new Chart(ctx, chartConfig);
}

function renderDoubleChart(data, chartType) {
    const ctx1 = document.getElementById('comparisonChart1').getContext('2d');
    const ctx2 = document.getElementById('comparisonChart2').getContext('2d');
    
    // Actualizar títulos
    document.getElementById('chart1Title').textContent = data.comparison.period1_label;
    document.getElementById('chart2Title').textContent = data.comparison.period2_label;
    
    // Configurar gráfica 1 (período 1)
    const config1 = createPeriodChart(data, chartType, 1);
    comparisonChart1 = new Chart(ctx1, config1);
    
    // Configurar gráfica 2 (período 2)
    const config2 = createPeriodChart(data, chartType, 2);
    comparisonChart2 = new Chart(ctx2, config2);
}

function createTotalPeriodChart(data, chartType) {
    const type = chartType.replace('-total', '');
    const totalData = [data.comparison.total_period1, data.comparison.total_period2];
    const labels = [data.comparison.period1_label, data.comparison.period2_label];
    const dataTypeLabel = document.getElementById('dataType').options[document.getElementById('dataType').selectedIndex].text;
    const isCurrency = currentDataType === 'facturado';
    
    return {
        type: type,
        data: {
            labels: labels,
            datasets: [{
                label: dataTypeLabel,
                data: totalData,
                backgroundColor: colorPalette.slice(0, 2),
                borderColor: borderColorPalette.slice(0, 2),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: `Comparación de ${dataTypeLabel}`,
                    font: {
                        size: 16
                    }
                },
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + (isCurrency ? formatCurrency(context.parsed) : formatNumber(context.parsed));
                        }
                    }
                },
                datalabels: {
                    formatter: function(value) {
                        return isCurrency ? formatCurrency(value, true) : formatNumber(value);
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 12
                    }
                }
            }
        },
        plugins: [{
            id: 'datalabels',
            afterDatasetsDraw(chart, args, options) {
                const {ctx, data, chartArea: {top, bottom, left, right, width, height}} = chart;
                
                if (chartType.includes('pie') || chartType.includes('doughnut')) {
                    ctx.font = 'bold 12px Arial';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    
                    data.datasets.forEach((dataset, i) => {
                        chart.getDatasetMeta(i).data.forEach((datapoint, index) => {
                            const {x, y} = datapoint.tooltipPosition();
                            const value = dataset.data[index];
                            const label = isCurrency ? formatCurrency(value, true) : formatNumber(value);
                            
                            ctx.fillStyle = '#333';
                            ctx.fillText(label, x, y);
                        });
                    });
                }
            }
        }]
    };
}
function createPeriodChart(data, chartType, period) {
    const labels = data.labels;
    const chartData = period === 1 ? data.comparison.period1_data : data.comparison.period2_data;
    const title = period === 1 ? data.comparison.period1_label : data.comparison.period2_label;
    const dataTypeLabel = document.getElementById('dataType').options[document.getElementById('dataType').selectedIndex].text;
    const isCurrency = currentDataType === 'facturado';

    // Configuración base común
    const config = {
        type: chartType,
        data: {
            labels: labels,
            datasets: [{
                label: dataTypeLabel,
                data: chartData,
                backgroundColor: colorPalette.slice(0, labels.length),
                borderColor: borderColorPalette.slice(0, labels.length),
                borderWidth: 2,
                // Para gráficas de línea
                pointRadius: chartType === 'line' ? 4 : 3,
                pointHoverRadius: chartType === 'line' ? 6 : 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Cambiado a false para evitar cortes
            layout: {
                padding: {
                    top: 20,
                    right: chartType === 'radar' ? 50 : 20,
                    bottom: chartType === 'line' || chartType === 'bar' ? 50 : 20,
                    left: chartType === 'radar' ? 50 : 20
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: `${title} - ${dataTypeLabel}`,
                    font: { size: 14 }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${isCurrency ? formatCurrency(context.raw) : formatNumber(context.raw)}`;
                        }
                    }
                },
                legend: {
                    position: chartType === 'pie' || chartType === 'doughnut' || chartType === 'radar' ? 'right' : 'top',
                    labels: {
                        padding: 20,
                        font: { size: 12 },
                        generateLabels: function(chart) {
                            return chart.data.labels.map((label, i) => {
                                const value = chart.data.datasets[0].data[i];
                                const hidden = !chart.getDataVisibility(i);
                                
                                return {
                                    text: `${label}: ${isCurrency ? formatCurrency(value, true) : formatNumber(value)}`,
                                    fillStyle: chart.data.datasets[0].backgroundColor[i],
                                    hidden: hidden,
                                    index: i,
                                    font: {
                                        ...(hidden ? { textDecoration: 'line-through' } : {})
                                    }
                                };
                            });
                        }
                    }
                },
                datalabels: {
                    display: false
                }
            }
        }
    };

    // Configuración específica para gráficas circulares
    if (chartType === 'pie' || chartType === 'doughnut') {
        config.options.cutout = chartType === 'doughnut' ? '60%' : 0;
        config.options.plugins.legend.maxWidth = 300;
        config.options.aspectRatio = 1.5; // Ajuste para evitar cortes
    }

    // Configuración para gráfica de radar
    if (chartType === 'radar') {
        config.options.aspectRatio = 1.3;
        config.options.scales = {
            r: {
                beginAtZero: true,
                ticks: { display: false },
                pointLabels: {
                    font: {
                        size: 11,
                        weight: 'bold'
                    },
                    color: '#333'
                }
            }
        };
    }

    // Plugin mejorado para mostrar valores SOLO en barras (sin líneas)
    if (chartType === 'bar') {
        config.plugins = [{
            id: 'showValues',
            afterDatasetsDraw(chart, args, options) {
                const {ctx, data, chartArea: {top, bottom, left, right, width, height}} = chart;
                ctx.save();
                
                data.datasets.forEach((dataset, i) => {
                    chart.getDatasetMeta(i).data.forEach((datapoint, index) => {
                        if (!chart.getDataVisibility(index)) return;
                        
                        const {x, y} = datapoint.tooltipPosition();
                        const value = dataset.data[index];
                        const label = isCurrency ? formatCurrency(value, true) : formatNumber(value);
                        
                        // Solo para gráficas de barras - texto rotado
                        ctx.fillStyle = '#333';
                        ctx.font = 'bold 10px Arial';
                        ctx.textAlign = 'start';
                        ctx.textBaseline = 'middle';
                        
                        // Rotar el texto -45 grados para que se vea diagonal
                        ctx.translate(x, y - 10);
                        ctx.rotate(-Math.PI / 4);
                        ctx.fillText(label, 0, 0);
                        ctx.rotate(Math.PI / 4);
                        ctx.translate(-x, -(y - 10));
                    });
                });
                
                ctx.restore();
            }
        }];
    }

    return config;
}

function createNormalChart(data, chartType) {
    const dataTypeLabel = document.getElementById('dataType').options[document.getElementById('dataType').selectedIndex].text;
    const isCurrency = currentDataType === 'facturado';
    
    return {
        type: chartType,
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: data.comparison.period1_label,
                    data: data.comparison.period1_data,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: chartType === 'line' ? false : true
                },
                {
                    label: data.comparison.period2_label,
                    data: data.comparison.period2_data,
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 2,
                    fill: chartType === 'line' ? false : true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: `Comparación de ${dataTypeLabel}`,
                    font: {
                        size: 16
                    }
                },
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + (isCurrency ? formatCurrency(context.parsed.y || context.parsed) : formatNumber(context.parsed.y || context.parsed));
                        }
                    }
                },
                datalabels: {
                    display: false
                }
            },
            scales: chartType !== 'radar' ? {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return isCurrency ? formatCurrency(value, true) : formatNumber(value);
                        }
                    }
                }
            } : {}
        },
        plugins: [{
            id: 'showValues',
            afterDatasetsDraw(chart, args, options) {
                // Solo mostrar valores en gráficas de barras, NO en líneas
                if (chartType === 'bar') {
                    const {ctx, data, chartArea: {top, bottom, left, right, width, height}} = chart;
                    ctx.save();
                    
                    data.datasets.forEach((dataset, i) => {
                        chart.getDatasetMeta(i).data.forEach((datapoint, index) => {
                            const {x, y, base} = datapoint.tooltipPosition();
                            const value = dataset.data[index];
                            const label = isCurrency ? formatCurrency(value, true) : formatNumber(value);
                            
                            ctx.fillStyle = '#333';
                            ctx.font = 'bold 9px Arial';
                            ctx.textAlign = 'start';
                            ctx.textBaseline = 'middle';
                            
                            // Rotar el texto -45 grados
                            ctx.translate(x, y - 15);
                            ctx.rotate(-Math.PI / 4);
                            ctx.fillText(label, 0, 0);
                            ctx.rotate(Math.PI / 4);
                            ctx.translate(-x, -(y - 15));
                        });
                    });
                    
                    ctx.restore();
                }
            }
        }]
    };
}
function downloadChartAsImage() {
    const chartType = document.getElementById('chartType').value;
    const useDoubleChart = ['pie', 'doughnut', 'radar'].includes(chartType);
    
    if (useDoubleChart) {
        downloadDoubleChartAsImage();
    } else {
        downloadSingleChartAsImage();
    }
}

function downloadSingleChartAsImage() {
    if (!comparisonChart) return;
    
    const tempCanvas = document.createElement('canvas');
    const tempCtx = tempCanvas.getContext('2d');
    
    tempCanvas.width = comparisonChart.canvas.width;
    tempCanvas.height = comparisonChart.canvas.height;
    
    tempCtx.fillStyle = 'white';
    tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
    
    tempCtx.drawImage(comparisonChart.canvas, 0, 0);
    
    const link = document.createElement('a');
    link.download = 'comparacion-rips.png';
    link.href = tempCanvas.toDataURL('image/png', 1.0);
    link.click();
}

function downloadDoubleChartAsImage() {
    if (!comparisonChart1 || !comparisonChart2) return;
    
    const tempCanvas = document.createElement('canvas');
    const tempCtx = tempCanvas.getContext('2d');
    
    // Configurar canvas más grande para dos gráficas
    const chartWidth = comparisonChart1.canvas.width;
    const chartHeight = comparisonChart1.canvas.height;
    const padding = 40;
    const titleHeight = 30;
    
    tempCanvas.width = (chartWidth * 2) + (padding * 3);
    tempCanvas.height = chartHeight + (titleHeight * 2) + (padding * 2);
    
    // Fondo blanco
    tempCtx.fillStyle = 'white';
    tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
    
    // Configurar texto para títulos
    tempCtx.fillStyle = 'black';
    tempCtx.font = '16px Arial';
    tempCtx.textAlign = 'center';
    
    // Título 1
    const title1 = document.getElementById('chart1Title').textContent;
    tempCtx.fillText(title1, padding + (chartWidth / 2), padding + 20);
    
    // Título 2
    const title2 = document.getElementById('chart2Title').textContent;
    tempCtx.fillText(title2, padding + chartWidth + padding + (chartWidth / 2), padding + 20);
    
    // Dibujar gráficas
    tempCtx.drawImage(comparisonChart1.canvas, padding, padding + titleHeight);
    tempCtx.drawImage(comparisonChart2.canvas, padding + chartWidth + padding, padding + titleHeight);
    
    const link = document.createElement('a');
    link.download = 'comparacion-rips-doble.png';
    link.href = tempCanvas.toDataURL('image/png', 1.0);
    link.click();
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

function formatNumber(value) {
    return new Intl.NumberFormat('es-CO').format(value);
}
</script>

@endsection