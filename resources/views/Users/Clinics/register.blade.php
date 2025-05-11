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
                    <a href="{{ Route('clinic.signup') }}" class="btn btn-outline-light me-2">Login</a>
                    <a href="{{ Route('home') }}" class="btn btn-light text-primary">Início</a>
                @endif

            </div>
        </div>
    </header>
<x-error-modal
modal-id="patientErrorModal"
title="Erro no Cadastro de Paciente"
/>
<!-- Modal de Sucesso -->
<x-success-modal
    modal-id="patientSuccessModal"
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
                        <form method="POST" action="{{ route('clinic.register') }}" class="needs-validation" novalidate>
                            @csrf
                            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nome da clínica" required>
                                <label for="name"><i class="bi bi-building me-2"></i>Nome da clínica</label>
                                <div class="invalid-feedback">Por favor, insira o nome da clínica.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
                                <label for="email"><i class="bi bi-envelope me-2"></i>E-mail da clínica</label>
                                <div class="invalid-feedback">Por favor, insira um e-mail válido.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="document_number" name="document_number" placeholder="CNPJ" maxlength="18" required>
                                <label for="document_number"><i class="bi bi-card-text me-2"></i>CNPJ</label>
                                <div class="invalid-feedback">Por favor, insira um CNPJ válido.</div>
                            </div>

                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Senha" minlength="6" required>
                                <label for="password"><i class="bi bi-lock me-2"></i>Senha</label>
                                <i class="bi bi-eye-slash password-toggle" onclick="togglePassword('password')"></i>
                                <div class="invalid-feedback">A senha deve ter no mínimo 6 caracteres.</div>
                            </div>

                            <div class="form-floating mb-4 position-relative">
                                <input type="password" class="form-control" id="senha_confirmation" name="senha_confirmation" placeholder="Confirmar Senha" minlength="6" required>
                                <label for="senha_confirmation"><i class="bi bi-lock-fill me-2"></i>Confirmar Senha</label>
                                <i class="bi bi-eye-slash password-toggle" onclick="togglePassword('senha_confirmation')"></i>
                                <div class="invalid-feedback">As senhas não coincidem.</div>
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
            // Remove tudo que não é número
            let numeros = valor.replace(/\D/g, '');
            
            // Limita a 14 dígitos
            numeros = numeros.slice(0, 14);
            
            // Aplica a máscara
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

        // Mantém a posição do cursor após a formatação
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
            
            // Formata o valor
            this.value = formatarCNPJ(this.value);
            
            // Ajusta a posição do cursor
            const novaPosicao = manterPosicaoCursor(this, posicaoInicial);
            this.setSelectionRange(novaPosicao, novaPosicao);
        });

        function aplicarMascara() {
            docInput.value = formatarCNPJ(docInput.value);
        }

        docInput.addEventListener('input', aplicarMascara);

        // Aplica máscara automaticamente ao carregar a página
        aplicarMascara();
    
    document.addEventListener("DOMContentLoaded", function () {
        const input = document.getElementById("document_number");
        const form = input.closest("form");
        const senha = document.getElementById("password");
        const repetirSenha = document.getElementById("senha_confirmation");
        form.addEventListener("submit", function (e) {
            if (senha.value !== repetirSenha.value) {
                senha.classList.add('is-invalid');
                repetirSenha.classList.add('is-invalid');
                e.preventDefault(); // impede o envio
                alert("As senhas não coincidem.");
                repetirSenha.focus();
            }else{
                senha.classList.remove('is-invalid');
                repetirSenha.classList.remove('is-invalid');
            }
            });
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
        const repetirSenha = document.getElementById('senha_confirmation');

        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            if (senha.value !== repetirSenha.value) {
                senha.classList.add('is-invalid');
                repetirSenha.classList.add('is-invalid');
                event.preventDefault();
            } else {
                senha.classList.remove('is-invalid');
                repetirSenha.classList.remove('is-invalid');
            }

            form.classList.add('was-validated');
        });
    });
    </script>
</body>
</html>
