<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>PsiConecta</title>
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
                    <a href="/fazer" class="btn btn-outline-light me-2">Dashboard</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Modal de Erro -->
    <x-error-modal
        modal-id="patientErrorModal"
        title="Erro no Cadastro de Paciente"
    />
    <!-- Modal de Sucesso -->
    <x-success-modal
        modal-id="patientSuccessModal"
        title="Login"
        message="{{ session('success_message') }}"
    />
    <!-- Formulário de Cadastro -->
    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <h2 class="card-title mb-4 text-primary text-center">Admin</h2>

                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <!-- Senha -->
                            <div class="mb-4">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" name="password" id="senha" class="form-control" required minlength="6">
                            </div>

                            <!-- Botão -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>

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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para garantir que todos os modais sejam destruídos corretamente quando fechados
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('hidden.bs.modal', function() {
                // Remove a classe modal-open do body
                document.body.classList.remove('modal-open');
                
                // Remove o backdrop do modal
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            });
        });
    </script>
</body>
</html>
