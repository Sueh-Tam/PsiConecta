@extends('layouts.clinic.dashboard')

@section('title', 'Pacientes')

@section('content')
<div class="container-fluid px-4">
    <!-- Modais de Feedback -->
    <x-error-modal
        modal-id="patientErrorModal"
        title="Erro na compra do Pacote"
        message="{{ session('error_message') }}"
    />
    <x-success-modal
        modal-id="patientSuccessModal"
        title="Compra feita com sucesso!"
        message="{{ session('success_message') }}"
    />

    <!-- Cabeçalho da Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gerenciamento de Pacientes</h1>
        <button class="btn btn-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalCreatePatient">
            <i class="bi bi-person-plus-fill me-2"></i>
            Cadastrar Paciente
        </button>
    </div>

    <!-- Card Principal -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 text-primary">Lista de Pacientes</h5>
                </div>
                <div class="col-auto">
                    <form method="GET" action="{{ Route('clinic.patient.index') }}" id="searchForm">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchPatient" name="searchPatient" placeholder="Buscar paciente pelo CPF">
                            <button type="submit" class="input-group-text bg-primary text-white border-0" style="cursor: pointer;">
                                <i class="bi bi-search"></i>
                            </button>
                            <button type="button" id="reset-filters" class="btn btn-outline-secondary" onclick="window.location.href = '{{ Route('clinic.patient.index') }}'">
                                <i class="bi bi-x-circle me-1"></i>Limpar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="min-height: 400px; max-height: 70vh; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th class="px-4 py-3">Nome</th>
                            <th class="px-4 py-3">CPF</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patients as $patient)
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-3">
                                            {{ substr($patient->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $patient->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4">{{ $patient->formartDocumentCPF($patient->document_number) }}</td>
                                <td class="px-4 text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $patient->id }}">
                                            <i class="bi bi-pencil-square">Editar</i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalPurchasePackage-{{ $patient->id }}">
                                            <i class="bi bi-bag-plus">Renovar pacote</i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-person-x">Inativar</i>
                                        </button>
                                    </div>
                                    <x-patient.purchase-package :patient="$patient" :psychologists="$psychologists" />
                                </td>
                            </tr>
                            <!-- Modal de Edição -->
                            <div class="modal fade" id="editModal-{{ $patient->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content border-0">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Editar Paciente</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <x-patient.form-update :patient="$patient" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-people fs-1 d-block mb-3"></i>
                                        Nenhum paciente encontrado
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Criação -->
    <div class="modal fade" id="modalCreatePatient" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Novo Paciente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <x-patient.form />
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const resetFiltersButton = document.getElementById('reset-filters');

        resetFiltersButton.addEventListener('click', function() {
            window.location.href = window.location.pathname;
        });
    });
</script>
<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.table-responsive::-webkit-scrollbar {
    width: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endsection
