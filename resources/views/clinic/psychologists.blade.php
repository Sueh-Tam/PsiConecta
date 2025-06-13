<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>PsiConecta - Psicólogos da Clínica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="imagex/png" href="../img/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,123,255,0.9), rgba(0,123,255,0.7));
            padding: 4rem 0;
            margin-bottom: 3rem;
        }
        .psychologist-card {
            transition: transform 0.3s ease;
        }
        .psychologist-card:hover {
            transform: translateY(-5px);
        }
        .avatar-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .specialty-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            margin-right: 0.25rem;
            margin-bottom: 0.25rem;
            display: inline-block;
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

    <!-- Hero Section -->
    <section class="hero-section text-white text-center">
        <div class="container">
            <h1 class="display-4 mb-4">{{ $clinic->name }}</h1>
            <p class="lead mb-4">Conheça nossos profissionais e agende sua consulta</p>
        </div>
    </section>

    <main class="container">
        <section id="psicologos" class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Psicólogos Disponíveis</h2>
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Voltar para Clínicas
                </a>
            </div>

            <div class="row row-cols-1 row-cols-md-3 g-4">
                @forelse ($psychologists as $psychologist)
                    <div class="col">
                        <div class="card h-100 shadow-sm psychologist-card">
                            <div class="card-body text-center">
                                <div class="avatar-circle bg-primary text-white mx-auto">
                                    {{ substr($psychologist->name, 0, 2) }}
                                </div>
                                <h5 class="card-title">{{ $psychologist->name }}</h5>
                                <p class="card-text text-muted mb-2">CRP: {{ $psychologist->formatDocumentCRP($psychologist->document_number) }}</p>
                                <p class="card-text fw-bold mb-3">Valor da consulta: R$ {{ number_format($psychologist->appointment_price, 2, ',', '.') }}</p>
                                
                                <a href="{{ Route('login') }}" class="btn btn-primary w-100">
                                    <i class="bi bi-calendar-check me-2"></i><span class="text-white">Agendar Consulta</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Esta clínica ainda não possui psicólogos cadastrados.
                        </div>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary mt-3">
                            <i class="bi bi-arrow-left me-2"></i>Voltar para Clínicas
                        </a>
                    </div>
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
    <script>
        // Script para carregar datas e horários disponíveis quando um psicólogo é selecionado
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('agendarModal');
            
            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const psychologistId = button.getAttribute('data-psychologist-id');
                const psychologistName = button.getAttribute('data-psychologist-name');
                
                document.getElementById('psychologist_id').value = psychologistId;
                document.getElementById('psychologist_name').value = psychologistName;
                
                // Aqui você pode fazer uma chamada AJAX para buscar as datas disponíveis
                // baseado no ID do psicólogo selecionado
                // Exemplo:
                /*
                fetch(`/api/psychologists/${psychologistId}/availability`)
                    .then(response => response.json())
                    .then(data => {
                        const dateSelect = document.getElementById('date');
                        dateSelect.innerHTML = '<option selected disabled>Selecione uma data disponível</option>';
                        
                        data.dates.forEach(date => {
                            const option = document.createElement('option');
                            option.value = date.value;
                            option.textContent = date.label;
                            dateSelect.appendChild(option);
                        });
                    });
                */
            });
            
            // Evento para carregar horários quando uma data é selecionada
            document.getElementById('date').addEventListener('change', function() {
                const psychologistId = document.getElementById('psychologist_id').value;
                const date = this.value;
                
                // Aqui você pode fazer uma chamada AJAX para buscar os horários disponíveis
                // baseado no ID do psicólogo e na data selecionada
                // Exemplo:
                /*
                fetch(`/api/psychologists/${psychologistId}/availability/${date}`)
                    .then(response => response.json())
                    .then(data => {
                        const timeSelect = document.getElementById('time');
                        timeSelect.innerHTML = '<option selected disabled>Selecione um horário disponível</option>';
                        
                        data.times.forEach(time => {
                            const option = document.createElement('option');
                            option.value = time.value;
                            option.textContent = time.label;
                            timeSelect.appendChild(option);
                        });
                    });
                */
            });
        });
    </script>
</body>
</html>