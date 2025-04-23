@extends('layouts.clinic.dashboard')

@section('title', 'Atendentes')

@section('content')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

<div class="d-flex justify-content-between align-items-center mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAttendantModal">
        Cadastrar Atendente
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
            <th>CPF</th>
            <th>Status</th>
            <th>Detalhes</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($attendants as $attendant)
            <tr>
                <td>{{ $attendant->name }}</td>
                <td>{{ $attendant->formartDocumentCPF($attendant->document_number) }}</td>
                <td><span class="badge @if ($attendant->status == 'active') bg-success @elseif ($attendant->status == 'inactive') bg-danger @else bg-warning @endif">{{ $attendant->status }}</span></td>
                <td class="text-center">
                    <!-- Botão para abrir modal de edição -->
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAttendantModal-{{ $attendant->id }}">
                        Editar
                    </button>
                </td>
            </tr>
            <!-- Modal de Edição (inclusa para cada atendente) -->
            @include('components.attendant.edit-attendant', ['attendant' => $attendant])
        @endforeach

        @if ($attendants->isEmpty())
            <tr>
                <td colspan="5" class="text-center">Nenhum Atendente cadastrado.</td>
            </tr>
        @endif

    </tbody>
</table>
<x-attendant.create-attendant />

<!-- JS Bootstrap local (ou substitua por asset se já estiver copiado para o projeto) -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@endsection
