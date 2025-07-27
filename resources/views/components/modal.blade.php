@props([
    'id' => 'modal-id',
    'title' => 'Modal Title',
    'size' => '',
    'hideHeader' => false,
])

<div class="modal fade" tabindex="-1" id="{{ $id }}" aria-hidden="true">
    <div class="modal-dialog {{ $size ? 'modal-' . $size : '' }} modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            @unless($hideHeader)
                <div class="modal-header">
                    <h3 class="modal-title">{{ $title }}</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                </div>
            @endunless

            <div class="modal-body">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset

        </div>
    </div>
</div>
