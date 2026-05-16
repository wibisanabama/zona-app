@props([
    'type' => 'button',
    'variant' => 'primary',
    'href' => null,
    'disabled' => false,
])

@php
    $classes = match($variant) {
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'danger' => 'btn-danger',
        'icon' => 'btn-icon',
        default => 'btn-primary',
    };
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @disabled($disabled)>
        {{ $slot }}
    </button>
@endif
