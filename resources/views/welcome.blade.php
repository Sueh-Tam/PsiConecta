<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Página Inicial - Sistema de Clínicas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS (via CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Header -->
    <header class="bg-primary text-white py-3 shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">PsiConecta</h1>
            <div>
                @if (Auth::check() && Auth::user()->isPatient())
                    <a href="{{ Route('patient.dashboard') }}" class="btn btn-outline-light me-2">Dashboard</a>
                    <a href="{{ Route('patient.logout') }}" class="btn btn-light text-primary">Logout</a>
                @elseif (Auth::check() && Auth::user()->isClinic())
                    <a href="{{ Route('clinic.dashboard') }}" class="btn btn-outline-light me-2">Dashboard</a>
                    <a href="{{ Route('clinic.logout') }}" class="btn btn-light text-primary">Logout</a>
                @elseif (Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ Route('admin.dashboard') }}" class="btn btn-outline-light me-2">Dashboard admin</a>
                    <a href="{{ Route('admin.logout') }}" class="btn btn-light text-primary">Logout</a>
                @else
                    <a href="{{ Route('user.signup') }}" class="btn btn-outline-light me-2">Cadastre-se</a>
                    <a href="{{ Route('user.login') }}" class="btn btn-light text-primary">Login</a>
                @endif

            </div>
        </div>
    </header>
    <x-error-modal
        modal-id="patientErrorModal"
        title="Erro"
    />
    <x-success-modal
        modal-id="patientSuccessModal"
        title="Cadastro Realizado!"
        message="{{ session('success_message') }}"
    />
    <!-- Body -->
    <main class="container mt-5">

        <!-- Slider de Clínicas -->
        <div id="clinicasCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <img src="https://via.placeholder.com/1200x400?text=Clínica+Bem+Estar" class="d-block w-100" alt="Clínica Bem Estar">
                </div>
                <!-- Slide 2 -->
                <div class="carousel-item">
                    <img src="https://via.placeholder.com/1200x400?text=Clínica+Saúde+Mental" class="d-block w-100" alt="Clínica Saúde Mental">
                </div>
                <!-- Slide 3 -->
                <div class="carousel-item">
                    <img src="https://via.placeholder.com/1200x400?text=Clínica+Viva+Melhor" class="d-block w-100" alt="Clínica Viva Melhor">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#clinicasCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#clinicasCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Próximo</span>
            </button>
        </div>

        <!-- Lista de Clínicas -->
        <h2 class="mb-4">Clínicas Disponíveis</h2>
        <label>Quer se tornar um parceiro? <a href="{{ Route('clinic.signup') }}">Clique aqui</a></label>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <!-- Clínica 1 -->
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="https://via.placeholder.com/400x200?text=Clínica+Bem+Estar" class="card-img-top" alt="Clínica Bem Estar">
                    <div class="card-body">
                        <h5 class="card-title">Clínica Bem Estar</h5>
                        <p class="card-text">Localizada no centro da cidade, com especialistas renomados.</p>
                        <a href="/clinicas/1/psicologos" class="btn btn-primary">Ver Psicólogos</a>
                    </div>
                </div>
            </div>

            <!-- Clínica 2 -->
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="https://via.placeholder.com/400x200?text=Clínica+Saúde+Mental" class="card-img-top" alt="Clínica Saúde Mental">
                    <div class="card-body">
                        <h5 class="card-title">Clínica Saúde Mental</h5>
                        <p class="card-text">Equipe dedicada a cuidar do seu bem-estar emocional.</p>
                        <a href="/clinicas/2/psicologos" class="btn btn-primary">Ver Psicólogos</a>
                    </div>
                </div>
            </div>

            <!-- Clínica 3 -->
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="https://via.placeholder.com/400x200?text=Clínica+Viva+Melhor" class="card-img-top" alt="Clínica Viva Melhor">
                    <div class="card-body">
                        <h5 class="card-title">Clínica Viva Melhor</h5>
                        <p class="card-text">Ambiente acolhedor com profissionais experientes.</p>
                        <a href="/clinicas/3/psicologos" class="btn btn-primary">Ver Psicólogos</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Sistema de Clínicas. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS (via CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
