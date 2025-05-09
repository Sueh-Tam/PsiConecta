@extends('layouts.clinic.dashboard')

@section('title', 'Psicólogos')

@section('content')
<div class="container-fluid px-4">
    <!-- Cabeçalho da Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-primary">Lista de Psicólogos</h1>
            <p class="text-muted mb-0">Gerencie os psicólogos da sua clínica</p>
        </div>
        <button class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createPsychologistModal">
            <i class="bi bi-plus-circle me-2"></i>
            Cadastrar psicólogo
        </button>
    </div>

    <!-- Componentes de Modal -->
    <x-error-modal modal-id="patientErrorModal" title="Erro"/>
    <x-success-modal modal-id="patientSuccessModal" title="Cadastro Realizado!" message="{{ session('success_message') }}"/>

    <!-- Card da Tabela -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Nome</th>
                            <th class="px-4 py-3">CRP</th>
                            <th class="px-4 py-3">Valor da consulta</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($psychologists as $psychologist)
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-2">
                                            {{ substr($psychologist->name, 0, 2) }}
                                        </div>
                                        {{ $psychologist->name }}
                                    </div>
                                </td>
                                <td class="px-4">{{ $psychologist->formatDocumentCRP($psychologist->document_number) }}</td>
                                <td class="px-4">R$ {{ number_format($psychologist->appointment_price, 2, ',', '.') }}</td>
                                <td class="px-4">
                                    <span class="badge rounded-pill @if($psychologist->status == 'active') bg-success @elseif($psychologist->status == 'inactive') bg-danger @else bg-warning @endif">
                                        {{ $psychologist->status == 'active' ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="px-4 text-center">
                                    <button class="btn btn-outline-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editPsychologistModal"
                                            data-id="{{ $psychologist->id }}"
                                            data-name="{{ $psychologist->name }}"
                                            data-email="{{ $psychologist->email }}"
                                            data-document_number="{{ $psychologist->document_number }}"
                                            data-appointment_price="{{ $psychologist->appointment_price }}"
                                            data-status="{{ $psychologist->status }}">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                        @if ($psychologists->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                        Nenhum psicólogo cadastrado.
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

<x-psychologist.create-psychologist />
<x-psychologist.edit-psychologist />

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editPsychologistModal = document.getElementById('editPsychologistModal');

        editPsychologistModal.addEventListener('show.bs.modal', function(event) {
            // Botão que acionou o modal
            const button = event.relatedTarget;

            // Extrai os dados dos atributos data-*
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const documentNumber = button.getAttribute('data-document_number');
            const appointmentPrice = button.getAttribute('data-appointment_price');
            const status = button.getAttribute('data-status');

            // Atualiza os campos do modal
            editPsychologistModal.querySelector('#edit_name').value = name;
            editPsychologistModal.querySelector('#edit_email').value = email;
            editPsychologistModal.querySelector('#edit_document_number').value = documentNumber;
            editPsychologistModal.querySelector('#edit_appointment_price').value = appointmentPrice;
            editPsychologistModal.querySelector('#edit_status').value = status;
            editPsychologistModal.querySelector('#psychologistID').value = id;




        });
    });
    </script>
<!-- JS Bootstrap local (ou substitua por asset se já estiver copiado para o projeto) -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@endsection
