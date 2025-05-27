<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>PsiConecta - Conectando Você ao Bem-Estar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --font-size: 16px;
        }
        * {
            font-size: var(--font-size);
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
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
        .font-control {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        .font-control button {
            margin: 0 5px;
            padding: 5px 10px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 3px;
            cursor: pointer;
        }
        .font-control button:hover {
            background: #0056b3;
        }

        h1, h2, h3, h4, h5, h6 {
            font-size: calc(var(--font-size) * 1.5) !important;
        }
        p, a, span, div {
            font-size: var(--font-size) !important;
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
            </div>
        </div>
    </section>

    <main class="container">
        <section id="clinicas" class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Clínicas Parceiras</h2>
                <a href="{{ Route('clinic.signup') }}" class="btn btn-outline-primary">
                    <i class="bi bi-building-add"></i> Seja um Parceiro
                </a>
            </div>

            <div class="row row-cols-1 row-cols-md-3 g-4">
                @forelse ($clinics as $clinic)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $clinic->name }}</h5>
                                <a href="{{ Route('psychologist.clinic',['id' => $clinic->id]) }}" class="btn btn-primary">Ver Psicólogos</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <label>Tem anda não</label>
                @endforelse
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

<div class="font-control">
    <button onclick="changeFontSize('decrease')">A-</button>
    <button onclick="changeFontSize('reset')">A</button>
    <button onclick="changeFontSize('increase')">A+</button>
</div>

<script>
    function changeFontSize(action) {
        const root = document.documentElement;
        const currentSize = parseInt(getComputedStyle(root).getPropertyValue('--font-size')) || 16;
        
        switch(action) {
            case 'increase':
                root.style.setProperty('--font-size', `${currentSize + 2}px`);
                break;
            case 'decrease':
                if (currentSize > 8) {
                    root.style.setProperty('--font-size', `${currentSize - 2}px`);
                }
                break;
            case 'reset':
                root.style.setProperty('--font-size', '16px');
                break;
        }
    }
</script>
