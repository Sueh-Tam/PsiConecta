@props(['packages'])
<div class="modal fade" id="agendarModal" tabindex="-1" aria-labelledby="agendarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="appointmentForm" method="POST" action="{{ route('appointments.store') }}">
            @csrf
            <input type="hidden" name="patient_id" value="{{ Auth::user()->id }}">
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
                    <label for="psychologist" class="form-label">Psicólogo</label>
                    <select class="form-select" id="psychologist" name="psychologist_id" required>
                        <option value="">Selecione um psicólogo</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->psychologist->id }}" data-package="{{ $package }}" data-appointments-left="{{ $package->balance }}">{{ $package->psychologist->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3" id="appointmentsLeftInfo" style="display: none;">
                    <div class="alert alert-info">
                        <strong>Saldo:</strong> <span id="appointmentsLeft">0</span> consultas restantes
                    </div>
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

        

        

        psychologistSelect.addEventListener('change', function() {
            daySelect.innerHTML = '<option value="">Selecione um dia</option>';
            daySelect.disabled = true;
            timeSelect.value = '';
            timeSelect.disabled = true;

            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                const appointmentsLeft = selectedOption.getAttribute('data-appointments-left');
                const appointmentsLeftInfo = document.getElementById('appointmentsLeftInfo');
                console.log(appointmentsLeft);
                // if (appointmentsLeft && appointmentsLeftSpan) {
                //     appointmentsLeftSpan.textContent = appointmentsLeft;
                //     appointmentsLeftInfo.style.display = 'block';
                // } else {
                //     appointmentsLeftInfo.style.display = 'none';
                // }
                
                fetch(`/patient/api/psychologist/${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        // console.log(this.value)
                        // console.log(data);
                        if (data.psychologist) {
                            availableDays = data.available_days;
                            availableTimes = data.available_times;
                            daySelect.innerHTML = '<option value="">Selecione um dia</option>';
                            availableDays.forEach(day => {
                                const option = document.createElement('option');
                                option.value = day;
                                option.textContent = getDayName(day);
                                daySelect.appendChild(option);
                            });
                            
                            daySelect.disabled = false;
                            //if (data.package && data.package.balance ) {
                                
                                
                                appointmentsLeftSpan.textContent = appointmentsLeft ;
                                appointmentsLeftInfo.style.display = 'block';
                            //}
                        }
                    })
                    .catch(error => console.error('Erro:', error));
                
                daySelect.disabled = false;
            } else {
                document.getElementById('appointmentsLeftInfo').style.display = 'none';
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

        // Função para converter número do dia em nome
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