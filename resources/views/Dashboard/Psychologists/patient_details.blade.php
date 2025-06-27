@extends('layouts.psychologist.dashboard')

@section('title', 'Prontuários')

@section('content')
<x-success-modal
    modal-id="patientSuccessModal"
    title="{{ session('title') }}"
    message="{{ session('message') }}"
/>
<x-error-modal
    modal-id="patientErrorModal"
    title="Erro"
/>
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="container-fluid px-4">
    <!-- Cabeçalho da Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Prontuários - {{ $patient->name }}</h1>
        <a href="{{ route('psychologist.patients') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <!-- Card Principal -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 text-primary">Informações do Paciente</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nome:</strong> {{ $patient->name }}</p>
                    <p><strong>Email:</strong> {{ $patient->email }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>CPF:</strong> {{ $patient->formartDocumentCPF($patient->document_number) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card de Prontuários -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 text-primary">Histórico de Prontuários</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="min-height: 400px; max-height: 70vh; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th class="px-4 py-3">Data</th>
                            <th class="px-4 py-3">Horário</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Prontuário</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $appointment)
                            <tr>
                                <td class="px-4">{{ \Carbon\Carbon::parse($appointment->dt_Availability)->format('d/m/Y') }}</td>
                                <td class="px-4">{{ $appointment->hr_Availability }}</td>
                                <td class="px-4">
                                    @switch($appointment->status)
                                        @case('completed')
                                            <span class="badge bg-success">Realizada</span>
                                            @break
                                        @case('scheduled')
                                            <span class="badge bg-warning text-dark">Agendada</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge" style="background-color: #8B0000">Cancelamento Tardio</span>
                                            @break
                                        @case('canceled_early')
                                            <span class="badge" style="background-color: #FFA500">Cancelamento Antecipado</span>
                                            @break
                                        @case('canceled_late')
                                            <span class="badge" style="background-color: #8B0000">Cancelamento Tardio</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($appointment->status) }}</span>
                                    @endswitch
                                </td>
                                <td class="px-4">
                                    @if($appointment->medical_record)
                                        {{ Str::limit($appointment->medical_record, 50) }}
                                        @if(strlen($appointment->medical_record) > 50)
                                            <button type="button" class="btn btn-link btn-sm" data-bs-toggle="modal" data-bs-target="#recordModal{{ $appointment->id }}">
                                                Ver mais
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="recordModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="recordModalLabel{{ $appointment->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="recordModalLabel{{ $appointment->id }}">Prontuário Médico - {{ \Carbon\Carbon::parse($appointment->dt_Availability)->format('d/m/Y') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="p-3 bg-light rounded">
                                                                {{ $appointment->medical_record }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-muted">Sem prontuário</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-journal-text fs-1 d-block mb-3"></i>
                                        Nenhum prontuário encontrado
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

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

@endsection