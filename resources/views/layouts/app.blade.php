<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard RIPS - Hospital Susana López de Valencia')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Asegúrate de tener esto en tu layout -->
<meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50, #3498db);
            color: white;
        }
        .card-header {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-hospital me-2"></i>
                        Hospital SLV
                    </h4>
                    <hr>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a href="/dashboard" class="nav-link active">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white">
                                <i class="fas fa-file-invoice me-2"></i> Reportes RIPS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white">
                                <i class="fas fa-chart-bar me-2"></i> Estadísticas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white">
                                <i class="fas fa-cog me-2"></i> Configuración
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 ms-auto p-0">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                    <div class="container-fluid">
                        <span class="navbar-brand">
                            <i class="fas fa-chart-line me-2"></i> Panel de Control RIPS
                        </span>
                        <div class="d-flex align-items-center">
                            <span class="text-white me-3">
                                <i class="fas fa-user-circle me-2"></i> Administrador
                            </span>
                            <button class="btn btn-outline-light">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <main class="p-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    @yield('scripts')
    
    <script>
        // Inicializar gráfico de facturación
        document.addEventListener('DOMContentLoaded', function() {
            const facturacionData = {!! json_encode($facturacionData ?? []) !!};
            
            if (facturacionData.length > 0) {
                const ctx = document.getElementById('facturacionChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: facturacionData.map(item => item.regimen),
                        datasets: [{
                            data: facturacionData.map(item => item.total),
                            backgroundColor: [
                                '#3498db',
                                '#2ecc71',
                                '#e74c3c',
                                '#f39c12',
                                '#9b59b6'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return ` ${context.label}: $${context.raw.toLocaleString()}`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>