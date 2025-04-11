@extends('layouts.dashboard')

@section('title', 'Minhas Consultas')

@section('content')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
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
@endsection
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

