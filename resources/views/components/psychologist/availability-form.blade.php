<div class="container mt-4" style="max-width: 800px;">
    <h5>Cadastrar Disponibilidade</h5>

    <form method="POST" action="{{ route('psychologist.disponibility.store') }}">
        @csrf
        <div class="mb-3">
            <label for="day_of_week" class="form-label">Dia da Semana</label>
            <select class="form-select" id="day_of_week" name="day_of_week" required>
                <option value="" disabled selected>Selecione o dia</option>
                <option value="segunda">Segunda-feira</option>
                <option value="terca">Terça-feira</option>
                <option value="quarta">Quarta-feira</option>
                <option value="quinta">Quinta-feira</option>
                <option value="sexta">Sexta-feira</option>
                <option value="sabado">Sábado</option>
                <option value="domingo">Domingo</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="start_time" class="form-label">Data de Início</label>
            <input type="date" class="form-control" id="start_time" name="dt_start" required>
        </div>

        <div class="mb-3">
            <label for="end_time" class="form-label">Data de Fim</label>
            <input type="date" class="form-control" id="end_time" name="dt_end" required>
        </div>
        @props(['timeBlocks'])
        <div class="mb-3">
            <label class="form-label">Selecione os horários disponíveis</label>
            <div class="row">
                @foreach($timeBlocks as $block)
                    @php
                        [$start, $end] = explode('-', $block);
                    @endphp
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input horario-checkbox" type="checkbox" id="check_{{ $loop->index }}">
                            <label class="form-check-label" for="check_{{ $loop->index }}">
                                {{ $block }}
                            </label>
                            <input type="hidden" name="start_time[]" value="{{ $start }}" disabled>
                            <input type="hidden" name="end_time[]" value="{{ $end }}" disabled>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Salvar Disponibilidade</button>
        </div>
    </form>
</div>

<script>
    function atualizarRemocoes() {
        const botoes = document.querySelectorAll('.btn-remove-horario');
        if (botoes.length === 1) {
            botoes[0].setAttribute('disabled', 'true');
            botoes[0].setAttribute('title', 'Mínimo de 1 horário');
        } else {
            botoes.forEach(btn => {
                btn.removeAttribute('disabled');
                btn.removeAttribute('title');
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.horario-checkbox').forEach(function (checkbox, index) {
            checkbox.addEventListener('change', function () {
                const hiddenStart = document.getElementsByName('start_time[]')[index];
                const hiddenEnd = document.getElementsByName('end_time[]')[index];
                hiddenStart.disabled = !this.checked;
                hiddenEnd.disabled = !this.checked;
            });
        });
    });

    document.addEventListener('DOMContentLoaded', atualizarRemocoes);
</script>
