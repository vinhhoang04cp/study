<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Course Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3498db;
            --success: #2ecc71;
            --danger: #e74c3c;
            --warning: #f39c12;
            --dark: #2c3e50;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, var(--dark) 0%, #34495e 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: bold;
        }

        .sidebar .nav-link {
            color: white;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--primary);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 20px 30px;
            margin: -30px -30px 30px -30px;
            border-bottom: 2px solid #ecf0f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
            color: var(--dark);
            margin: 0;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 15px 20px;
            font-weight: 600;
        }

        .btn-primary {
            background-color: var(--primary);
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 12px;
            font-weight: 600;
        }

        .badge.published {
            background-color: var(--success);
            color: white;
        }

        .badge.draft {
            background-color: var(--warning);
            color: white;
        }

        table {
            font-size: 14px;
        }

        table thead {
            background-color: #f8f9fa;
        }

        table th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: var(--dark);
        }

        .pagination {
            margin-top: 20px;
        }

        .alert {
            border: none;
            border-radius: 5px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid var(--danger);
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid var(--primary);
        }

        .form-control, .form-select {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px 15px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .stat-card .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: var(--primary);
            margin: 10px 0;
        }

        .stat-card .stat-label {
            color: #7f8c8d;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .main-content {
                margin-left: 200px;
                padding: 15px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2><i class="fas fa-graduation-cap"></i> CMS</h2>
            <nav class="nav flex-column">
                <a href="{{ route('dashboard') }}" class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('courses.index') }}" class="nav-link {{ Route::is('courses.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i> Khóa Học
                </a>
                <a href="{{ route('enrollments.index') }}" class="nav-link {{ Route::is('enrollments.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Đăng Ký Học
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @if($errors->any())
                @component('components.alert')
                    @slot('type', 'danger')
                    @slot('title', 'Lỗi')
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endcomponent
            @endif

            @if(session('success'))
                @component('components.alert')
                    @slot('type', 'success')
                    @slot('title', 'Thành Công')
                    {{ session('success') }}
                @endcomponent
            @endif

            @if(session('error'))
                @component('components.alert')
                    @slot('type', 'danger')
                    @slot('title', 'Lỗi')
                    {{ session('error') }}
                @endcomponent
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>

