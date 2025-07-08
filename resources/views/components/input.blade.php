@props(['name', 'label', 'value', 'placeholder', 'type' => 'text', 'required' => false, 'class' => ''])

<div class="mb-4">
    @isset($label)
        <label for="{{ $name }}" class="form-label fw-semibold">
            {{ $label }}
            @if (!empty($required))
                <span class="text-danger">*</span>
            @endif
        </label>
    @endisset

    <input type="{{ $type ?? 'text' }}" name="{{ $name }}" id="{{ $name }}"
        value="{{ old($name, $value ?? '') }}" placeholder="{{ $placeholder ?? '' }}"
        {{ !empty($required) ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-control ' . ($class ?? '')]) }}>

    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>
