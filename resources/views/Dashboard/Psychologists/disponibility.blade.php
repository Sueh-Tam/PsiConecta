@extends('layouts.psychologist.dashboard')

@section('title', 'Minhas Consultas')

@section('content')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<x-error-modal
    modal-id="patientErrorModal"
    title="Erro"
/>
<x-success-modal
    modal-id="patientSuccessModal"
    title="Todos os horários foram cadastrados!"
    message="{{ session('success_message') }}"
/>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCriarHorario">
        Cadastrar Horário
    </button>
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalInativarHorario">
        Inativar Horários
    </button>
    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalRestaurarHorario">
        Restaurar Disponibilidade
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCriarHorarioLabel">Cadastrar Disponibilidade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <x-psychologist.availability-form :timeBlocks="$timeBlocks"/>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalRestaurarHorario" tabindex="-1" aria-labelledby="modalRestaurarHorarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRestaurarHorarioLabel">Restaurar Disponibilidade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <x-psychologist.restore-availability-form  />
                </div>
            </div>
        </div>
    </div>

  <div class="container mt-4">
    <!-- Abas de dias da semana -->
    @php
    $diasSemana = ['segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira'];
@endphp

<ul class="nav nav-tabs" id="diasSemanaTabs" role="tablist">
    @foreach($diasSemana as $index => $dia)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                    id="tab-{{ ucfirst($dia) }}"
                    data-bs-toggle="tab"
                    data-bs-target="#conteudo-{{ ucfirst($dia) }}"
                    type="button"
                    role="tab">
                {{ ucfirst($dia) }}
            </button>
        </li>
    @endforeach
</ul>

<div class="tab-content mt-3" id="diasSemanaTabsContent">
    @foreach($diasSemana as $index => $dia)
        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="conteudo-{{ ucfirst($dia) }}" role="tabpanel">
            <h5>{{ ucfirst($dia) }}</h5>
            @php
                $diaDisponibilidades = $groupedAvailabilities[$dia] ?? collect();
            @endphp

            @if($diaDisponibilidades->isEmpty())
                <p class="text-muted">Nenhum horário cadastrado.</p>
            @else
                <ul class="list-group">
                    @foreach($diaDisponibilidades as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ \Carbon\Carbon::parse($item->dt_Availability)->format('d/m/Y') }} - {{ $item->hr_Availability }}
                            <span class="badge {{ $item->status === 'available' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($item->status) == "Available" ? 'Disponível' : 'Indisponível' }}
                            </span>
                            <form action="{{ route('psychologist.disponibility.delete', $item->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este horário?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endforeach
</div>
</div>


<!-- JS Bootstrap local (ou substitua por asset se já estiver copiado para o projeto) -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@endsection
