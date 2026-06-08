<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MYPOLBENG') - Politeknik Negeri Bengkalis</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-bg: #f5f7fb;
            --sidebar-bg: #0f172a;
            --sidebar-active: #1e293b;
            --accent-color: #2563eb;
            --accent-hover: #1d4ed8;
            --text-main: #334155;
            --text-muted: #64748b;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            --border-radius: 14px;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            background-color: var(--sidebar-bg);
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s ease;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
            padding: 0 0.5rem;
        }

        .sidebar-brand span {
            color: #38bdf8;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex-grow: 1;
        }

        .nav-menu-item a {
            color: #94a3b8;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.8rem 1rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-menu-item a:hover {
            color: #fff;
            background-color: var(--sidebar-active);
            transform: translateX(4px);
        }

        .nav-menu-item.active a {
            color: #fff;
            background-color: var(--accent-color);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .sidebar-footer {
            border-top: 1px solid #1e293b;
            padding-top: 1.5rem;
            margin-top: auto;
        }

        /* Main Content Container */
        .main-wrapper {
            margin-left: 260px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        /* Header Navbar */
        .top-navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 0.8rem 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }

        /* Cards & Components Styling */
        .glass-card {
            background: white;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.08);
        }

        .btn-custom-primary {
            background-color: var(--accent-color);
            color: white;
            border-radius: 10px;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            border: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }

        .btn-custom-primary:hover {
            background-color: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.3);
            color: white;
        }

        /* Micro-animations */
        .hover-scale {
            transition: transform 0.2s ease;
        }
        .hover-scale:hover {
            transform: scale(1.02);
        }

        .table-responsive {
            border-radius: var(--border-radius);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--text-muted);
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Status Badges */
        .badge-role {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 30px;
        }
        
        .role-admin { background-color: #fee2e2; color: #ef4444; }
        .role-dosen { background-color: #fef3c7; color: #d97706; }
        .role-mahasiswa { background-color: #dbeafe; color: #2563eb; }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('logo_polbeng.jpg') }}" alt="Logo POLBENG" style="height: 36px; width: 36px; object-fit: cover; border-radius: 50%; margin-right: 10px; background-color: #ffffff; padding: 2px;">
            <div>MY<span>POLBENG</span></div>
        </div>

        <ul class="nav-menu">
            <li class="nav-menu-item {{ Route::is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-fill"></i>
                    Dashboard
                </a>
            </li>

            @if(Auth::user()->isAdmin())
                <li class="nav-menu-item {{ Route::is('admin.users*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users') }}">
                        <i class="bi bi-people-fill"></i>
                        Manajemen User
                    </a>
                </li>
                <li class="nav-menu-item {{ Route::is('admin.dosen*') ? 'active' : '' }}">
                    <a href="{{ route('admin.dosen') }}">
                        <i class="bi bi-person-workspace"></i>
                        Data Dosen
                    </a>
                </li>
                <li class="nav-menu-item {{ Route::is('admin.mahasiswa*') ? 'active' : '' }}">
                    <a href="{{ route('admin.mahasiswa') }}">
                        <i class="bi bi-mortarboard-fill"></i>
                        Data Mahasiswa
                    </a>
                </li>
                <li class="nav-menu-item {{ Route::is('admin.courses*') ? 'active' : '' }}">
                    <a href="{{ route('admin.courses') }}">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        Mata Kuliah
                    </a>
                </li>
                <li class="nav-menu-item {{ Route::is('admin.jadwal*') ? 'active' : '' }}">
                    <a href="{{ route('admin.jadwal') }}">
                        <i class="bi bi-calendar-event-fill"></i>
                        Jadwal Kuliah
                    </a>
                </li>
                <li class="nav-menu-item {{ Route::is('admin.logs*') ? 'active' : '' }}">
                    <a href="{{ route('admin.logs') }}">
                        <i class="bi bi-activity"></i>
                        Log Aktivitas
                    </a>
                </li>
                <li class="nav-menu-item {{ Route::is('admin.backups*') ? 'active' : '' }}">
                    <a href="{{ route('admin.backups') }}">
                        <i class="bi bi-database-fill-gear"></i>
                        Backup Database
                    </a>
                </li>
            @elseif(Auth::user()->isDosen())
                <li class="nav-menu-item {{ Route::is('dosen.classes*') || Route::is('dosen.grades*') ? 'active' : '' }}">
                    <a href="{{ route('dosen.classes') }}">
                        <i class="bi bi-mortarboard-fill"></i>
                        Kelas Mengajar
                    </a>
                </li>
            @elseif(Auth::user()->isMahasiswa())
                <li class="nav-menu-item {{ Route::is('mahasiswa.krs*') ? 'active' : '' }}">
                    <a href="{{ route('mahasiswa.krs') }}">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        Isi KRS
                    </a>
                </li>
                <li class="nav-menu-item {{ Route::is('mahasiswa.khs*') ? 'active' : '' }}">
                    <a href="{{ route('mahasiswa.khs') }}">
                        <i class="bi bi-award-fill"></i>
                        KHS / Nilai
                    </a>
                </li>
            @endif
        </ul>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-wrapper">
        
        <!-- Header Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h4 class="mb-0 fw-bold">@yield('page_title', 'Dashboard')</h4>
            </div>

            <div class="user-profile">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                    <span class="badge badge-role role-{{ Auth::user()->role }}">{{ Auth::user()->role }}</span>
                </div>
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

        <!-- Message Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="submit" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="submit" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="submit" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Dynamic Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar Toggle for Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
