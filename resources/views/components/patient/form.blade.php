<div>
    <form method="POST" action="{{ route('patient.register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome completo</label>
            <input type="text" name="name" id="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo de document_number</label>
            <select name="document_type" id="document_type" class="form-select mb-2" onchange="atualizarDocumento()">
                <option value="cpf" selected>CPF</option>
                <option value="rg">RG</option>
            </select>
            <input type="text" name="document_number" id="document_number" class="form-control" placeholder="000.000.000-00" required>
        </div>

        <div class="mb-3">
            <label for="birth_date" class="form-label">Data de nascimento</label>
            <input type="date" name="birth_date" id="birth_date" class="form-control" required>
        </div>

        <div class="mb-4">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" name="password" id="senha" class="form-control" required minlength="6">
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </div>
    </form>
</div>
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
