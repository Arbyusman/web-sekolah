@props([
    'type' => 'button',
    'color' => 'primary',
    'dataBsToggle' => '',
    'dataBsTarget' => '',
    'dataBsDismiss' => '',
    'indicator' => false,
    'label' => 'Submit'
])

<button
    type="{{ $type }}"
    {{ $attributes->class(['btn'])->merge(['class' => 'btn-' . $color]) }}
    @if ($dataBsToggle) data-bs-toggle="{{ $dataBsToggle }}" @endif
    @if ($dataBsTarget) data-bs-target="{{ $dataBsTarget }}" @endif
    @if ($dataBsDismiss) data-bs-dismiss="{{ $dataBsDismiss }}" @endif
>
    {{ $slot }}

    @if ($indicator || $type == 'submit')
        <span class="indicator-label">{{ $label }}</span>
        <span class="indicator-progress">Please wait...
            <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
        </span>
    @endif
</button>
