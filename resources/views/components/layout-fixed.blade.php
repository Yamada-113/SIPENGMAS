@props(['title' => 'Judul Halaman'])

<div class="container d-flex flex-column" style="height: calc(100vh - 70px); overflow: hidden;">
    <div class="flex-shrink-0 pt-4 mb-3">
        {{ $header }}
    </div>
    <div class="card shadow-sm border-0 mb-3 flex-grow-1 d-flex flex-column" style="overflow: hidden;">
        <div class="card-body p-0 d-flex flex-column" style="overflow-y: auto;">
            {{ $slot }}
        </div>
        @if (isset($footer))
            <div class="card-footer bg-white py-3 flex-shrink-0">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
