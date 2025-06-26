<div class="modal fade" id="createPsychologistModal" tabindex="-1" aria-labelledby="createPsychologistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card shadow-sm p-4 rounded">
                    <h4 class="mb-3">Cadastrar Psicólogo</h4>

                    <form action="{{ Route('clinic.psychologist.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input name="name" type="text" class="form-control" id="name">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input name="email" type="email" class="form-control" id="email">
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="document_number" class="form-label">CRP</label>
                            <input name="document_number" type="text" class="form-control" id="document_number" minlength="7" maxlength="7">
                            @error('document_number') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="appointment_price" class="form-label">Valor da Consulta</label>
                            <input name="appointment_price" type="number" step="0.01" class="form-control" id="appointment_price">
                            @error('appointment_price') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input name="password" type="password" class="form-control" id="password">
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select" id="status">
                                <option value="active">Ativo</option>
                                <option value="inactive">Inativo</option>
                            </select>
                            @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const documentoInput = document.getElementById('document_number');
    

    function atualizarDocumento() {
        documentoInput.value = '';
        documentoInput.placeholder = '00.000-00';
        
    }

    documentoInput.addEventListener('input', () => {
        let value = documentoInput.value.replace(/\D/g, '');
        
        if (value.length > 7) value = value.slice(0, 7);
        if (value.length >= 2) value = value.replace(/(\d{2})(\d)/, '$1.$2');
        if (value.length >= 5) value = value.replace(/(\d{2}\.\d{3})(\d)/, '$1-$2');
        
        documentoInput.value = value;
    });
</script>
