@extends('layouts.dashboard')

@section('title', 'Minhas Consultas')

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
            <th>Data e Hora</th>
            <th>Psicólogo</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>12/04/2025 - 14:00</td>
            <td>Dr. Ana Paula</td>
            <td><span class="badge bg-success">Atendido</span></td>
        </tr>
        <tr>
            <td>15/04/2025 - 10:00</td>
            <td>Dr. João Mendes</td>
            <td><span class="badge bg-warning text-dark">Agendada</span></td>
        </tr>
        <tr>
            <td>10/04/2025 - 09:00</td>
            <td>Dr. Fernanda Silva</td>
            <td><span class="badge bg-danger">Falta</span></td>
        </tr>
    </tbody>
</table>

<!-- Modal de Agendamento -->
<div class="modal fade" id="agendarModal" tabindex="-1" aria-labelledby="agendarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agendarModalLabel">Agendar Consulta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="clinica" class="form-label">Clínica</label>
                    <select class="form-select" id="clinica" required>
                        <option selected disabled>Selecione a clínica</option>
                        <option value="1">Clínica Vida Plena</option>
                        <option value="2">Clínica Equilíbrio</option>
                        <option value="3">Clínica Bem Viver</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="psicologo" class="form-label">Psicólogo</label>
                    <select class="form-select" id="psicologo" required>
                        <option selected disabled>Selecione o psicólogo</option>
                        <option value="1">Dr. Ana Paula</option>
                        <option value="2">Dr. João Mendes</option>
                        <option value="3">Dr. Fernanda Silva</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="data" class="form-label">Dias disponíveis</label>
                    <input type="datetime-local" class="form-control" id="data" required>
                </div>
                <div class="mb-3">
                    <label for="data" class="form-label">Horários disponíveis</label>
                    <input type="datetime-local" class="form-control" id="data" required>
                </div>
                <div class="mb-3">
                    <label for="valor" class="form-label">Valor (R$)</label>
                    <input type="number" class="form-control" id="valor" placeholder="Ex: 120.00" step="0.01" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Confirmar</button>
            </div>
        </form>
    </div>
</div>

<!-- JS Bootstrap local (ou substitua por asset se já estiver copiado para o projeto) -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@endsection
