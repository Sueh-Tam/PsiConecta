<div class="modal fade" id="editAttendantModal-{{ $attendant->id }}" tabindex="-1" aria-labelledby="editAttendantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="mb-3">Editar Atendente</h4>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('clinic.attendant.update', $attendant->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input name="name" type="text" class="form-control" value="{{ old('name', $attendant->name) }}" required>
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email', $attendant->email) }}" required>
                        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Redefinir Senha (opcional)</label>
                        <input name="password" type="password" class="form-control" placeholder="Deixe em branco para manter">
                        @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="document_number" class="form-label">CPF/Documento</label>
                        <input name="document_number" type="text" class="form-control" value="{{ old('document_number', $attendant->formartDocumentCPF($attendant->document_number)) }}" minlength="14" maxlength="14" required>
                        @error('document_number') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $attendant->status) === 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ old('status', $attendant->status) === 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
