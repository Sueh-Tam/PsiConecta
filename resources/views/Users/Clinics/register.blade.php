<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>PsiConecta - Cadastro de Clínica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
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
            opacity: 1;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }
    </style>
</head>
<body class="bg-light">
    <header class="bg-primary text-white py-3 shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0"><a href="{{ route('home') }}" class="text-white text-decoration-none">PsiConecta</a></h1>
            <div>
                @if (Auth::check())
                <a href="{{ Route('user.signup') }}" class="btn btn-outline-light me-2">Dashboard</a>
                @else
                    <a href="{{ Route('clinic.signup') }}" class="btn btn-outline-light me-2">Login</a>
                    <a href="{{ Route('home') }}" class="btn btn-light text-primary">Início</a>
                @endif

            </div>
        </div>
    </header>
{{-- <x-error-modal
modal-id="clinicErrorModal"
title="Erro no Cadastro de Clínica"
/> --}}
<x-success-modal
    modal-id="clinicSuccessModal"
    title="Cadastro Realizado!"
    message="{{ session('success_message') }}"
/>
    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
                        <h2 class="h3 mb-0">Cadastro de Clínica</h2>
                        <p class="mb-0 mt-2">Junte-se à nossa rede de profissionais</p>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('clinic.register') }}" class="needs-validation">
                            @csrf
                            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nome da clínica" value="{{ old('name') }}" required>
                                <label for="name"><i class="bi bi-building me-2"></i>Nome da clínica</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="E-mail" value="{{ old('email') }}" required>
                                <label for="email"><i class="bi bi-envelope me-2"></i>E-mail da clínica</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('document_number') is-invalid @enderror" id="document_number" name="document_number" placeholder="CNPJ" maxlength="18" value="{{ old('document_number') }}" required>
                                <label for="document_number"><i class="bi bi-card-text me-2"></i>CNPJ</label>
                                @error('document_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Senha" minlength="6" required>
                                <label for="password"><i class="bi bi-lock me-2"></i>Senha</label>
                                <i class="bi bi-eye-slash password-toggle" onclick="togglePassword('password')"></i>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-4 position-relative">
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Confirmar Senha" minlength="6" required>
                                <label for="password_confirmation"><i class="bi bi-lock-fill me-2"></i>Confirmar Senha</label>
                                <i class="bi bi-eye-slash password-toggle" onclick="togglePassword('password_confirmation')"></i>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check2-circle me-2"></i>Cadastrar Clínica
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="mb-0">Já possui uma conta? 
                                    <a href="{{ Route('login') }}" class="text-primary text-decoration-none">
                                        Faça login aqui
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const docInput = document.getElementById('document_number');

        function formatarCNPJ(valor) {
            let numeros = valor.replace(/\D/g, '');
            
            numeros = numeros.slice(0, 14);
            
            let cnpjFormatado = numeros;
            
            if (numeros.length > 2) {
                cnpjFormatado = numeros.slice(0, 2) + '.' + numeros.slice(2);
            }
            if (numeros.length > 5) {
                cnpjFormatado = cnpjFormatado.slice(0, 6) + '.' + cnpjFormatado.slice(6);
            }
            if (numeros.length > 8) {
                cnpjFormatado = cnpjFormatado.slice(0, 10) + '/' + cnpjFormatado.slice(10);
            }
            if (numeros.length > 12) {
                cnpjFormatado = cnpjFormatado.slice(0, 15) + '-' + cnpjFormatado.slice(15);
            }
            
            return cnpjFormatado;
        }

        function manterPosicaoCursor(input, posicaoInicial) {
            const formatadores = ['.', '/', '-'];
            let ajuste = 0;
            
            const valorAtePosicao = input.value.slice(0, posicaoInicial);
            formatadores.forEach(formatador => {
                ajuste += (valorAtePosicao.match(new RegExp('\\' + formatador, 'g')) || []).length;
            });
            
            return posicaoInicial + ajuste;
        }

        docInput.addEventListener('input', function(e) {
            const posicaoInicial = this.selectionStart;
            const valorAntigo = this.value;
            
            this.value = formatarCNPJ(this.value);
            
            const novaPosicao = manterPosicaoCursor(this, posicaoInicial);
            this.setSelectionRange(novaPosicao, novaPosicao);
        });

        function aplicarMascara() {
            docInput.value = formatarCNPJ(docInput.value);
        }

        docInput.addEventListener('input', aplicarMascara);

        aplicarMascara();
    
    document.addEventListener("DOMContentLoaded", function () {
        const input = document.getElementById("document_number");
        const senha = document.getElementById("password");
        const repetirSenha = document.getElementById("password_confirmation");
    });

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.nextElementSibling;
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    }

    // Form validation
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const senha = document.getElementById('password');
        const repetirSenha = document.getElementById('password_confirmation');

        // Exibir modal de erro se houver erros de validação
        @if($errors->any())
            const clinicErrorModalElement = document.getElementById('clinicErrorModal');
            const clinicErrorModal = new bootstrap.Modal(clinicErrorModalElement);
            clinicErrorModal.show();
            
            // Garantir que o modal seja destruído corretamente quando fechado
            clinicErrorModalElement.addEventListener('hidden.bs.modal', function () {
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            });
        @endif

        form.addEventListener('submit', function (event) {
            if (senha.value !== repetirSenha.value) {
                senha.classList.add('is-invalid');
                repetirSenha.classList.add('is-invalid');
                event.preventDefault();
            } else {
                senha.classList.remove('is-invalid');
                repetirSenha.classList.remove('is-invalid');
            }
        });
    });
    </script>
</body>
</html>
