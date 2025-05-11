<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>PsiConecta - Conectando Você ao Bem-Estar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,123,255,0.9), rgba(0,123,255,0.7));
            padding: 4rem 0;
            margin-bottom: 3rem;
        }
        .clinic-card {
            transition: transform 0.3s ease;
        }
        .clinic-card:hover {
            transform: translateY(-5px);
        }
        .carousel-item img {
            object-fit: cover;
            height: 400px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="bg-primary text-white py-3 shadow sticky-top">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0"><a href="{{ route('home') }}" class="text-white text-decoration-none">PsiConecta</a></h1>
            <div>

                @if (Auth::check() && Auth::user()->isPatient())
                    <a href="{{ Route('patient.dashboard') }}" class="btn btn-outline-light me-2">Dashboard</a>
                    <a href="{{ Route('auth.logout') }}" class="btn btn-light text-primary">Logout</a>
                @elseif (Auth::check() && (Auth::user()->isClinic() || Auth::user()->isAttendant()))
                    <a href="{{ Route('clinic.dashboard') }}" class="btn btn-outline-light me-2">Dashboard</a>
                    <a href="{{ Route('auth.logout') }}" class="btn btn-light text-primary">Logout</a>
                @elseif (Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ Route('admin.dashboard') }}" class="btn btn-outline-light me-2">Dashboard admin</a>
                    <a href="{{ Route('auth.logout') }}" class="btn btn-light text-primary">Logout</a>
                @elseif (Auth::check() && Auth::user()->isPsychologist())
                    <a href="{{ Route('psychologist.dashboard') }}" class="btn btn-outline-light me-2">Dashboard</a>
                    <a href="{{ Route('auth.logout') }}" class="btn btn-light text-primary">Logout</a>
                @else
                    <a href="{{ Route('user.signup') }}" class="btn btn-outline-light me-2">Registre-se</a>
                    <a href="{{ Route('login') }}" class="btn btn-light text-primary">Login</a>
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

    <!-- Hero Section -->
    <section class="hero-section text-white text-center">
        <div class="container">
            <h1 class="display-4 mb-4">Bem-vindo ao PsiConecta</h1>
            <p class="lead mb-4">Encontre o profissional ideal para cuidar da sua saúde mental</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ Route('user.signup') }}" class="btn btn-light btn-lg">Começar Agora</a>
                <a href="#clinicas" class="btn btn-outline-light btn-lg">Ver Clínicas</a>
            </div>
        </div>
    </section>

    <main class="container">
        <!-- Slider de Clínicas -->
        <section class="mb-5">
            <div id="clinicasCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#clinicasCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#clinicasCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#clinicasCarousel" data-bs-slide-to="2"></button>
                </div>
                <div class="carousel-inner rounded shadow">
                    <div class="carousel-item active">
                        <img src="https://via.placeholder.com/1200x400?text=Clínica+Bem+Estar" class="d-block w-100" alt="Clínica Bem Estar">
                    </div>
                    <div class="carousel-item">
                        <img src="https://via.placeholder.com/1200x400?text=Clínica+Saúde+Mental" class="d-block w-100" alt="Clínica Saúde Mental">
                    </div>
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
        </section>

        <!-- Lista de Clínicas -->
        <section id="clinicas" class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Clínicas Parceiras</h2>
                <a href="{{ Route('clinic.signup') }}" class="btn btn-outline-primary">
                    <i class="bi bi-building-add"></i> Seja um Parceiro
                </a>
            </div>

            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card h-100 shadow-sm clinic-card">
                        <div class="position-relative">
                            <img src="https://via.placeholder.com/400x200?text=Clínica+Bem+Estar" class="card-img-top" alt="Clínica Bem Estar">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-primary">Destaque</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Clínica Bem Estar</h5>
                            <p class="card-text">
                                <i class="bi bi-geo-alt text-primary"></i> Centro da cidade<br>
                                <i class="bi bi-star-fill text-warning"></i> 4.8/5 (120 avaliações)
                            </p>
                            <a href="/clinicas/1/psicologos" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Ver Psicólogos
                            </a>
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
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>PsiConecta</h5>
                    <p class="text-muted">Conectando você aos melhores profissionais de saúde mental.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Links Úteis</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50">Sobre Nós</a></li>
                        <li><a href="#" class="text-white-50">Como Funciona</a></li>
                        <li><a href="#" class="text-white-50">Termos de Uso</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Contato</h5>
                    <ul class="list-unstyled text-white-50">
                        <li><i class="bi bi-envelope"></i> contato@psiconecta.com</li>
                        <li><i class="bi bi-telephone"></i> (11) 9999-9999</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-light">
            <p class="text-center mb-0">&copy; {{ date('Y') }} PsiConecta. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
