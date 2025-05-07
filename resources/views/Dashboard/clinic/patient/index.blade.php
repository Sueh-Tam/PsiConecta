@extends('layouts.clinic.dashboard')

@section('title', 'Pacientes')

@section('content')
<div class="container">
    <x-error-modal
    modal-id="patientErrorModal"
    title="Erro na compra do Pacote"
    message="{{ session('error_message') }}"
    />
    <!-- Modal de Sucesso -->
    <x-success-modal
        modal-id="patientSuccessModal"
        title="Compra feita com sucesso!"
        message="{{ session('success_message') }}"
    />
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCreatePatient">
            + Cadastrar Paciente
        </button>
    </div>

    <div class="modal fade" id="modalCreatePatient" tabindex="-1" aria-labelledby="modalCreatePatientLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreatePatientLabel">Create Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <x-patient.form />
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive" style="min-height: 400px; max-height: 70vh; overflow-y: auto;">
                <table class="table table-hover align-middle">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>Name</th>
                            <th>Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patients as $patient)

                            <tr>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->formartDocumentCPF($patient->document_number) }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Ações
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal-{{ $patient->id }}">
                                                Editar perfil
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalPurchasePackage-{{ $patient->id }}">Renovar pacote</a></li>
                                            <li><a class="dropdown-item" href="">Inativar paciente</a></li>
                                        </ul>
                                    </div>
                                    <x-patient.purchase-package :patient="$patient" :psychologists="$psychologists" />
                                </td>
                            </tr>
                            <div class="modal fade" id="editModal-{{ $patient->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $patient->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content p-4">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel-{{ $patient->id }}">Editar paciente</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <x-patient.form-update :patient="$patient" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No patients found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
