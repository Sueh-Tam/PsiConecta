@php
    $horarios = [
        '08:00', '09:00', '10:00', '11:00',
        '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'
    ];
@endphp

<div>
    <form action="{{ route('psychologist.availability.deactivate') }}" method="POST">
        @csrf
        <h5 class="mb-3">Inativar Disponibilidade</h5>

        <div class="mb-3">
            <label for="data_inicio" class="form-label">Data de Início da Inativação *</label>
            <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
        </div>

        <div class="mb-3">
            <label for="data_fim" class="form-label">Data de Fim da Inativação *</label>
            <input type="date" class="form-control" id="data_fim" name="data_fim" required>
        </div>

        <div class="mb-3">
            <label for="dia_semana" class="form-label">Dia da Semana (opcional)</label>
            <select class="form-control" name="dia_semana" id="dia_semana">
                <option value="">Todos</option>
                <option value="1">Segunda-feira</option>
                <option value="2">Terça-feira</option>
                <option value="3">Quarta-feira</option>
                <option value="4">Quinta-feira</option>
                <option value="5">Sexta-feira</option>
                <option value="6">Sábado</option>
                <option value="0">Domingo</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="hora_inicio" class="form-label">Hora de Início (opcional)</label>
            <select class="form-control" name="hora_inicio" id="hora_inicio">
                <option value="">Selecione</option>
                @foreach ($horarios as $hora)
                    <option value="{{ $hora }}">{{ $hora }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="hora_fim" class="form-label">Hora de Fim *</label>
            <select class="form-control" name="hora_fim" id="hora_fim" disabled required>
                <option value="">Selecione</option>
            </select>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-danger">Inativar Horários</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const horaInicio = document.getElementById('hora_inicio');
        const horaFim = document.getElementById('hora_fim');

        const horarios = [
            '08:00', '09:00', '10:00', '11:00',
            '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'
        ];

        horaInicio.addEventListener('change', () => {
            const valorSelecionado = horaInicio.value;
            horaFim.innerHTML = '<option value="">Selecione</option>';

            if (valorSelecionado) {
                const indexInicio = horarios.indexOf(valorSelecionado);
                const horariosFiltrados = horarios.slice(indexInicio + 1);

                horariosFiltrados.forEach(hora => {
                    const option = document.createElement('option');
                    option.value = hora;
                    option.textContent = hora;
                    horaFim.appendChild(option);
                });

                horaFim.disabled = false;
                horaFim.setAttribute('required', true);
            } else {
                horaFim.disabled = true;
                horaFim.removeAttribute('required');
            }
        });
    });
</script>
