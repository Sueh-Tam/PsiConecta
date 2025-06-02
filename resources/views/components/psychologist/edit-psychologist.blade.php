<div class="modal fade" id="editPsychologistModal" tabindex="-1" aria-labelledby="editPsychologistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card shadow-sm p-4 rounded">
                    <h4 class="mb-3">Editar Psicólogo</h4>

                    <form action="{{ Route('clinic.psychologist.update') }}" method="POST">
                        @method('PUT')
                        @csrf
                        <input type="hidden" id="psychologistID" name="id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input name="name" type="text" class="form-control" id="edit_name">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input name="email" type="email" class="form-control" id="edit_email">
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="document_number" class="form-label">CRP</label>
                            <input name="document_number" type="text" class="form-control" id="edit_document_number">
                            @error('document_number') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="appointment_price" class="form-label">Valor da Consulta</label>
                            <input name="appointment_price" type="number" step="0.01" class="form-control" id="edit_appointment_price">
                            @error('appointment_price') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <small class="form-text text-muted">Deixe em branco se não quiser alterar.</small>
                            <input name="password" type="password" class="form-control" id="edit_password">
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select" id="edit_status">
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
