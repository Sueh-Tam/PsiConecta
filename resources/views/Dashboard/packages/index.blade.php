@extends('layouts.dashboard')

@section('title', 'Meus Pacotes')

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

@include('components.modals.buy-package')

<div class="container-fluid px-4">
    <!-- Cabeçalho da Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted mb-0">Gerencie seus pacotes de consulta</p>
        </div>
        <button class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#comprarPacoteModal">
            <i class="bi bi-bag-plus me-2"></i>
            Comprar Pacote
        </button>
    </div> 

    <!-- Cards de Resumo -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-primary mb-1">Total de Pacotes</h6>
                            <h5 class="mb-0">{{ $stats['total_packages'] ?? 0 }}</h5>
                            <small class="text-muted">Pacotes ativos</small>
                        </div>
                        <i class="bi bi-box text-primary fs-1 ms-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-success mb-1">Consultas Disponíveis</h6>
                            <h5 class="mb-0">{{ $stats['total_available_appointments'] ?? 0 }}</h5>
                            <small class="text-muted">Total em todos os pacotes</small>
                        </div>
                        <i class="bi bi-calendar-check text-success fs-1 ms-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-warning mb-1">Investimento Total</h6>
                            <h5 class="mb-0">R$ {{ number_format($stats['total_investment'] ?? 0, 2, ',', '.') }}</h5>
                            <small class="text-muted">Em pacotes ativos</small>
                        </div>
                        <i class="bi bi-currency-dollar text-warning fs-1 ms-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tabela de Pacotes -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 text-primary">Lista de Pacotes</h5>
                </div>
                <div class="col-auto d-flex gap-2">
                    <select id="psychologist-filter" class="form-control">
                        <option value="">Selecionar Psicólogo</option>
                        
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Psicólogo</th>
                            <th class="px-4 py-3">Clínica</th>
                            <th class="px-4 py-3">Total de Consultas</th>
                            <th class="px-4 py-3">Saldo</th>
                            <th class="px-4 py-3">Preço</th>
                            <th class="px-4 py-3">Método de Pagamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($packages as $package)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-2">{{ substr($package->psychologist->name, 0, 2) }}</div>
                                    {{ $package->psychologist->name }}
                                </div>
                            </td>
                            <td class="px-4">
                                {{ $package->psychologist->clinic->name }}
                            </td>
                            <td class="px-4">
                                {{ $package->total_appointments }}
                            </td>
                            <td class="px-4">
                                {{ $package->total_appointments-$package->balance }}
                            </td>
                            <td class="px-4">
                                R$ {{ number_format($package->price, 2, ',', '.') }}
                            </td>
                            <td class="px-4">
                                {{ $package->payment_method=='pix' ? 'Pix': 'Dinheiro' }}
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3">
                                    <p class="text-muted mb-0">Nenhum pacote encontrado</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($packages->hasPages())
                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                        <div class="small text-muted">
                            Mostrando {{ $packages->firstItem() ?? 0 }} - {{ $packages->lastItem() ?? 0 }} de {{ $packages->total() }} resultados
                        </div>
                        <div>
                            {{ $packages->appends(request()->query())->links('pagination::bootstrap-4') }}
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

<!-- Controles de Fonte -->
<div class="font-control">
    <button onclick="changeFontSize('decrease')">A-</button>
    <button onclick="changeFontSize('reset')">A</button>
    <button onclick="changeFontSize('increase')">A+</button>
</div>

<!-- JS Bootstrap local -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<script>
    // Função para ajuste de fonte
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
        const psychologistFilter = document.getElementById('psychologist-filter');

        psychologistFilter.addEventListener('change', function() {
            const psychologistId = this.value;
            window.location.href = `${window.location.pathname}?psychologist=${psychologistId}`;
        });
    });
</script>
@endsection