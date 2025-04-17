
<div class="modal fade" id="editarClinicaModal" tabindex="-1" aria-labelledby="editarClinicaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> {{-- modal-lg aumenta a largura --}}
        <form class="modal-content" action="{{ route('admin.update') }}" method="POST">
            {{-- CSRF token para proteção contra CSRF --}}
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editarClinicaModalLabel">Editar Dados da Clínica</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="clinicaId" name="id">

                <div class="mb-3">
                    <label for="nomeClinica" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nomeClinica" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="emailClinica" class="form-label">Email</label>
                    <input type="email" class="form-control" id="emailClinica" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="senhaClinica" class="form-label">Resetar Senha</label>
                    <input type="password" class="form-control" id="senhaClinica" name="password">
                    <small class="form-text text-muted">Deixe em branco se não quiser alterar.</small>
                </div>

                <div class="mb-3">
                    <label for="documentoClinica" class="form-label">Número do Documento (CNPJ)</label>
                    <input type="text" class="form-control" id="documentoClinica" name="document_number" maxlength="18">
                </div>

                <div class="mb-3">
                    <label for="situacaoClinica" class="form-label">Situação</label>
                     <!-- Corrigir o name do select de situação -->
                    <select class="form-select" id="situacaoClinica" name="situation" required>
                        <option value="valid">Aprovado</option>
                        <option value="pending">Pendente</option>
                        <option value="invalid">Reprovado</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="statusClinica" class="form-label">Status</label>
                    <select class="form-select" id="statusClinica" name="status" required>
                        <option value="active">Ativo</option>
                        <option value="inactive">Inativo</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const docInput = document.getElementById('documentoClinica');

    // Função para formatar CNPJ
    function formatCNPJ(value) {
        // Remove tudo que não é dígito
        const digits = value.replace(/\D/g, '');

        // Aplica a formatação do CNPJ
        return digits
            .replace(/(\d{2})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d)/, '$1/$2')
            .replace(/(\d{4})(\d{1,2})/, '$1-$2')
            .replace(/(-\d{2})\d+?$/, '$1');
    }

    // Evento de input
    docInput.addEventListener('input', function(e) {
        // Obtém a posição atual do cursor
        const cursorPosition = e.target.selectionStart;
        const originalLength = e.target.value.length;

        // Aplica a formatação
        e.target.value = formatCNPJ(e.target.value);

        // Mantém a posição do cursor correta após formatação
        const newLength = e.target.value.length;
        const lengthDiff = newLength - originalLength;
        e.target.setSelectionRange(cursorPosition + lengthDiff, cursorPosition + lengthDiff);
    });

    // Evento de blur (quando sai do campo) para garantir formatação completa
    docInput.addEventListener('blur', function(e) {
        const digits = e.target.value.replace(/\D/g, '');
        if (digits.length === 14) {
            e.target.value = formatCNPJ(digits);
        }
    });

    // Evento de keydown para permitir navegação com teclado
    docInput.addEventListener('keydown', function(e) {
        // Permite: backspace, delete, tab, escape, enter, setas
        if ([46, 8, 9, 27, 13, 37, 38, 39, 40].includes(e.keyCode) ||
            // Permite: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true)) {
            return;
        }
        // Impede que digite não-números
        if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});
    </script>
