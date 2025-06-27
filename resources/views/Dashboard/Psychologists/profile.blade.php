@extends('layouts.psychologist.dashboard')

@section('title', 'Minhas Consultas')

@section('content')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<x-error-modal
    modal-id="patientErrorModal"
    title="Erro"
/>
<x-success-modal
    modal-id="patientSuccessModal"
    title="Cadastro Atualziado!"
    message="{{ session('success_message') }}"
/>
<div class="container mt-4">
    <h2 class="mb-4">Editar Perfil</h2>

    <form action="{{ Route('clinic.psychologist.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nome" class="form-label">Nome completo</label>
            <input type="text" class="form-control" id="nome" name="name" value="{{ Auth::user()->name }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
        </div>

        <div class="mb-3">
            <label for="documento" class="form-label">Número do Documento CRP</label>
            <input type="text" class="form-control" id="document_number" name="document_number" placeholder="00.000-00" maxlength="9" value="{{ Auth::user()->formatDocumentCRP(Auth::user()->document_number) }}" required>
            <small id="erro-documento" class="text-danger d-none">CPF inválido. Digite os 11 números.</small>
        </div>
        <div class="mb-3">
            <label for="appointment_price" class="form-label">Valor da Consulta</label>
            <input type="text" class="form-control" id="appointment_price" name="appointment_price" placeholder="R$ 0,00" value="{{ Auth::user()->appointment_price }}" required>
            <small id="erro-valor" class="text-danger d-none">Valor inválido. Digite o valor no formato R$ 0,00.</small>
        </div>
        <div class="mb-3">
            <label for="birth_date" class="form-label">Data de Nascimento</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date" value="1990-01-01">
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Nova Senha</label>
            <input type="password" class="form-control" id="senha" name="password" minlength="6">
            <small class="form-text text-muted">Deixe em branco se não quiser alterar a senha.</small>
        </div>

        <div class="mb-3">
            <label for="senha_confirmation" class="form-label">Repetir Senha</label>
            <input type="password" class="form-control" id="senha_confirmation" name="password_confirmation " minlength="6">
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>

    <hr class="my-4">


</div>

<!-- JS Bootstrap local (ou substitua por asset se já estiver copiado para o projeto) -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@endsection
