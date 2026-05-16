@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'hint' => null,
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium mb-1.5" style="color: var(--color-forest);">
            {{ $label }}
            @if($required)
                <span style="color: var(--color-danger);">*</span>
            @endif
        </label>
    @endif

    @if($type === 'textarea')
        <textarea
            id="{{ $name }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'input-base ' . ($errors->has($name) ? 'input-error' : '')]) }}
            style="height: auto; min-height: 80px;"
            @required($required)
            @disabled($disabled)
        >{{ old($name, $value) }}</textarea>
    @else
        <input
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'input-base ' . ($errors->has($name) ? 'input-error' : '')]) }}
            @required($required)
            @disabled($disabled)
        >
    @endif

    @if($hint)
        <p class="mt-1 text-xs" style="color: var(--color-stone);">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-xs" style="color: var(--color-danger);">{{ $message }}</p>
    @enderror
</div>
