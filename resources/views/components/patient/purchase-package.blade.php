@props(['patient'])
@props(['psychologists'])
<div class="modal fade" id="modalPurchasePackage-{{ $patient->id }}" tabindex="-1" aria-labelledby="modalPurchasePackageLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ Route('clinic.packages.store') }}" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <input type="hidden" name="psychologist_id" value="{{ auth()->user()->id }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalPurchasePackageLabel">Renovar pacote - {{ $patient->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <input type="text" name="patient_id" value="{{ $patient->id }}" hidden>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="psychologist_id" class="form-label">Psicólogo</label>
                        <select name="psychologist_id" id="psychologist_id_{{ $patient->id }}" class="form-select select-psychologist" data-patient="{{ $patient->id }}" required>
                            <option value="">Selecione</option>
                            @foreach ($psychologists as $psychologist)
                                <option value="{{ $psychologist->id }}" data-price="{{ $psychologist->appointment_price }}">
                                    {{ $psychologist->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="total_appointments" class="form-label">Total de Sessões</label>
                        <input type="number" name="total_appointments" id="total_appointments_{{ $patient->id }}" class="form-control" required min="1">
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Valor do Pacote (R$)</label>
                        <input type="number" name="price" id="price_{{ $patient->id }}" class="form-control" step="0.01" value="0" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Método de Pagamento</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="pix">Pix</option>
                            <option value="cash">Dinheiro</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Confirmar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const selectPsychologist = document.getElementById('psychologist_id_{{ $patient->id }}');
    const inputTotalAppointments = document.getElementById('total_appointments_{{ $patient->id }}');
    const inputPrice = document.getElementById('price_{{ $patient->id }}');

    // Função para atualizar o preço
    function updatePrice() {
        // Obtém o preço do psicólogo selecionado
        const selectedOption = selectPsychologist.options[selectPsychologist.selectedIndex];
        const appointmentPrice = parseFloat(selectedOption.getAttribute('data-price') || 0);

        // Obtém o número total de sessões
        const totalAppointments = parseInt(inputTotalAppointments.value, 10);

        // Calcula o valor do pacote
        const totalPrice = appointmentPrice * totalAppointments;

        // Atualiza o campo de preço
        inputPrice.value = totalPrice.toFixed(2);
    }

    // Adiciona evento de mudança no select do psicólogo
    selectPsychologist.addEventListener('change', updatePrice);

    // Adiciona evento de mudança no campo de número de sessões
    inputTotalAppointments.addEventListener('input', updatePrice);
});

</script>
