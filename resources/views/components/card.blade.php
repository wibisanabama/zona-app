@props([
    'variant' => 'base',
    'padding' => true,
])

@php
    $classes = match($variant) {
        'raised' => 'card-raised',
        'kpi' => 'card-kpi',
        default => 'card-base',
    };
@endphp

<div {{ $attributes->merge(['class' => $classes . ($padding ? '' : ' !p-0')]) }}>
    {{ $slot }}
</div>
