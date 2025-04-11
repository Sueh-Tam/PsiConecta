@if($show)
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill text-success fs-1 me-3"></i>
                    <p class="mb-0">{{ $message }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('{{ $modalId }}'));
        modal.show();

        // Opcional: Redirecionamento após fechar
        document.getElementById('{{ $modalId }}').addEventListener('hidden.bs.modal', function () {
            @if(session('success_redirect'))
                window.location.href = "{{ session('success_redirect') }}";
            @endif
        });
    });
</script>
@endif
