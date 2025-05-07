@props(['patient'])

<div>
    <form method="POST" action="">
        @csrf
        @method('PUT') <!-- Método para atualização -->

        <!-- Nome -->
        <div class="mb-3">
            <label for="name" class="form-label">Nome completo</label>
            <input type="text" name="name" id="nome" class="form-control" value="{{ old('name', $patient->name) }}" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $patient->email) }}" required>
        </div>

        <!-- Tipo de documento -->
        <div class="mb-3">
            <label class="form-label">Tipo de document_number</label>
            <select name="document_type" id="document_type" class="form-select mb-2" onchange="atualizarDocumento()">
                <option value="cpf" {{ $patient->document_type === 'cpf' ? 'selected' : '' }}>CPF</option>
                <option value="rg" {{ $patient->document_type === 'rg' ? 'selected' : '' }}>RG</option>
            </select>
            <input type="text" name="document_number" id="document_number" class="form-control"
                   value="{{ old('document_number', $patient->formartDocumentCPF($patient->document_number)) }}" required>
        </div>

        <!-- Data de nascimento -->
        <div class="mb-3">
            <label for="data_nascimento" class="form-label">Data de nascimento</label>
            <input type="date" name="data_nascimento" id="data_nascimento" class="form-control"
                   value="{{ old('data_nascimento', $patient->data_nascimento ? $patient->data_nascimento->format('Y-m-d') : '') }}" required>
        </div>

        <!-- Botão -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Atualizar</button>
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
