@extends('layouts.psychologist.dashboard')

@section('title', 'Minhas Consultas')

@section('content')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>


    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCriarHorario">
        Cadastrar Horário
    </button>
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalInativarHorario">
        Inativar Horários
    </button>
    <div class="modal fade" id="modalInativarHorario" tabindex="-1" aria-labelledby="modalInativarHorarioLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalInativarHorarioLabel">Inativar Horários</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
              <x-psychologist.deactivate-availability-form />
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="modalCriarHorario" tabindex="-1" aria-labelledby="modalCriarHorarioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCriarHorarioLabel">Cadastrar Disponibilidade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <x-psychologist.availability-form />
                </div>
            </div>
        </div>
    </div>

  <div class="container mt-4">
    <!-- Abas de dias da semana -->
    <ul class="nav nav-tabs" id="diasSemanaTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-Segunda" data-bs-toggle="tab" data-bs-target="#conteudo-Segunda" type="button" role="tab">Segunda</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-Terça" data-bs-toggle="tab" data-bs-target="#conteudo-Terça" type="button" role="tab">Terça</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-Quarta" data-bs-toggle="tab" data-bs-target="#conteudo-Quarta" type="button" role="tab">Quarta</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-Quinta" data-bs-toggle="tab" data-bs-target="#conteudo-Quinta" type="button" role="tab">Quinta</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-Sexta" data-bs-toggle="tab" data-bs-target="#conteudo-Sexta" type="button" role="tab">Sexta</button>
        </li>
    </ul>

    <!-- Conteúdo de cada aba -->
    <div class="tab-content mt-3" id="diasSemanaTabsContent">
        <!-- Segunda -->
        <div class="tab-pane fade show active" id="conteudo-Segunda" role="tabpanel">
            <h5>Segunda-feira</h5>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    29/04/2025 - 10:00
                    <span class="badge bg-success">Available</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    29/04/2025 - 14:30
                    <span class="badge bg-secondary">Unavailable</span>
                </li>
            </ul>
        </div>

        <!-- Terça -->
        <div class="tab-pane fade" id="conteudo-Terça" role="tabpanel">
            <h5>Terça-feira</h5>
            <p class="text-muted">Nenhum horário cadastrado.</p>
        </div>

        <!-- Quarta -->
        <div class="tab-pane fade" id="conteudo-Quarta" role="tabpanel">
            <h5>Quarta-feira</h5>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    01/05/2025 - 11:00
                    <span class="badge bg-success">Available</span>
                </li>
            </ul>
        </div>

        <!-- Quinta, Sexta, Sábado, Domingo -->
        <div class="tab-pane fade" id="conteudo-Quinta" role="tabpanel">
            <h5>Quinta-feira</h5>
            <p class="text-muted">Nenhum horário cadastrado.</p>
        </div>
        <div class="tab-pane fade" id="conteudo-Sexta" role="tabpanel">
            <h5>Sexta-feira</h5>
            <p class="text-muted">Nenhum horário cadastrado.</p>
        </div>
    </div>
</div>


<!-- JS Bootstrap local (ou substitua por asset se já estiver copiado para o projeto) -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@endsection
