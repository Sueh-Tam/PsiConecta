@extends('layouts.dashboard')

@section('title', 'Editar Perfil')

@section('content')
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

    <form action="{{ Route('patient.profile.update') }}" method="POST">
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
            <label for="documento" class="form-label">Número do Documento</label>
            <input type="text" class="form-control" id="document_number" name="document_number" placeholder="000.000.000-00" maxlength="14" value="{{ Auth::user()->document_number }}" required>
            <small id="erro-documento" class="text-danger d-none">CPF inválido. Digite os 11 números.</small>
        </div>

        <div class="mb-3">
            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="1990-01-01">
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Nova Senha</label>
            <input type="password" class="form-control" id="senha" name="password" minlength="6">
            <small class="form-text text-muted">Deixe em branco se não quiser alterar a senha.</small>
        </div>

        <div class="mb-3">
            <label for="senha_confirmation" class="form-label">Repetir Senha</label>
            <input type="password" class="form-control" id="senha_confirmation" name="password_confirmation" minlength="6">
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>

    <hr class="my-4">

    <form action="{{ Route('patient.profile.delete') }}" method="POST" onsubmit="return confirm('Tem certeza que deseja inativar sua conta?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Inativar Conta</button>
    </form>
</div>
@endsection
<script>
    //formatar e validar cpf
    document.addEventListener("DOMContentLoaded", function () {
        const input = document.getElementById("document_number");
        const erro = document.getElementById("erro-documento");
        const form = input.closest("form");

        function formatarCPF(valor) {
            let value = valor.replace(/\D/g, "");
            if (value.length > 11) value = value.slice(0, 11);
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            return value;
        }

        input.addEventListener("input", function () {
            input.value = formatarCPF(input.value);
            if (input.value.replace(/\D/g, "").length === 11) {
                erro.classList.add("d-none");
            }
        });

        // Formata ao carregar
        input.value = formatarCPF(input.value);

        // Validação no envio
        form.addEventListener("submit", function (e) {
            if (input.value.replace(/\D/g, "").length < 11) {
                e.preventDefault();
                erro.classList.remove("d-none");
                input.focus();
            }
        });
    });



</script>
