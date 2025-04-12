<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') - Psiconecta</title>
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
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar">
        <h4 class="text-white mb-4">Psiconecta</h4>
        <a href="/dashboard/consultas" class="@if(request()->is('dashboard/consultas')) active @endif">Consultas</a>
        <a href="{{ Route('patient.profile') }}" class="@if(request()->is('dashboard/perfil')) active @endif">Meu Perfil</a>
    </nav>

    <!-- Conteúdo -->
    <main class="content">
        <h2 class="mb-4">@yield('title')</h2>
        @yield('content')
    </main>
</div>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
