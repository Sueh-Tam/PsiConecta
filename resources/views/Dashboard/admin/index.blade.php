@extends('layouts.admin.dashboard')

@section('title', 'Admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="container-fluid px-4">
    <!-- Cabeçalho da Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-primary">Lista de Clínicas</h1>
            <p class="text-muted mb-0">Gerencie as clínicas cadastradas no sistema</p>
        </div>
    </div>

    <!-- Card da Tabela -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Clínica</th>
                            <th class="px-4 py-3">CNPJ</th>
                            <th class="px-4 py-3">Situação</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clinics as $clinic)
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-2">
                                            {{ substr($clinic->name, 0, 2) }}
                                        </div>
                                        {{ $clinic->name }}
                                    </div>
                                </td>
                                <td class="px-4">{{ $clinic->document_number }}</td>
                                <td class="px-4">
                                    <span class="badge rounded-pill @if ($clinic->situation == 'valid') bg-success @elseif ($clinic->situation == 'pending') bg-warning text-dark @elseif ($clinic->situation == 'invalid') bg-danger text-light @endif">{{ $clinic->situation == 'valid' ? 'Válido': 'Inválido' }}</span>
                                </td>
                                <td class="px-4">
                                    <span class="badge rounded-pill @if ($clinic->status == 'active') bg-success @elseif ($clinic->status == 'inactive') bg-secondary text-light @endif">{{ $clinic->status == 'active' ? 'Ativo' : 'Inativo' }}</span>
                                </td>
                                <td class="px-4 text-center">
                                    <button class="btn btn-outline-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarClinicaModal"
                                            data-id="{{ $clinic->id }}"
                                            data-name="{{ $clinic->name }}"
                                            data-email="{{ $clinic->email }}"
                                            data-document="{{ $clinic->document_number }}"
                                            data-situacao="{{ $clinic->situation }}"
                                            data-status="{{ $clinic->status }}">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Detalhes da Clínica
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                        @if ($clinics->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                        Nenhuma clínica cadastrada.
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<x-clinics.editar-clinica-modal />
<x-success-modal
    modal-id="patientSuccessModal"
    title="Cadastro Realizado!"
    message="{{ session('success_message') }}"
/>
<x-error-modal
modal-id="patientErrorModal"
title="Erro"
/>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editarClinicaModal = document.getElementById('editarClinicaModal');

        editarClinicaModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            editarClinicaModal.querySelector('#clinicaId').value = button.getAttribute('data-id');
            editarClinicaModal.querySelector('#nomeClinica').value = button.getAttribute('data-name');
            editarClinicaModal.querySelector('#emailClinica').value = button.getAttribute('data-email');
            editarClinicaModal.querySelector('#documentoClinica').value = button.getAttribute('data-document');
            editarClinicaModal.querySelector('#situacaoClinica').value = button.getAttribute('data-situacao');
            editarClinicaModal.querySelector('#statusClinica').value = button.getAttribute('data-status');
        });
    });
    </script>


<!-- JS Bootstrap local (ou substitua por asset se já estiver copiado para o projeto) -->
<style>
    .avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
    }
    
    .table th {
        font-weight: 600;
        white-space: nowrap;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.5em 0.75em;
    }
    
    .btn-sm {
        padding: 0.4rem 0.8rem;
    }
</style>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@endsection
