<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>PsiConecta - Login</title>
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
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #0d6efd;
        }
        .login-container {
            min-height: calc(100vh - 180px);
            display: flex;
            align-items: center;
        }
        h1, h2, h3, h4, h5, h6 {
            font-size: calc(var(--font-size) * 1.5) !important;
        }
        p, a, span, div {
            font-size: var(--font-size) !important;
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
    </style>
</head>
<body class="bg-light">

    <!-- Header -->
    <header class="bg-primary text-white py-3 shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0"><a href="{{ route('home') }}" class="text-white text-decoration-none">PsiConecta</a></h1>
            <div>
                @if (Auth::check())
                <a href="{{ Route('user.signup') }}" class="btn btn-outline-light me-2">Dashboard</a>
                @else
                    <a href="{{ Route('user.signup') }}" class="btn btn-outline-light me-2">Registre-se</a>
                    <a href="{{ Route('home') }}" class="btn btn-light text-primary">Início</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Modal de Erro -->
    <x-error-modal
        modal-id="patientErrorModal"
        title="Erro ao logar"
    />
    <!-- Modal de Sucesso -->
    <x-success-modal
        modal-id="patientSuccessModal"
        title="Login"
        message="{{ session('success_message') }}"
    />
    <!-- Formulário de Login -->
    <main class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="text-center mb-4">
                        <h1 class="display-6 text-primary">Bem-vindo de volta!</h1>
                        <p class="text-muted">Entre para continuar sua jornada</p>
                    </div>
                    
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
                            <h2 class="h4 mb-0">Login</h2>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                                @csrf
                                
                                <div class="form-floating mb-3">
                                    <input type="email" 
                                           class="form-control" 
                                           id="email" 
                                           name="email" 
                                           placeholder="nome@exemplo.com" 
                                           required>
                                    <label for="email">
                                        <i class="bi bi-envelope me-2"></i>E-mail
                                    </label>
                                    <div class="invalid-feedback">
                                        Por favor, insira um e-mail válido
                                    </div>
                                </div>

                                <div class="form-floating mb-4 position-relative">
                                    <input type="password" 
                                           class="form-control" 
                                           id="senha" 
                                           name="password" 
                                           placeholder="Senha" 
                                           required 
                                           minlength="6">
                                    <label for="senha">
                                        <i class="bi bi-lock me-2"></i>Senha
                                    </label>
                                    <i class="bi bi-eye-slash password-toggle" onclick="togglePassword()"></i>
                                    <div class="invalid-feedback">
                                        A senha deve ter no mínimo 6 caracteres
                                    </div>
                                </div>

                                <div class="d-grid gap-3">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                                    </button>
                                    
                                    <div class="text-center">
                                        <p class="mb-0">Não possui uma conta? 
                                            <a href="{{ Route('user.signup') }}" class="text-primary text-decoration-none fw-bold">
                                                Registre-se
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white text-center py-3 mt-5">
        <div class="container">
            <small>&copy; {{ date('Y') }} PsiConecta - Todos os direitos reservados</small>
        </div>
    </footer>

    <!-- Controles de Fonte -->
    <div class="font-control">
        <button onclick="changeFontSize('decrease')">A-</button>
        <button onclick="changeFontSize('reset')">A</button>
        <button onclick="changeFontSize('increase')">A+</button>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para ajuste de fonte
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

        // Validação do formulário
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });

        // Toggle de visibilidade da senha
        function togglePassword() {
            const senhaInput = document.getElementById('senha');
            const toggleIcon = document.querySelector('.password-toggle');
            
            if (senhaInput.type === 'password') {
                senhaInput.type = 'text';
                toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                senhaInput.type = 'password';
                toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        }
    </script>
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
</body>
</html>
