@props([
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select an option',
    'allowClear' => true,
    'multiple' => false,
    'searchable' => false,
    'apiUrl' => null,
    'theme' => 'default',
    'disabled' => false,
])

@php
    $classes =
        [
            'default' => 'form-select',
            'transparent' => 'form-select form-select-transparent',
            'solid' => 'form-select form-select-solid',
        ][$theme] ?? 'form-select';

    $select2Options = [
        'data-control' => 'select2',
        'data-placeholder' => $placeholder,
        'data-allow-clear' => json_encode($allowClear),
    ];

    if ($searchable && $apiUrl) {
        $select2Options['data-ajax--url'] = $apiUrl;
        $select2Options['data-ajax--cache'] = 'true';
        $select2Options['data-minimum-input-length'] = '1';
    }
@endphp

<select name="{{ $name }}" id="{{ $name }}" class="{{ $classes }}"
    @foreach ($select2Options as $key => $value)
        {{ $key }}="{{ $value }}" @endforeach
    @if ($multiple) multiple @endif @if ($disabled) disabled @endif>
    <option></option>
    @if (!$searchable)
        @foreach ($options as $key => $value)
            <option value="{{ $key }}" @selected($key == $selected)>{{ $value }}</option>
        @endforeach
    @endif
</select>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#{{ $name }}').select2({
                @if ($searchable && $apiUrl)
                    ajax: {
                        url: '{{ $apiUrl }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        id: item.id,
                                        text: item.name
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                @endif
                placeholder: '{{ $placeholder }}',
                allowClear: {{ $allowClear ? 'true' : 'false' }},
                minimumInputLength: {{ $searchable ? '1' : '0' }},
                templateResult: function(data) {
                    if (data.loading) return data.text;
                    return data.text;
                },
                templateSelection: function(data) {
                    return data.text;
                }
            });
        });
    </script>
@endpush
