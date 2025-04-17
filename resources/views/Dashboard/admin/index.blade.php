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
                        <button class="btn btn-info"
                                data-bs-toggle="modal"
                                data-bs-target="#editarClinicaModal"
                                data-id="{{ $clinic->id }}"
                                data-name="{{ $clinic->name }}"
                                data-email="{{ $clinic->email }}"
                                data-document="{{ $clinic->document_number }}"
                                data-situacao="{{ $clinic->situation }}"
                                data-status="{{ $clinic->status }}">
                            Detalhes da Clínica
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
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
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@endsection
