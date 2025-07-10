@props([
    'type' => 'button',
    'color' => 'primary',
    'dataBsToggle' => '',
    'dataBsTarget' => '',
    'dataBsDismiss' => '',
])

<button
    type="{{ $type }}"
    class="btn btn-{{ $color }}"
    @if ($dataBsToggle) data-bs-toggle="{{ $dataBsToggle }}" @endif
    @if ($dataBsTarget) data-bs-target="{{ $dataBsTarget }}" @endif
    @if ($dataBsDismiss) data-bs-dismiss="{{ $dataBsDismiss }}" @endif
>
    {{ $slot }}
</button>
