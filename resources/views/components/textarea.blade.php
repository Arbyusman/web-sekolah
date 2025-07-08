@props(['name', 'label' => '', 'value' => '', 'placeholder' => '', 'required' => false, 'class' => '', 'rows' => 3])

<div class="mb-4">
    @isset($label)
        <label for="{{ $name }}" class="form-label fw-semibold">
            {{ $label }}
            @if (!empty($required))
                <span class="text-danger">*</span>
            @endif
        </label>
    @endisset

    <textarea name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}" rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-control form-control-solid ' . $class]) }}>{{ old($name, $value) }}</textarea>

    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>
