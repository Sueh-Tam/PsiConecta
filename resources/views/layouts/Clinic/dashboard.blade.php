<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') - PsiConecta</title>
    <link rel="shortcut icon" type="imagex/png" href="./img/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 70px;
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: #0d6efd;
            color: white;
            padding: 1.5rem;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 1rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: #0b5ed7;
            color: white;
            transform: translateX(5px);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .header {
            height: var(--header-height);
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .system-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .logout-btn {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background-color: #dc3545;
            color: white;
        }

        .content {
            padding: 2rem;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <h1 class="h4 mb-0">
                <a href="{{ route('home') }}" class="text-white text-decoration-none d-flex align-items-center">
                    <i class="bi bi-brightness-high me-2"></i>
                    PsiConecta
                </a>
            </h1>
        </div>
        
        <a href="{{ Route('clinic.dashboard') }}" class="@if(request()->is('dashboard/consultas')) active @endif">
            <i class="bi bi-calendar-check"></i>
            Consultas
        </a>
        <a href="{{ Route('clinic.psychologist.index') }}">
            <i class="bi bi-people"></i>
            Psicólogos
        </a>
        @if (Auth::user()->isClinic())
            <a href="{{ Route('clinic.attendant.index') }}">
                <i class="bi bi-person-badge"></i>
                Atendentes
            </a>
        @endif
        @if (Auth::user()->isAttendant())
            <a href="{{ Route('clinic.patient.index') }}">
                <i class="bi bi-person-heart"></i>
                Pacientes
            </a>
        @endif
    </nav>

    <!-- Conteúdo Principal -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="system-info">
                <div class="user-info">
                    <div class="avatar">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div>
                        <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                        <small class="text-muted">
                        @php
                        switch (Auth::user()->type) {
                            case 'clinic':
                                echo 'Clínica';
                                break;
                            case 'psychologist':
                                echo 'Psicólogo';
                                break;
                            case 'attendant':
                                echo 'Atendente';
                                break;
                            default:
                                echo 'Não definido';
                                break;
                        } 
                        @endphp
                        </small>
                    </div>
                </div>
            </div>

            <a href="{{ Route('auth.logout') }}" class="btn btn-outline-danger logout-btn">
                <i class="bi bi-box-arrow-right"></i>
                Sair
            </a>
        </div>

        <!-- Conteúdo Principal -->
        <main class="content">
            <h2 class="mb-4">@yield('title')</h2>
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
