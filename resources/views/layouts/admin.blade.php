<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Face Attendance System</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* 60-30-10 Color Rule with Pastel Colors */
            /* 60% - Background & Base */
            --color-bg-primary: #f8f9fc;
            --color-bg-secondary: #ffffff;
            --color-bg-tertiary: #eef2f7;
            
            /* 30% - Secondary/Accent */
            --color-pastel-blue: #a8d5e5;
            --color-pastel-purple: #d4b5e9;
            --color-pastel-pink: #f5c2d9;
            
            /* 10% - Primary/CTA */
            --color-primary: #6366f1;
            --color-primary-dark: #4f46e5;
            --color-primary-light: #818cf8;
            
            /* Status Colors */
            --color-success: #86d4a9;
            --color-warning: #fcd779;
            --color-danger: #f5a8a8;
            --color-info: #a8d5e5;
            
            /* Text Colors */
            --color-text-primary: #1e293b;
            --color-text-secondary: #64748b;
            --color-text-muted: #94a3b8;
            
            /* Others */
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--color-bg-primary);
            color: var(--color-text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, var(--color-bg-secondary) 0%, #f5f3ff 100%);
            border-right: 1px solid var(--color-bg-tertiary);
            padding: 24px;
            z-index: 100;
            transition: var(--transition);
            overflow-y: auto;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--color-bg-tertiary);
        }

        .sidebar-logo {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-pastel-purple) 100%);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--color-text-primary);
        }

        .sidebar-subtitle {
            font-size: 12px;
            color: var(--color-text-muted);
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--color-text-muted);
            margin-bottom: 12px;
            padding-left: 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: var(--border-radius);
            color: var(--color-text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            margin-bottom: 4px;
        }

        .nav-link:hover {
            background: var(--color-bg-tertiary);
            color: var(--color-primary);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: var(--transition);
        }

        /* Top Header */
        .top-header {
            background: var(--color-bg-secondary);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--color-bg-tertiary);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--color-text-muted);
        }

        .breadcrumb a {
            color: var(--color-text-secondary);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb a:hover {
            color: var(--color-primary);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: var(--color-bg-tertiary);
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
        }

        .user-menu:hover {
            background: var(--color-bg-primary);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--color-pastel-purple) 0%, var(--color-pastel-pink) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Page Content */
        .page-content {
            padding: 32px;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: 8px;
        }

        .page-description {
            color: var(--color-text-secondary);
        }

        /* Cards */
        .card {
            background: var(--color-bg-secondary);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--color-bg-tertiary);
            overflow: hidden;
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--color-bg-tertiary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
        }

        .card-body {
            padding: 24px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--color-bg-secondary);
            border-radius: var(--border-radius-lg);
            padding: 24px;
            border: 1px solid var(--color-bg-tertiary);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--color-primary) 0%, var(--color-pastel-purple) 100%);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card.success::before { background: linear-gradient(90deg, var(--color-success) 0%, #a8e6cf 100%); }
        .stat-card.warning::before { background: linear-gradient(90deg, var(--color-warning) 0%, #ffe066 100%); }
        .stat-card.danger::before { background: linear-gradient(90deg, var(--color-danger) 0%, #ffb3b3 100%); }
        .stat-card.info::before { background: linear-gradient(90deg, var(--color-info) 0%, #b3e5fc 100%); }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
        }

        .stat-card .stat-icon { background: rgba(99, 102, 241, 0.1); color: var(--color-primary); }
        .stat-card.success .stat-icon { background: rgba(134, 212, 169, 0.2); color: #22c55e; }
        .stat-card.warning .stat-icon { background: rgba(252, 215, 121, 0.2); color: #f59e0b; }
        .stat-card.danger .stat-icon { background: rgba(245, 168, 168, 0.2); color: #ef4444; }
        .stat-card.info .stat-icon { background: rgba(168, 213, 229, 0.2); color: #0ea5e9; }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--color-text-secondary);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--color-bg-tertiary);
            color: var(--color-text-primary);
        }

        .btn-secondary:hover {
            background: var(--color-bg-primary);
        }

        .btn-success {
            background: linear-gradient(135deg, #22c55e 0%, var(--color-success) 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, var(--color-danger) 100%);
            color: white;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--color-bg-tertiary);
        }

        th {
            font-weight: 600;
            color: var(--color-text-secondary);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: var(--color-bg-primary);
        }

        tr:hover td {
            background: var(--color-bg-primary);
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--color-text-primary);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--color-bg-tertiary);
            border-radius: var(--border-radius);
            font-size: 14px;
            font-family: inherit;
            transition: var(--transition);
            background: var(--color-bg-secondary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        select.form-control {
            cursor: pointer;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success { background: rgba(134, 212, 169, 0.2); color: #15803d; }
        .badge-warning { background: rgba(252, 215, 121, 0.2); color: #b45309; }
        .badge-danger { background: rgba(245, 168, 168, 0.2); color: #dc2626; }
        .badge-info { background: rgba(168, 213, 229, 0.2); color: #0369a1; }
        .badge-secondary { background: var(--color-bg-tertiary); color: var(--color-text-secondary); }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success { background: rgba(134, 212, 169, 0.2); color: #15803d; border-left: 4px solid #22c55e; }
        .alert-error { background: rgba(245, 168, 168, 0.2); color: #dc2626; border-left: 4px solid #ef4444; }
        .alert-warning { background: rgba(252, 215, 121, 0.2); color: #b45309; border-left: 4px solid #f59e0b; }
        .alert-info { background: rgba(168, 213, 229, 0.2); color: #0369a1; border-left: 4px solid #0ea5e9; }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 24px;
        }

        .pagination a, .pagination span {
            padding: 8px 14px;
            border-radius: var(--border-radius);
            font-size: 14px;
            text-decoration: none;
            transition: var(--transition);
        }

        .pagination a {
            background: var(--color-bg-secondary);
            color: var(--color-text-secondary);
            border: 1px solid var(--color-bg-tertiary);
        }

        .pagination a:hover {
            background: var(--color-primary);
            color: white;
            border-color: var(--color-primary);
        }

        .pagination .active span {
            background: var(--color-primary);
            color: white;
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--color-text-primary);
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .page-content {
                padding: 16px;
            }

            .top-header {
                padding: 12px 16px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .card-body {
                padding: 16px;
            }

            th, td {
                padding: 12px 8px;
                font-size: 13px;
            }

            .btn {
                padding: 8px 16px;
            }
        }

        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 64px;
            color: var(--color-pastel-purple);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 20px;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--color-text-secondary);
            margin-bottom: 20px;
        }

        /* Avatar */
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background: linear-gradient(135deg, var(--color-pastel-blue) 0%, var(--color-pastel-purple) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Face Status Badge */
        .face-status {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .face-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .face-status-dot.registered {
            background: #22c55e;
        }

        .face-status-dot.not-registered {
            background: #ef4444;
        }

        /* Action Buttons Group */
        .action-buttons {
            display: flex;
            gap: 8px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-fingerprint"></i>
            </div>
            <div>
                <div class="sidebar-title">Face Attend</div>
                <div class="sidebar-subtitle">Sistem Absensi Wajah</div>
            </div>
        </div>

        <nav>
            <div class="nav-section">
                <div class="nav-section-title">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('attendance.index') }}" class="nav-link" target="_blank">
                    <i class="fas fa-camera"></i>
                    <span>Halaman Absensi</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Manajemen</div>
                <a href="{{ route('admin.employees.index') }}" class="nav-link {{ request()->routeIs('admin.employees*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Karyawan</span>
                </a>
                <a href="{{ route('admin.work-schedules.index') }}" class="nav-link {{ request()->routeIs('admin.work-schedules*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span>Jadwal Kerja</span>
                </a>
                <a href="{{ route('admin.departments.index') }}" class="nav-link {{ request()->routeIs('admin.departments*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>Departemen</span>
                </a>
                <a href="{{ route('admin.positions.index') }}" class="nav-link {{ request()->routeIs('admin.positions*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span>Jabatan</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Laporan</div>
                <a href="{{ route('admin.payrolls.index') }}" class="nav-link {{ request()->routeIs('admin.payrolls*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Payroll / Gaji</span>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan Kehadiran</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Akun</div>
                <a href="{{ route('profile.edit') }}" class="nav-link">
                    <i class="fas fa-user-cog"></i>
                    <span>Profil</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-link" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div style="display: flex; align-items: center; gap: 16px;">
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="breadcrumb">
                    @yield('breadcrumb')
                </div>
            </div>
            <div class="header-actions">
                <div class="user-menu">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 500; font-size: 14px;">{{ Auth::user()->name }}</div>
                        <div style="font-size: 12px; color: var(--color-text-muted);">Administrator</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="page-content">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    {{ session('info') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        // Mobile Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mobileToggle = document.getElementById('mobileToggle');

        mobileToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });

        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    </script>
    @stack('scripts')
</body>
</html>
