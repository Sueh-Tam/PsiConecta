@extends('layouts.clinic.dashboard')

@section('title', 'Atendentes')

@section('content')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="container-fluid px-4">
    <!-- Cabeçalho da Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="text-primary mb-1">Lista de Atendentes</h5>
            <p class="text-muted mb-0">Gerencie os atendentes da clínica</p>
        </div>
        <button class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createAttendantModal">
            <i class="bi bi-person-plus me-2"></i>
            Cadastrar Atendente
        </button>
    </div>

    <!-- Cards de Resumo -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-primary mb-1">Total de Atendentes</h6>
                            <h5 class="mb-0">{{ $attendants->count() }}</h5>
                            <small class="text-muted">Cadastrados no sistema</small>
                        </div>
                        <i class="bi bi-people text-primary fs-1 ms-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-success mb-1">Atendentes Ativos</h6>
                            <h5 class="mb-0">{{ $attendants->where('status', 'active')->count() }}</h5>
                            <small class="text-muted">Em atividade</small>
                        </div>
                        <i class="bi bi-person-check text-success fs-1 ms-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-warning mb-1">Atendentes Inativos</h6>
                            <h5 class="mb-0">{{ $attendants->where('status', 'inactive')->count() }}</h5>
                            <small class="text-muted">Fora de atividade</small>
                        </div>
                        <i class="bi bi-person-dash text-warning fs-1 ms-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Atendentes -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary">Atendentes Cadastrados</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Nome</th>
                            <th class="px-4 py-3">CPF</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendants as $attendant)
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-2">{{ substr($attendant->name, 0, 2) }}</div>
                                        {{ $attendant->name }}
                                    </div>
                                </td>
                                <td class="px-4">{{ $attendant->formartDocumentCPF($attendant->document_number) }}</td>
                                <td class="px-4">
                                    <span class="badge @if ($attendant->status == 'active') bg-success @elseif ($attendant->status == 'inactive') bg-danger @else bg-warning @endif">
                                        {{ $attendant->status == 'active' ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="px-4 text-center">
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAttendantModal-{{ $attendant->id }}">
                                        <i class="bi bi-pencil-square me-1"></i>Editar
                                    </button>
                                </td>
                            </tr>
                            @include('components.attendant.edit-attendant', ['attendant' => $attendant])
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">
                                    <p class="text-muted mb-0">Nenhum atendente cadastrado</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-error-modal
    modal-id="patientErrorModal"
    title="Erro"
/>
<x-success-modal
    modal-id="patientSuccessModal"
    title="Cadastro Realizado!"
    message="{{ session('success_message') }}"
/>
<x-attendant.create-attendant />

<style>
    .border-left-primary {
        border-left: 4px solid #0d6efd;
    }
    .border-left-success {
        border-left: 4px solid #198754;
    }
    .border-left-warning {
        border-left: 4px solid #ffc107;
    }
    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
    }
</style>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@endsection
