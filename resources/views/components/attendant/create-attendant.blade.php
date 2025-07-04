<div class="modal fade" id="createAttendantModal" tabindex="-1" aria-labelledby="createAttendantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card shadow-sm p-4 rounded">
                    <h4 class="mb-3">Cadastrar Atendente</h4>

                    @if (session()->has('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('clinic.attendant.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input name="name" type="text" class="form-control" id="name" required>
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input name="email" type="email" class="form-control" id="email" required>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input name="password" type="password" class="form-control" id="password" required>
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="document_number" class="form-label">Número do Documento</label>
                            <input name="document_number" type="text" class="form-control" id="document_number" minlength="11" maxlength="11" required>
                            @error('document_number') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select" id="status" required>
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
    document.addEventListener('DOMContentLoaded', function() {
        const documentoInput = document.getElementById('document_number');
        
        // Define o placeholder para orientar o usuário
        documentoInput.placeholder = '000.000.000-00';
        
        documentoInput.addEventListener('input', function() {
            // Remove todos os caracteres não numéricos
            let value = this.value.replace(/\D/g, '');
            
            // Limita a 11 dígitos (CPF)
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            
            // Aplica a máscara de CPF (XXX.XXX.XXX-XX)
            if (value.length > 0) {
                value = value.replace(/^(\d{1,3})/, '$1');
                if (value.length > 3) {
                    value = value.replace(/^(\d{3})(\d{1,3})/, '$1.$2');
                }
                if (value.length > 6) {
                    value = value.replace(/^(\d{3})\.(\d{3})(\d{1,3})/, '$1.$2.$3');
                }
                if (value.length > 9) {
                    value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
                }
            }
            
            this.value = value;
        });
    });
</script>
