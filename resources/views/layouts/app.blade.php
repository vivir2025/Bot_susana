<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard RIPS - Hospital Susana López de Valencia')</title>
        
    <link rel="shortcut icon" href="{{ url('susana/public/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ url('susana/public/favicon.ico') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        :root {
            --sidebar-width: 250px;
            --navbar-height: 56px;
        }
        
        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(180deg, #2c3e50, #3498db);
            color: white;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }
        
        .content-wrapper {
            flex: 1;
            padding: 20px;
            margin-top: var(--navbar-height);
        }
        
        .navbar-main {
            width: calc(100% - var(--sidebar-width));
            position: fixed;
            top: 0;
            right: 0;
            z-index: 999;
            transition: all 0.3s;
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
        
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
            border-radius: 5px;
            transition: all 0.2s;
        }
        
        .nav-link:hover, .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link.active {
            font-weight: bold;
        }

        /* Para pantallas pequeñas */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content, .navbar-main {
                width: 100%;
                margin-left: 0;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    @auth
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-3">
            <h4 class="text-center mb-4">
                <i class="fas fa-hospital me-2"></i>
                Hospital SLV
            </h4>
            <hr>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}" class="nav-link @if(request()->routeIs('dashboard.index')) active @endif">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ripsdata.index') }}" class="nav-link @if(request()->routeIs('ripsdata.*')) active @endif">
                        <i class="fas fa-file-invoice me-2"></i> Datos RIPS
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-dark bg-primary navbar-main">
            <div class="container-fluid">
                <button class="btn btn-link text-white d-md-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <span class="navbar-brand">
                    <i class="fas fa-chart-line me-2"></i> Panel de Control RIPS
                </span>
                
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">
                        <i class="fas fa-user-circle me-2"></i> {{ Auth::user()->name }}
                    </span>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="btn btn-outline-light">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="content-wrapper">
            @include('layouts.partials.alerts')
            @yield('content')
        </div>
    </div>
    @else
    <!-- Contenido para usuarios no autenticados -->
    <main>
        @yield('content')
    </main>
    @endauth

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Toggle sidebar en móviles
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    
    @yield('scripts')
</body>
</html>