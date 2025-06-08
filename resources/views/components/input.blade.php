<div class="mb-4">
    @if ($label)
        <label for="{{ $name }}" class="form-label fw-semibold">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        class="form-control {{ $class }}"
    >

    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>
