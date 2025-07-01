<div class="modal fade" id="agendarModal" tabindex="-1" aria-labelledby="agendarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="appointmentForm" method="POST" action="{{ route('appointments.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="agendarModalLabel">Agendar Consulta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-3">
                    <label for="patient" class="form-label">Paciente</label>
                    <select class="form-select" id="patient" name="patient_id" required>
                        <option value="">Selecione um paciente</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" data-package="{{ $patient->active_package }}" data-appointments-left="{{ $patient->appointments_left }}">{{ $patient->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted" id="appointmentsLeft"></small>
                </div>

                <div class="mb-3">
                    <label for="psychologist" class="form-label">Psicólogo</label>
                    <select class="form-select" id="psychologist" name="psychologist_id" disabled required>
                        <option value="">Selecione um paciente primeiro</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="day_of_week" class="form-label">Dia da Semana</label>
                    <select class="form-select" id="day_of_week" name="day_of_week" disabled required>
                        <option value="">Selecione um psicólogo primeiro</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="time" class="form-label">Horário Disponível</label>
                    <select class="form-select" id="time" name="time" disabled required>
                        <option value="">Selecione um dia primeiro</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Agendar</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const appointmentForm = document.getElementById('appointmentForm');

        appointmentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    window.location.reload();
                } else {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger';
                    errorDiv.innerHTML = `<ul class="mb-0"><li>${result.message || 'Ocorreu um erro ao agendar a consulta.'}</li></ul>`;
                    
                    const oldAlerts = this.querySelectorAll('.alert');
                    oldAlerts.forEach(alert => alert.remove());
                    
                    const modalBody = this.querySelector('.modal-body');
                    modalBody.insertBefore(errorDiv, modalBody.firstChild);
                }
            } catch (error) {
                console.error('Erro:', error);
            }
        });

        const patientSelect = document.getElementById('patient');
        const psychologistSelect = document.getElementById('psychologist');
        const daySelect = document.getElementById('day_of_week');
        const timeSelect = document.getElementById('time');
        const appointmentsLeftSpan = document.getElementById('appointmentsLeft');

        let availableDays = [];
        let availableTimes = {};

        patientSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const appointmentsLeft = selectedOption.dataset.appointmentsLeft;
            console.log(appointmentsLeft);
            // if (appointmentsLeft !== undefined) {
            //     appointmentsLeftSpan.textContent = `Consultas restantes: ${appointmentsLeft}`;
            // } else {
            //     appointmentsLeftSpan.textContent = '';
            // }

            psychologistSelect.value = '';
            psychologistSelect.disabled = true;
            daySelect.value = '';
            daySelect.disabled = true;
            timeSelect.value = '';
            timeSelect.disabled = true;

            if (this.value) {
                fetch(`/patient/api/patients/${this.value}/psychologist`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.psychologist) {
                            psychologistSelect.innerHTML = '<option value="">Selecione um psicólogo</option>';
                            
                            const option = document.createElement('option');
                            option.value = data.psychologist.id;
                            option.textContent = data.psychologist.name;
                            psychologistSelect.appendChild(option);
                            
                            availableDays = data.available_days;
                            availableTimes = data.available_times;
                            
                            daySelect.innerHTML = '<option value="">Selecione um dia</option>';
                            availableDays.forEach(day => {
                                const option = document.createElement('option');
                                option.value = day;
                                option.textContent = getDayName(day);
                                daySelect.appendChild(option);
                            });
                            psychologistSelect.disabled = false;
                            psychologistSelect.value = data.psychologist.id;
                            daySelect.disabled = false;
                        }
                    })
                    .catch(error => console.error('Erro:', error));
            }
        });

        daySelect.addEventListener('change', function() {
            timeSelect.innerHTML = '<option value="">Selecione um horário</option>';
            timeSelect.disabled = true;

            if (this.value) {
                const times = availableTimes[this.value] || [];
                times.forEach(time => {
                    const option = document.createElement('option');
                    option.value = time;
                    option.textContent = `${time}`;
                    timeSelect.appendChild(option);
                });
                timeSelect.disabled = false;
            }
        });

        function getDayName(dayNumber) {
            const days = {
                0: 'Domingo',
                1: 'Segunda-feira',
                2: 'Terça-feira',
                3: 'Quarta-feira',
                4: 'Quinta-feira',
                5: 'Sexta-feira',
                6: 'Sábado'
            };
            return days[dayNumber] || dayNumber;
        }
    });
</script>
