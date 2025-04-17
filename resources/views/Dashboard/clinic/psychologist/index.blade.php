@extends('layouts.clinic.dashboard')

@section('title', 'Psicólogos')

@section('content')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

<div class="d-flex justify-content-between align-items-center mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPsychologistModal">
        Cadastrar psicólogo
    </button>
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
<table class="table table-bordered table-striped shadow-sm rounded">
    <thead class="table-primary">
        <tr>
            <th>Nome</th>
            <th>CRP</th>
            <th>Valor da consulta</th>
            <th>Status</th>
            <th>Detalhes</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($psychologists as $psychologist)
            <tr>
                <td>{{ $psychologist->name }}</td>
                <td>{{ $psychologist->formatDocumentCRP($psychologist->document_number) }}</td>
                <td>R${{ $psychologist->appointment_price }}</td>
                <td><span class="badge @if ($psychologist->status == 'active') bg-success @elseif ($psychologist->status == 'inactive') bg-danger @else bg-warning @endif">{{ $psychologist->status }}</span></td>
                <td>
                    <div class="d-flex justify-content-center ">
                        <button class="btn btn-info"
                                data-bs-toggle="modal"
                                data-bs-target="#editPsychologistModal"
                                data-id="{{ $psychologist->id }}"
                                data-name="{{ $psychologist->name }}"
                                data-email="{{ $psychologist->email }}"
                                data-document_number="{{ $psychologist->document_number }}"
                                data-appointment_price="{{ $psychologist->appointment_price }}"
                                data-status="{{ $psychologist->status }}">
                            Editar psicólogo
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach

        @if ($psychologists->isEmpty())
            <tr>
                <td colspan="5" class="text-center">Nenhum psicólogo cadastrado.</td>
            </tr>
        @endif

    </tbody>
</table>
<x-psychologist.create-psychologist />
<x-psychologist.edit-psychologist />
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
