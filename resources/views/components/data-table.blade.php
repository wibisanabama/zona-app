@props([
    'headers' => [],
    'empty' => 'Belum ada data.',
    'emptyIcon' => null,
])

<div class="card-base !p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if(isset($isEmpty) && $isEmpty)
        <div class="py-12 text-center">
            @if($emptyIcon)
                <div class="mb-3">{!! $emptyIcon !!}</div>
            @else
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto" style="color: var(--color-dove);"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                </div>
            @endif
            <p class="text-sm" style="color: var(--color-stone);">{{ $empty }}</p>
        </div>
    @endif
</div>
