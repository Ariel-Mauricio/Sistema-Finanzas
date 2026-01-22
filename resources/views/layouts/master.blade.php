<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FinanzaPro - @yield('title', 'Sistema de Gestión Financiera')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #4F46E5;
            --primary-dark: #4338CA;
            --primary-light: #818CF8;
            --secondary: #0F172A;
            --success: #10B981;
            --success-light: #D1FAE5;
            --danger: #EF4444;
            --danger-light: #FEE2E2;
            --warning: #F59E0B;
            --warning-light: #FEF3C7;
            --info: #3B82F6;
            --info-light: #DBEAFE;
            --gray-50: #F8FAFC;
            --gray-100: #F1F5F9;
            --gray-200: #E2E8F0;
            --gray-300: #CBD5E1;
            --gray-400: #94A3B8;
            --gray-500: #64748B;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1E293B;
            --gray-900: #0F172A;
            --sidebar-width: 280px;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--gray-100);
            color: var(--gray-900);
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--secondary) 0%, #1E293B 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .sidebar-brand-text {
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .sidebar-brand-text span {
            color: var(--primary-light);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            padding: 0.5rem 1.5rem;
            margin-top: 0.5rem;
        }

        .nav-section-title {
            color: var(--gray-500);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
        }

        .nav-item {
            margin: 0.125rem 0.75rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--gray-400);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1rem;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Header */
        .main-header {
            position: sticky;
            top: 0;
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 100;
        }

        .header-search {
            position: relative;
            width: 350px;
        }

        .header-search input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .header-search input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .header-search i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-btn {
            width: 42px;
            height: 42px;
            border: none;
            background: var(--gray-100);
            border-radius: 10px;
            color: var(--gray-600);
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .header-btn:hover {
            background: var(--gray-200);
            color: var(--gray-900);
        }

        .header-btn .badge {
            position: absolute;
            top: -3px;
            right: -3px;
            width: 18px;
            height: 18px;
            background: var(--danger);
            color: white;
            font-size: 0.65rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .user-dropdown:hover {
            background: var(--gray-100);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }

        .user-info {
            line-height: 1.2;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--gray-900);
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        /* Main Content Area */
        .main-content {
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: var(--gray-500);
            font-size: 0.95rem;
        }

        /* Cards */
        .card {
            background: white;
            border: none;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--gray-100);
            padding: 1.25rem 1.5rem;
        }

        .card-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--gray-900);
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stat Cards */
        .stat-card {
            padding: 1.5rem;
            border-radius: 16px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-card-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .stat-card-success {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
        }

        .stat-card-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #DC2626 100%);
            color: white;
        }

        .stat-card-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #D97706 100%);
            color: white;
        }

        .stat-card-info {
            background: linear-gradient(135deg, var(--info) 0%, #2563EB 100%);
            color: white;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.8rem;
            margin-top: 0.5rem;
            padding: 0.25rem 0.5rem;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
        }

        /* Tables */
        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-modern thead th {
            background: var(--gray-50);
            color: var(--gray-600);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .table-modern tbody td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .table-modern tbody tr:hover {
            background: var(--gray-50);
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 600;
            border-radius: 10px;
            padding: 0.65rem 1.25rem;
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
        }

        /* Status Badges */
        .badge-status {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background: var(--success-light);
            color: var(--success);
        }

        .badge-danger {
            background: var(--danger-light);
            color: var(--danger);
        }

        .badge-warning {
            background: var(--warning-light);
            color: var(--warning);
        }

        .badge-info {
            background: var(--info-light);
            color: var(--info);
        }

        /* Forms */
        .form-control, .form-select {
            border: 1px solid var(--gray-300);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        .form-label {
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
        }

        .alert-success {
            background: var(--success-light);
            color: #065F46;
        }

        .alert-danger {
            background: var(--danger-light);
            color: #991B1B;
        }

        .alert-warning {
            background: var(--warning-light);
            color: #92400E;
        }

        /* Quick Actions */
        .quick-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem 1rem;
            background: white;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid var(--gray-200);
        }

        .quick-action:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: var(--primary);
        }

        .quick-action-icon {
            width: 56px;
            height: 56px;
            background: var(--gray-100);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            transition: all 0.2s ease;
        }

        .quick-action:hover .quick-action-icon {
            background: var(--primary);
            color: white;
        }

        .quick-action-text {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--gray-700);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.4s ease forwards;
        }

        /* Dropdown menu styles */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: var(--gray-100);
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 0.5rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="sidebar-brand-text">
                    Finanza<span>Pro</span>
                </div>
            </a>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Principal</div>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </div>

            @auth
                @if(Auth::user()->isAdmin() || Auth::user()->role === 'contador')
                    <div class="nav-section">
                        <div class="nav-section-title">Operaciones</div>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('comprobantes.index') }}" class="nav-link {{ request()->routeIs('comprobantes.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar"></i>
                            Comprobantes
                            @php $pendientes = \App\Models\Comprobante::whereDate('created_at', today())->count(); @endphp
                            @if($pendientes > 0)
                                <span class="nav-badge">{{ $pendientes }}</span>
                            @endif
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('egresos.index') }}" class="nav-link {{ request()->routeIs('egresos.*') ? 'active' : '' }}">
                            <i class="fas fa-receipt"></i>
                            Egresos
                        </a>
                    </div>

                    <div class="nav-section">
                        <div class="nav-section-title">Finanzas</div>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('metodos-pago.index') }}" class="nav-link {{ request()->routeIs('metodos-pago.*') ? 'active' : '' }}">
                            <i class="fas fa-credit-card"></i>
                            Métodos de Pago
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('reportes.index') }}" class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            Reportes
                        </a>
                    </div>

                    @if(Auth::user()->isAdmin())
                    <div class="nav-section">
                        <div class="nav-section-title">Administración</div>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('configuracion.index') }}" class="nav-link {{ request()->routeIs('configuracion.*') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i>
                            Configuración
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('configuracion.usuarios') }}" class="nav-link {{ request()->routeIs('configuracion.usuarios*') ? 'active' : '' }}">
                            <i class="fas fa-user-shield"></i>
                            Usuarios
                        </a>
                    </div>
                    @endif
                @endif
            @endauth
        </nav>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <header class="main-header">
            <div class="header-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar comprobantes, egresos..." id="globalSearch">
            </div>

            <div class="header-actions">
                <button class="header-btn" title="Notificaciones">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </button>

                <button class="header-btn" title="Ayuda">
                    <i class="fas fa-question-circle"></i>
                </button>

                @auth
                <div class="dropdown">
                    <div class="user-dropdown" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="user-info d-none d-md-block">
                            <div class="user-name">{{ Auth::user()->name }}</div>
                            <div class="user-role">
                                @if(Auth::user()->isAdmin())
                                    Administrador
                                @elseif(Auth::user()->role === 'contador')
                                    Contador
                                @else
                                    Usuario
                                @endif
                            </div>
                        </div>
                        <i class="fas fa-chevron-down ms-2 text-muted"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user"></i>
                                Mi Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('configuracion.index') }}">
                                <i class="fas fa-cog"></i>
                                Configuración
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="py-4 px-4 text-center text-muted" style="font-size: 0.85rem;">
            <p class="mb-0">
                © {{ date('Y') }} FinanzaPro - Sistema de Gestión Financiera Profesional
            </p>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Global Search -->
    <script>
        document.getElementById('globalSearch')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value;
                if (query.length > 2) {
                    window.location.href = `/buscar?q=${encodeURIComponent(query)}`;
                }
            }
        });

        // Sidebar Toggle for Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
