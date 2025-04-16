@extends('layouts.admin.dashboard')

@section('title', 'Admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

<div class="d-flex justify-content-between align-items-center mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agendarModal">
        Agendar Consulta
    </button>
</div>
<table class="table table-bordered table-striped shadow-sm rounded">
    <thead class="table-primary">
        <tr>
            <th>Clínica</th>
            <th>CNPJ</th>
            <th>Situação</th>
            <th>Status</th>
            <th>Detalhes</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clinics as $clinic)
            <tr>
                <td>{{ $clinic->name }}</td>
                <td>{{ $clinic->document_number }}</td>
                <td><span class="badge @if ($clinic->situation == 'valid') bg-success @elseif ($clinic->situation == 'pending') bg-warning text-dark @elseif ($clinic->situation == 'invalid') bg-danger text-light  @endif text-dark">{{ $clinic->situation }}</span></td>
                <td><span class="badge @if ($clinic->status == 'active') bg-success @elseif ($clinic->status == 'inactive') bg-secondary text-light  @endif ">{{ $clinic->status }}</span></td>
                <td>
                    <!-- Botão de Detalhes -->
                    <div class="d-flex justify-content-center ">
                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editarClinicaModal">
                            Detalhes da Clínica
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>




<!-- Modal de Edição da Clínica -->
<div class="modal fade" id="editarClinicaModal" tabindex="-1" aria-labelledby="editarClinicaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarClinicaModalLabel">Editar Dados da Clínica</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
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
                    <select class="form-select" id="situacaoClinica" name="situacao" required>
                        <option value="pendente">Pendente</option>
                        <option value="aprovado">Aprovado</option>
                        <option value="reprovado">Reprovado</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="statusClinica" class="form-label">Status</label>
                    <select class="form-select" id="statusClinica" name="status" required>
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
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


<!-- JS Bootstrap local (ou substitua por asset se já estiver copiado para o projeto) -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@endsection
