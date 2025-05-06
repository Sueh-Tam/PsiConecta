<form action="{{ route('psychologist.availability.restore') }}" method="POST">
    @csrf
    <h5 class="mb-3">Restaurar Disponibilidade</h5>

    <div class="mb-3">
        <label for="data_inicio" class="form-label">Data de Início *</label>
        <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
    </div>

    <div class="mb-3">
        <label for="data_fim" class="form-label">Data de Fim *</label>
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
    @php
        $horarios = [
            '08:00', '09:00', '10:00', '11:00',
            '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'
        ];
    @endphp
    <div class="mb-3">
        <label for="hora_inicio" class="form-label">Hora de Início (opcional)</label>
        <select class="form-control" name="hora_inicio" id="hora_inicio_restore">
            <option value="">Selecione</option>
            @foreach ($horarios as $hora)
                <option value="{{ $hora }}">{{ $hora }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="hora_fim" class="form-label">Hora de Fim *</label>
        <select class="form-control" name="hora_fim" id="hora_fim_restore" disabled required>
            <option value="">Selecione</option>
        </select>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-success">Restaurar Horários</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const horaInicio = document.getElementById('hora_inicio_restore');
        const horaFim = document.getElementById('hora_fim_restore');

        const horarios = [
            '08:00', '09:00', '10:00', '11:00',
            '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'
        ];

        horaInicio.addEventListener('change', () => {
            const valor = horaInicio.value;
            horaFim.innerHTML = '<option value="">Selecione</option>';

            if (valor) {
                const startIndex = horarios.indexOf(valor);
                horarios.slice(startIndex + 1).forEach(hora => {
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
