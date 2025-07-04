@extends('layouts.psychologist.dashboard')

@section('title', 'Minhas Consultas')

@section('content')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateFilter = document.getElementById('date-filter');
    const statusFilter = document.getElementById('status-filter');
    const applyFilters = document.getElementById('apply-filters');

    applyFilters.addEventListener('click', function() {
        const date = dateFilter.value;
        const status = statusFilter.value;
        
        window.location.href = `{{ route('psychologist.dashboard') }}?date=${date}&status=${status}`;
    });
});
</script>

<div class="container-fluid px-4">

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-primary mb-1">Próxima Consulta</h6>
                            @if($stats['next_appointment'])
                                <h5 class="mb-0">{{ \Carbon\Carbon::parse($stats['next_appointment']['date'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($stats['next_appointment']['start_time'])->format('H:i') }}</h5>
                                <small class="text-muted">{{ $stats['next_appointment']['patient']['name'] }}</small>
                            @else
                                <h5 class="mb-0">Nenhuma consulta agendada</h5>
                                <small class="text-muted">-</small>
                            @endif
                        </div>
                        <i class="bi bi-calendar-check text-primary fs-1 ms-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-success mb-1">Consultas Realizadas</h6>
                            <h5 class="mb-0">{{ $stats['completed_appointments'] }}</h5>
                            <small class="text-muted">Último mês</small>
                        </div>
                        <i class="bi bi-check-circle text-success fs-1 ms-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-warning mb-1">Próximos Agendamentos</h6>
                            <h5 class="mb-0">{{ $stats['pending_appointments'] }}</h5>
                            <small class="text-muted">Consultas pendentes</small>
                        </div>
                        <i class="bi bi-clock text-warning fs-1 ms-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 text-info">Consultas de Hoje - {{ now()->format('d/m/Y') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Horário</th>
                            <th class="px-4 py-3">Paciente</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @forelse($todayAppointments as $appointment)
                        
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock text-info me-2"></i>
                                        {{ $appointment->hr_Availability }}
                                    </div>
                                </td>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-info text-white me-2">{{ $appointment['patient']['initials'] }}</div>
                                        {{ $appointment['patient']['name'] }}
                                    </div>
                                </td>
                                <td class="px-4">
                                @switch($appointment['status'])
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
                                @endswitch
                                </td>
                                <td class="px-4 text-center">
                                    @if ($appointment['status'] != 'canceled_early' && $appointment['status']!= 'cancelled' && $appointment['status'] != 'canceled_late')
                                        <a href="{{ route('appointments.edit', $appointment['id']) }}" class="btn btn-sm {{ $appointment['status'] == 'scheduled' ? 'btn-success' : 'btn-secondary' }} me-2" title="Marcar como realizada">
                                            <i class="bi bi-check-circle"></i> {{ $appointment['status'] == 'scheduled' ? 'Realizar':'Visualizar' }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">
                                    <p class="text-muted mb-0">Nenhuma consulta agendada para hoje</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 text-primary">Lista de Consultas</h5>
                </div>
                <div class="col-auto d-flex gap-2">
                    <input type="date" id="date-filter" class="form-control" value="{{ request('date') }}">
                    <select id="status-filter" class="form-control">
                        <option value="">Todos os status</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Agendada</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Concluída</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    <button id="apply-filters" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Data e Hora</th>
                            <th class="px-4 py-3">Paciente</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-event text-primary me-2"></i>
                                        {{ \Carbon\Carbon::parse($appointment['date'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($appointment['start_time'])->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-info text-white me-2">{{ $appointment['patient']['initials'] }}</div>
                                        {{ $appointment['patient']['name'] }}
                                    </div>
                                </td>
                                <td class="px-4">
                                @switch($appointment['status'])
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
                                @endswitch
                                </td>
                                <td class="px-4 text-center">
                                    @if ($appointment['status'] != 'canceled_early' && $appointment['status']!= 'cancelled' && $appointment['status'] != 'canceled_late')
                                        <a href="{{ route('appointments.edit', $appointment['id']) }}" class="btn btn-sm {{ $appointment['status'] == 'scheduled' ? 'btn-success' : 'btn-secondary' }} me-2" title="Marcar como realizada">
                                            <i class="bi bi-check-circle"></i> {{ $appointment['status'] == 'scheduled' ? 'Realizar':'Visualizar' }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">
                                    <p class="text-muted mb-0">Nenhuma consulta encontrada</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($appointments->hasPages())
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                    <div class="small text-muted">
                        Mostrando {{ $appointments->firstItem() ?? 0 }} - {{ $appointments->lastItem() ?? 0 }} de {{ $appointments->total() }} resultados
                    </div>
                    <div>
                        {{ $appointments->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    

    <style>
        .border-left-primary {
            border-left: 4px solid #0d6efd;
        }
        .border-left-success {
            border-left: 4px solid #198754;
        }
        .border-left-warning {
            border-left: 4px solid #ffc107;
        }
        .avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</div>


<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cancelButtons = document.querySelectorAll('button.btn-danger[data-appointment-id]');
        const cancelEarlyButtons = document.querySelectorAll('button.btn-outline-danger[data-appointment-id]');
        

        cancelButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Tem certeza que deseja cancelar esta consulta?')) {
                    const appointmentId = this.getAttribute('data-appointment-id');
                    
                    fetch(`/psychologist/appointments/${appointmentId}/cancel`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao cancelar a consulta');
                    });
                }
            });
        });

        cancelEarlyButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Tem certeza que deseja cancelar antecipadamente esta consulta?')) {
                    const appointmentId = this.getAttribute('data-appointment-id');
                    
                    fetch(`/psychologist/appointments/${appointmentId}/canceledEarly`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao cancelar antecipadamente a consulta');
                    });
                }
            });
        });
    });
</script>
@endsection
