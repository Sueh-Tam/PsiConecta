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
            <h1 class="h4 mb-0"><a href="{{ route('home') }}" class="text-white text-decoration-none">Psiconecta</a></h1>
            <div>
                <a href="{{ Route('user.login') }}" class="btn btn-outline-light me-2">Login</a>
                <a href="/" class="btn btn-light text-primary">Início</a>
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
        title="Cadastro Realizado!"
        message="{{ session('success_message') }}"
    />
    <!-- Formulário de Cadastro -->
    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <h2 class="card-title mb-4 text-primary text-center">Cadastro de Paciente</h2>

                        <form method="POST" action="{{ route('patient.register') }}">
                            @csrf

                            <!-- Nome -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome completo</label>
                                <input type="text" name="name" id="nome" class="form-control" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>

                            <!-- CPF -->
                            <div class="mb-3">
                                <label class="form-label">Tipo de document_number</label>
                                <select name="document_type" id="document_type" class="form-select mb-2" onchange="atualizarDocumento()">
                                    <option value="cpf" selected>CPF</option>
                                    <option value="rg">RG</option>
                                </select>
                                <input type="text" name="document_number" id="document_number" class="form-control" placeholder="000.000.000-00" required>
                            </div>

                            <!-- Data de Nascimento -->
                            <div class="mb-3">
                                <label for="data_nascimento" class="form-label">Data de nascimento</label>
                                <input type="date" name="data_nascimento" id="data_nascimento" class="form-control" required>
                            </div>

                            <!-- Senha -->
                            <div class="mb-4">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" name="password" id="senha" class="form-control" required minlength="6">
                            </div>

                            <!-- Botão -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Cadastrar</button>
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
            <small>&copy; {{ date('Y') }} Psiconecta - Todos os direitos reservados</small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    const documentoInput = document.getElementById('document_number');
    const tipoSelect = document.getElementById('document_type');

    function atualizarDocumento() {
        documentoInput.value = '';
        if (tipoSelect.value === 'cpf') {
            documentoInput.placeholder = '000.000.000-00';
        } else {
            documentoInput.placeholder = '00.000.000-0';
        }
    }

    documentoInput.addEventListener('input', () => {
        let value = documentoInput.value.replace(/\D/g, '');
        if (tipoSelect.value === 'cpf') {
            if (value.length > 11) value = value.slice(0, 11);
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        } else {
            if (value.length > 9) value = value.slice(0, 9);
            value = value.replace(/(\d{2})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1})$/, '$1-$2');
        }
        documentoInput.value = value;
    });
</script>
</html>
