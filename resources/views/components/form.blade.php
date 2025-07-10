@props([
    'title' => null,
    'action',
    'method' => 'POST',
    'enctype' => 'application/x-www-form-urlencoded',
    'buttonLabel' => 'Simpan',
    'isModal' => true,
])

<form action="{{ $action }}" method="{{ strtoupper($method) }}" {{ $enctype ? 'enctype=' . $enctype : '' }}
    {{ $attributes }}>
    @if (!$isModal)
        <div class="mt-4 d-flex justify-content-between gap-2">
            @if ($title)
                <div class="mb-4 border-bottom pb-2">
                    <div class="card-title m-0">
                        {!! $title !!}
                    </div>
                </div>
            @endif

            <div class="mb-4 border-bottom pb-2">
                <button type="submit" class="btn btn-primary indicator">
                    <span class="indicator-label">
                        <i class="fas fa-save me-1"></i>
                        {{ $buttonLabel }}
                    </span>
                    <span class="indicator-progress">
                        <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                        Mohon tunggu...
                    </span>
                </button>

                <button type="reset" class="btn btn-light border">
                    <i class="fas fa-undo me-1"></i>
                    Reset
                </button>
            </div>
        </div>
    @endif

    {{ $slot }}
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');

            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const btn = this.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.setAttribute('data-kt-indicator', 'on');
                        btn.disabled = true;

                        setTimeout(() => {
                            btn.removeAttribute('data-kt-indicator');
                            btn.disabled = false;
                        }, 3000);
                    }
                });
            });
        });
    </script>
@endpush
