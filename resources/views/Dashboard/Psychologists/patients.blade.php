@extends('layouts.psychologist.dashboard')

@section('title', 'Meus pacientes')

@section('content')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<x-error-modal
    modal-id="patientErrorModal"
    title="Erro"
/>
<x-success-modal
    modal-id="patientSuccessModal"
    title="Cadastro Atualizado!"
    message="{{ session('success_message') }}"
/>

<div class="container-fluid px-4">
    <!-- Cabeçalho da Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Meus Pacientes</h1>
    </div>

    <!-- Card Principal -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 text-primary">Lista de Pacientes</h5>
                </div>
                <div class="col-auto">
                    <form method="GET" action="{{ Route('psychologist.patients') }}" id="searchForm">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchPatient" name="searchPatient" placeholder="Buscar paciente pelo CPF">
                            <button type="submit" class="input-group-text bg-primary text-white border-0" style="cursor: pointer;">
                                <i class="bi bi-search"></i>
                            </button>
                            <button type="button" id="reset-filters" class="btn btn-outline-secondary" onclick="window.location.href = '{{ Route('psychologist.patients') }}'">
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
                        @forelse ($patients ?? [] as $patient)
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
                                        <a href="{{ route('psychologist.patient.details', $patient->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-journal-text"></i> Ver Prontuários
                                        </a>
                                    </div>
                                </td>
                            </tr>
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
</div>

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

<!-- JS Bootstrap local -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@endsection
