@props(['clinics'])
<x-success-modal
        modal-id="patientSuccessModal"
        title="Sucesso"
        message="{{ session('success_message') }}"
    />
    <x-error-modal
    modal-id="patientErrorModal"
    title="Erro"
    message="{{ session('error_message') }}"/>
<div class="modal fade" id="comprarPacoteModal" tabindex="-1" aria-labelledby="comprarPacoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="comprarPacoteModalLabel">Comprar Pacote de Consultas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form id="formCompraPacote" method="POST" action="{{ Route('clinic.packages.store') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="patient_id" value="{{ Auth::user()->id }}">
                    <div class="mb-3">
                        <label for="clinic_id" class="form-label">Clínica</label>
                        <select class="form-select" id="clinic_id" name="clinic_id" required>
                            <option value="">Selecione uma clínica</option>
                             @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="psychologist_id_{{ Auth::user()->id }}" class="form-label">Psicólogo</label>
                        <select class="form-select" id="psychologist_id_{{ Auth::user()->id }}" name="psychologist_id" required>
                            <option value="">Selecione um psicólogo</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="total_appointments_{{ Auth::user()->id }}" class="form-label">Quantidade de Sessões</label>
                        <input type="number" class="form-control" id="total_appointments_{{ Auth::user()->id }}" name="total_appointments" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Valor Total</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="text" class="form-control" id="price" name="price" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Método de Pagamento</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_pix" value="pix" checked>
                                <label class="form-check-label" for="payment_pix">PIX</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_money" value="money">
                                <label class="form-check-label" for="payment_money">Dinheiro</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_health_plan" value="health_plan">
                                <label class="form-check-label" for="health_plan">Plano de Saúde</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Comprar Pacote</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const selectPsychologist = document.getElementById('psychologist_id_{{ Auth::user()->id }}');
    const inputTotalAppointments = document.getElementById('total_appointments_{{ Auth::user()->id }}');
    const inputPrice = document.getElementById('price_{{ Auth::user()->id }}');

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



    const clinicSelect = document.getElementById('clinic_id');
    const psychologistSelect = document.getElementById('psychologist_id_{{ Auth::user()->id }}');
    const totalAppointmentsInput = document.getElementById('total_appointments_{{ Auth::user()->id }}');
    const priceInput = document.getElementById('price');

    // Atualizar lista de psicólogos quando uma clínica é selecionada
    clinicSelect.addEventListener('change', function() {
        const clinicId = this.value;
        if (clinicId) {
            
            fetch(`/api/clinic/${clinicId}/psychologists`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                psychologistSelect.innerHTML = '<option value="">Selecione um psicólogo</option>';
                if (Array.isArray(data)) {
                    data.forEach(psychologist => {
                        psychologistSelect.innerHTML += `<option value="${psychologist.id}" data-price="${psychologist.appointment_price}">${psychologist.name}</option>`;
                    });
                } else if (data.psychologists && Array.isArray(data.psychologists)) {
                    data.psychologists.forEach(psychologist => {
                        psychologistSelect.innerHTML += `<option value="${psychologist.id}" data-price="${psychologist.appointment_price}">${psychologist.name}</option>`;
                    });
                } else {
                    console.error('Formato de dados inválido:', data);
                    psychologistSelect.innerHTML = '<option value="">Erro: Formato de dados inválido</option>';
                }
            })
            .catch(error => {
                console.error('Erro ao carregar psicólogos:', error);
                psychologistSelect.innerHTML = '<option value="">Erro ao carregar psicólogos</option>';
            });
        } else {
            psychologistSelect.innerHTML = '<option value="">Selecione um psicólogo</option>';
            updateTotalPrice();
        }
    });

    // Calcular valor total quando quantidade de sessões ou psicólogo é alterado
    function updateTotalPrice() {
        const selectedPsychologist = psychologistSelect.options[psychologistSelect.selectedIndex];
        const sessionPrice = selectedPsychologist ? parseFloat(selectedPsychologist.dataset.price) : 0;
        const totalSessions = parseInt(totalAppointmentsInput.value) || 0;
        const totalPrice = sessionPrice * totalSessions;
        
        priceInput.value = totalPrice.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    psychologistSelect.addEventListener('change', updateTotalPrice);
    totalAppointmentsInput.addEventListener('input', updateTotalPrice);
});
</script>