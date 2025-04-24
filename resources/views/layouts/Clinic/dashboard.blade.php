<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') - PsiConecta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            background-color: #0d6efd;
            color: white;
            padding: 1rem;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: #0b5ed7;
        }

        .content {
            padding: 2rem;
            width: 100%;
        }

        .header {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .system-info {
            display: flex;
            flex-direction: column;
        }

        .system-info h5 {
            margin: 0;
            font-weight: bold;
        }

        .system-info small {
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar">
        <h4 class="text-white mb-4">PsiConecta</h4>
        <a href="{{ Route('clinic.dashboard') }}" class="@if(request()->is('dashboard/consultas')) active @endif">Consultas</a>
        <a href="{{ Route('clinic.psychologist.index') }}" >Psicólogos</a>
        <a href="{{ Route('clinic.attendant.index') }}" >Atendentes</a>
    </nav>

    <!-- Conteúdo -->
    <div class="w-100">
        <!-- Header -->
        <div class="header">
            <div class="system-info">
                <h3>Usuário: {{ Auth::user()->name }}</h3>
            </div>
            <form method="POST" >
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
            </form>
        </div>

        <main class="content">
            <h2 class="mb-4">@yield('title')</h2>
            @yield('content')
        </main>
    </div>
</div>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
