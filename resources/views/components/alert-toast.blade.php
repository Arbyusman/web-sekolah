@if(session('success'))
    <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3 z-index-999">

        <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-toast-stack>
            <div class="toast-header">
                <i class="ki-duotone ki-check-circle fs-2 text-success me-3"><span class="path1"></span><span class="path2"></span></i>
                <strong class="me-auto">Success</strong>
                <small>{{ now()->format('H:i') }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('toast-container');
            const template = container.querySelector('[data-toast-stack]');

            const toast = new bootstrap.Toast(template, { delay: 3000 });
            toast.show();
        });
    </script>
@endif
