<div class="container mt-4">
    <h5>Cadastrar Disponibilidade</h5>

    <form>
        <div class="mb-3">
          <label for="dia_semana" class="form-label">Dia da Semana</label>
          <select class="form-select" id="dia_semana" name="dia_semana" required>
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
          <label for="data_inicio" class="form-label">Data de Início</label>
          <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
        </div>

        <div class="mb-3">
          <label for="data_fim" class="form-label">Data de Fim</label>
          <input type="date" class="form-control" id="data_fim" name="data_fim" required>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="hora_inicio" class="form-label">Horário de Início</label>
            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
          </div>

          <div class="col-md-6 mb-3">
            <label for="hora_fim" class="form-label">Horário de Fim</label>
            <input type="time" class="form-control" id="hora_fim" name="hora_fim" required>
          </div>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-success">Salvar Disponibilidade</button>
        </div>
      </form>

</div>

<!-- Script para habilitar horários -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const diaSelect = document.getElementById('dia_semana');
        const inicioInput = document.getElementById('hora_inicio');
        const fimInput = document.getElementById('hora_fim');

        diaSelect.addEventListener('change', function () {
            const isSelected = this.value !== '';
            inicioInput.disabled = !isSelected;
            fimInput.disabled = !isSelected;
        });
    });
</script>
