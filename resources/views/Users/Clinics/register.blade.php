<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Paciente - Psiconecta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Header -->
    <header class="bg-primary text-white py-3 shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">Psiconecta</h1>
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
    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <form method="POST" action="/registrar">
                            @csrf
                            <h2 class="card-title mb-4 text-primary text-center">Cadastro de Clínica</h2>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="document_number" class="form-label">CPF</label>
                                <input type="text" class="form-control" id="document_number" name="document_number" maxlength="14" required>
                                <small id="erro-documento" class="text-danger d-none">CPF inválido. Digite os 11 números.</small>
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

                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
