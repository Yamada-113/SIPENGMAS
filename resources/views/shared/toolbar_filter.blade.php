<form action="{{ url()->current() }}" method="GET" class="d-flex">
    @if (request('tab'))
        <input type="hidden" name="tab" value="{{ request('tab') }}">
    @endif
    @if (request('year'))
        <input type="hidden" name="year" value="{{ request('year') }}">
    @endif
    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
    <div class="input-group shadow-sm">
        <input type="text" name="search" class="form-control border-0 px-3" placeholder="Cari judul..."
            value="{{ request('search') }}" style="min-width: 250px;">
        <button class="btn btn-primary px-3" type="submit">
            <i class="bi bi-search"></i>
        </button>
    </div>
</form>

@if (isset($years) && count($years) > 0)
    <div class="dropdown shadow-sm ms-2">
        <button class="btn btn-white border dropdown-toggle bg-white text-dark" type="button" id="dropdownYear"
            data-bs-toggle="dropdown" aria-expanded="false">
            Tahun: <strong>{{ $selectedYear ?? date('Y') }}</strong>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            @foreach ($years as $y)
                <li>
                    --}}
                    <a href="{{ request()->fullUrlWithQuery(['year' => $y]) }}"
                        class="dropdown-item {{ ($selectedYear ?? date('Y')) == $y ? 'active' : '' }}"
                        onclick="if(typeof changeYear === 'function') { changeYear({{ $y }}); return false; }">
                        {{ $y }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
