@props([
    'title' => 'Tidak ada data',
    'message' => 'Belum ada data yang tersedia saat ini.',
    'actionLabel' => null,
    'actionUrl' => null,
])

<div class="flex flex-col items-center justify-center py-12 text-center px-4">
    <div class="mb-4 text-forest opacity-50">
        {{-- Minimalist outdoor SVG (Tent/Mountain) --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="m12 3 9 18H3l9-18Z"/>
            <path d="m12 12-3 9"/>
            <path d="M12 12h-3"/>
        </svg>
    </div>
    <h3 class="text-base font-medium mb-1" style="color: var(--color-forest);">{{ $title }}</h3>
    <p class="text-sm mb-4 max-w-md" style="color: var(--color-stone);">{{ $message }}</p>
    
    @if($actionLabel && $actionUrl)
        <x-button href="{{ $actionUrl }}">{{ $actionLabel }}</x-button>
    @endif
</div>
