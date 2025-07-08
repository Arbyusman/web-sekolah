@props([
    'title' => null,
    'header' => null,
    'toolbar' => null,
    'body' => null,
    'footer' => null,
])

<div {{ $attributes->merge(['class' => 'card shadow-sm']) }}>

    @if ($title || $header || $toolbar)
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="card-title m-0">
                {!! $title ?? '' !!}
                {!! $header ?? '' !!}
            </div>
            @if ($toolbar)
                <div class="card-toolbar">
                    {!! $toolbar !!}
                </div>
            @endif
        </div>
    @endif

    @if ($body)
        <div class="card-body">
            {!! $body !!}
        </div>
    @endif

    @if ($footer)
        <div class="card-footer">
            {!! $footer !!}
        </div>
    @endif

</div>
