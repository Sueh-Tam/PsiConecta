<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Paciente - PsiConecta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        h1, h2, h3, h4, h5, h6 {
            font-size: calc(var(--font-size) * 1.5) !important;
        }
        p, a, span, div, label, input, select, button {
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
                <a href="{{ Route('login') }}" class="btn btn-outline-light me-2">Login</a>
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
                                <input type="text" name="name" id="nome" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                            </div>

                            <!-- CPF -->
                            <div class="mb-3">
                                <label class="form-label">Tipo de documento</label>
                                <select name="document_type" id="document_type" class="form-select mb-2" onchange="atualizarDocumento()">
                                    <option value="cpf" selected>CPF</option>
                                </select>
                                <input type="text" name="document_number" id="document_number" class="form-control" placeholder="000.000.000-00" value="{{ old('document_number') }}" required>
                            </div>

                            <!-- Data de Nascimento -->
                            <div class="mb-3">
                                <label for="birth_date" class="form-label">Data de nascimento</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
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

        // Funções do formulário
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
</body>
</html>
