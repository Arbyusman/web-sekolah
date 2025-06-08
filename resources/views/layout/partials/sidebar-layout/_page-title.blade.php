<!--begin::Page title-->
<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
	<!--begin::Title-->
	<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{ $title ?? '' }}</h1>
	<!--end::Title-->
	<!--begin::Breadcrumb-->
	<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <li class="breadcrumb-item text-muted">
            <a href="{{ url('/') }}" class="text-muted text-hover-primary">Dashboards</a>
        </li>

        @php
            $segments = Request::segments();
            $url = '';
        @endphp

        @foreach ($segments as $index => $segment)
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            @php
                $url .= '/' . $segment;
                $isLast = $loop->last;
            @endphp

            <li class="breadcrumb-item {{ $isLast ? 'text-muted' : '' }}">
                @if ($isLast)
                    {{ ucfirst($segment) }}
                @else
                    <a href="{{ $url }}" class="text-muted text-hover-primary">
                        {{ ucfirst($segment) }}
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
	<!--end::Breadcrumb-->
</div>
<!--end::Page title-->
