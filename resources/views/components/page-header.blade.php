@props([
    'title',
    'subtitle' => null,
])

<div class="page-header">
    <div>
        <h1>{{ $title }}</h1>
        @if($subtitle)
            <p>{{ $subtitle }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
