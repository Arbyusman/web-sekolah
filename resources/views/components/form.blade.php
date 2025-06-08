<form action="{{ $action }}" method="{{ $method === 'GET' ? 'GET' : 'POST' }}"
    @if ($enctype) enctype="{{ $enctype }}" @endif>
    @if ($method !== 'GET' && $method !== 'POST')
        @method($method)
    @endif

    @csrf

    {{ $slot }}
</form>
