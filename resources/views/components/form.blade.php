@props([
    'title' => null,
    'header' => null,
    'action',
    'method' => 'POST',
    'enctype' => 'application/x-www-form-urlencoded',
    'buttonLabel' => 'Simpan',
])

<form action="{{ $action }}" method="{{ strtoupper($method) }}"
    {{ $enctype ? 'enctype=' . $enctype : '' }} {{ $attributes }}
    onsubmit="
        const btn = this.querySelector('button[type=submit]');
        btn.disabled = true;
        btn.innerHTML = `<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Loading...`;
    ">
    <div class="mt-4 d-flex justify-content-between gap-2">
        @if ($title || $header)
            <div class="mb-4 border-bottom pb-2">
                @if ($title)
                    <h3 class="fw-bold mb-0">{!! $title !!}</h3>
                @endif

                @if ($header)
                    <div class="text-muted small">{!! $header !!}</div>
                @endif
            </div>
        @endif
        <div class="mb-4 border-bottom pb-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>
                @include('partials/general/_button-indicator', ['label' => $buttonLabel])
            </button>

            <button type="reset" class="btn btn-light border">
                <i class="fas fa-undo me-1"></i>
                Reset
            </button>
        </div>
    </div>

    {{ $slot }}


</form>
