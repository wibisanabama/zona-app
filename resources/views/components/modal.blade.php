@props([
    'name',
    'title' => '',
    'maxWidth' => 'md',
])

@php
    $widthClass = match($maxWidth) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        default => 'max-w-md',
    };
@endphp

<div
    x-data="{ open: false }"
    x-on:open-modal-{{ $name }}.window="open = true"
    x-on:close-modal-{{ $name }}.window="open = false"
    x-on:keydown.escape.window="open = false"
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[60] bg-black/50"
        @click="open = false"
        x-cloak
    ></div>

    {{-- Modal Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="fixed inset-0 z-[70] flex items-center justify-center p-4"
        x-cloak
    >
        <div class="bg-white rounded-2xl w-full {{ $widthClass }} max-h-[85vh] overflow-y-auto" style="box-shadow: var(--shadow-maximum);" @click.stop>
            {{-- Header --}}
            @if($title)
                <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--color-mist);">
                    <h3 class="text-lg font-semibold" style="color: var(--color-forest);">{{ $title }}</h3>
                    <button @click="open = false" class="btn-icon" aria-label="Tutup">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
            @endif

            {{-- Body --}}
            <div class="px-6 py-4">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            @if(isset($footer))
                <div class="flex items-center justify-end gap-2 px-6 py-4 border-t" style="border-color: var(--color-mist);">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
