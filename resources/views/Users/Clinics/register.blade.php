<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Solicitar conta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <div class="col-md-6">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('clinic.register') }}">
                            @csrf
                            <h2 class="card-title mb-4 text-primary text-center">Solicitar conta</h2>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nome da clínica</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail da clínica</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="document_number" class="form-label">CNPJ</label>
                                <input type="text" class="form-control" id="document_number" name="document_number" maxlength="18" required>
                            </div>


                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                            </div>

                            <div class="mb-3">
                                <label for="senha_confirmation" class="form-label">Confirmar Senha</label>
                                <input type="password" class="form-control" id="senha_confirmation" name="senha_confirmation" minlength="6" required>
                                <div class="invalid-feedback">As senhas não coincidem.</div>
                            </div>
                            <label>Ja possui uma conta? <a href="{{ Route('auth.login') }}">clique aqui</a></label><br>
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const docInput = document.getElementById('document_number');

        function formatarCNPJ(valor) {
            const numeros = valor.replace(/\D/g, '');

            const cnpjFormatado = numeros
                .replace(/^(\d{2})(\d)/, '$1.$2')
                .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                .replace(/\.(\d{3})(\d)/, '.$1/$2')
                .replace(/(\d{4})(\d)/, '$1-$2');

            return cnpjFormatado.slice(0, 18); // evita digitar além do necessário
        }

        function aplicarMascara() {
            docInput.value = formatarCNPJ(docInput.value);
        }

        docInput.addEventListener('input', aplicarMascara);

        // Aplica máscara automaticamente ao carregar a página
        aplicarMascara();
    });
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
</script>

</html>
