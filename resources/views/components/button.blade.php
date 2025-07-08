@props([
    'type' => 'button',
    'color' => 'primary',
    'dataBsToggle' => '',
    'dataBsTarget' => '',
])

<button
    type="{{ $type }}"
    class="btn btn-{{ $color }}"
    @if($dataBsToggle) data-bs-toggle="{{ $dataBsToggle }}" @endif
    @if($dataBsTarget) data-bs-target="{{ $dataBsTarget }}" @endif
>
    {{ $slot }}
</button>
