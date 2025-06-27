@extends('layouts.dashboard')

@section('title', 'Minhas Consultas')

@section('content')
<style>
    :root {
        --font-size: 16px;
    }
    * {
        font-size: var(--font-size);
    }
    h1, h2, h3, h4, h5, h6 {
        font-size: calc(var(--font-size) * 1.5) !important;
    }
    p, a, span, div, label, input, select, button, small, td, th {
        font-size: var(--font-size) !important;
    }
    .font-control {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: white;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 1000;
    }
    .font-control button {
        margin: 0 5px;
        padding: 5px 10px;
        border: none;
        background: #007bff;
        color: white;
        border-radius: 3px;
        cursor: pointer;
    }
    .font-control button:hover {
        background: #0056b3;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="container-fluid px-4">
    <!-- Cabeçalho da Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted mb-0">Gerencie suas consultas</p>
        </div>
        <button class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#agendarModal">
            <i class="bi bi-calendar-plus me-2"></i>
            Agendar Consulta
        </button>
    </div> 

    <!-- Cards de Resumo -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            @if($stats['next_appointment'])
                                <h5 class="mb-0">{{ \Carbon\Carbon::parse($stats['next_appointment']['dt_Availability'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($stats['next_appointment']['hr_Availability'])->format('H:i') }}</h5>
                                <small class="text-muted">{{ $stats['next_appointment']['psychologist']['name'] }}</small>
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

    <!-- Tabela de Consultas -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 text-primary">Lista de Consultas</h5>
                </div>
                <div class="col-auto d-flex gap-2">
                    <select id="psicologo-filter" class="form-control w-200px">
                        <option value="">Selecionar Psicólogo</option>
                        @foreach ($psychologists as $psychologist)
                            <option value="{{ $psychologist->id }}">{{ $psychologist->name }}</option>
                        @endforeach
                    </select>
                    <input type="date" id="date-filter" class="form-control">
                    <select id="status-filter" class="form-control">
                        <option value="">Todos os status</option>
                        <option value="scheduled">Agendada</option>
                        <option value="completed">Concluída</option>
                        <option value="canceled_early">Cancelamento Antecipado</option>
                        <option value="canceled_late">Cancelamento Tardio</option>
                    </select>
                    <button id="apply-filters" class="btn btn-primary">
                        <i class="bi bi-search"></i>Buscar
                    </button>
                    <button id="reset-filters" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Limpar
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
                            <th class="px-4 py-3">Clínica</th>
                            <th class="px-4 py-3">Psicólogo</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $appointment)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-event text-primary me-2"></i>
                                    {{  \Carbon\Carbon::parse($appointment->dt_Availability)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($appointment->hr_Availability)->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    {{  $appointment->psychologist()->first()->clinic->name }}
                                </div>
                            </td>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-2">{{ substr($appointment->psychologist()->first()->name, 3, 3) }}</div>
                                    {{ $appointment->psychologist()->first()->name  }}
                                </div>
                            </td>
                            <td class="px-4">
                                @switch($appointment->status)
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
                                @if ($appointment->status === 'scheduled')
                                    <button class="btn btn-sm btn-danger cancel-appointment" 
                                        data-appointment-id="{{ $appointment->id }}" 
                                        title="Cancelar consulta">
                                        <i class="bi bi-x-circle"></i> Cancelar
                                    </button>
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
                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                        <div class="small text-muted">
                            Mostrando {{ $appointments->firstItem() ?? 0 }} - {{ $appointments->lastItem() ?? 0 }} de {{ $appointments->total() }} resultados
                        </div>
                        <div>
                            {{ $appointments->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
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
        .w-200px {
            width: 200px !important;
        }
    </style>
</div>

<!-- Controles de Fonte -->
<div class="font-control">
    <button onclick="changeFontSize('decrease')">A-</button>
    <button onclick="changeFontSize('reset')">A</button>
    <button onclick="changeFontSize('increase')">A+</button>
</div>

<!-- Incluindo o componente de agendamento de consultas com clínica -->
@include('components.appointments.create-appointment-with-clinic')

<!-- Modal de Confirmação de Cancelamento -->
<!-- Carregando Bootstrap JS no final do documento para garantir que o DOM esteja carregado -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Confirmar Cancelamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja cancelar esta consulta?</p>
                <p class="text-warning"><small>Atenção: Cancelamentos com menos de 24 horas de antecedência podem resultar em cobrança.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                <button type="button" class="btn btn-danger" id="confirmCancel">Sim, cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Função para ajuste de fonte - movida para escopo global
    function changeFontSize(action) {
        const root = document.documentElement;
        const currentSize = parseInt(getComputedStyle(root).getPropertyValue('--font-size')) || 16;
        
        switch(action) {
            case 'increase':
                root.style.setProperty('--font-size', `${currentSize + 2}px`);
                break;
            case 'decrease':
                if (currentSize > 8) {
                    root.style.setProperty('--font-size', `${currentSize - 2}px`);
                }
                break;
            case 'reset':
                root.style.setProperty('--font-size', '16px');
                break;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Configuração do Toast de notificação
        function showToast(message, type = 'success') {
            const toastContainer = document.createElement('div');
            toastContainer.style.position = 'fixed';
            toastContainer.style.top = '20px';
            toastContainer.style.right = '20px';
            toastContainer.style.zIndex = '9999';
            
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            document.body.appendChild(toastContainer);
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', () => {
                document.body.removeChild(toastContainer);
            });
        }

        // Manipulação do cancelamento de consulta
        let appointmentToCancel = null;
        const cancelModalElement = document.getElementById('cancelModal');
        const cancelModal = new bootstrap.Modal(cancelModalElement);

        // Garantir que o modal seja destruído corretamente quando fechado
        cancelModalElement.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        });

        document.querySelectorAll('.cancel-appointment').forEach(button => {
            button.addEventListener('click', function() {
                appointmentToCancel = this.dataset.appointmentId;
                cancelModal.show();
            });
        });

        document.getElementById('confirmCancel').addEventListener('click', function() {
            if (!appointmentToCancel) return;
            console.log(appointmentToCancel);
            fetch(`/patient/cancel/${appointmentToCancel}`, {
                method: 'PUT',
                headers: {
                    
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                cancelModal.hide();
                showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 2000);
            })
            .catch(error => {
                showToast('Erro ao cancelar consulta. Tente novamente.', 'danger');
                console.error('Erro:', error);
            });
        });

        // Código existente dos filtros
        // Manipulação dos filtros
        const applyFiltersButton = document.getElementById('apply-filters');
        const resetFiltersButton = document.getElementById('reset-filters');

        resetFiltersButton.addEventListener('click', function() {
            window.location.href = window.location.pathname;
        });

        applyFiltersButton.addEventListener('click', function() {
            const psicologoFilter = document.getElementById('psicologo-filter').value;
            const dateFilter = document.getElementById('date-filter').value;
            const statusFilter = document.getElementById('status-filter').value;

            const params = new URLSearchParams(window.location.search);
            if (psicologoFilter) params.set('psicologo', psicologoFilter);
            if (dateFilter) params.set('date', dateFilter);
            if (statusFilter) params.set('status', statusFilter);

            window.location.href = `${window.location.pathname}?${params.toString()}`;
        });
    });
</script>
@endsection
