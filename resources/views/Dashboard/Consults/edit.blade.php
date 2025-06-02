@extends('layouts.psychologist.dashboard')

@section('title', 'Editar Consulta')

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
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('appointments.update', $appointment) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="card-title mb-3">Informações do Paciente</h5>
                        <p><strong>Nome:</strong> {{ $appointment->patient->name }}</p>
                        <p><strong>Email:</strong> {{ $appointment->patient->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="card-title mb-3">Detalhes da Consulta</h5>
                        <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($appointment->dt_avaliability)->format('d/m/Y') }}</p>
                        <p><strong>Horário:</strong> {{ $appointment->hr_avaliability }}</p>
                        <p><strong>Status:</strong> @switch($appointment['status'])
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
                                <span class="badge bg-secondary">{{ ucfirst($appointment['status']) }}</span>
                        @endswitch</p>
                        <p><strong>Pacote:</strong> {{ $appointment->package->name }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="card-title mb-3">Prontuário Médico</h5>
                    <div class="form-group">
                        <textarea class="form-control" id="medical_record" name="medical_record" rows="6" placeholder="Digite as observações do atendimento aqui..." {{ $appointment->status !== 'scheduled' ? 'disabled' : '' }}>{{ old('medical_record', $appointment->medical_record) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ Route('psychologist.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                    <div>
                        <button type="submit" name="status" value="completed" class="btn btn-success" {{ $appointment->status !== 'scheduled' ? 'disabled' : '' }}>
                            <i class="bi bi-check-circle"></i> Marcar como Realizada
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

@endsection