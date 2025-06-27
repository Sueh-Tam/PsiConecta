
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mb-2">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $error }}
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($errors->isNotEmpty())
            const modalElement = document.getElementById('{{ $modalId }}');
            const errorModal = new bootstrap.Modal(modalElement);
            errorModal.show();
            
            // Garantir que o modal seja destruído corretamente quando fechado
            modalElement.addEventListener('hidden.bs.modal', function () {
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            });
        @endif
    });
</script>
