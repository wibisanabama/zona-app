@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => '— Pilih —',
    'required' => false,
    'disabled' => false,
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

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'select-base ' . ($errors->has($name) ? 'input-error' : '')]) }}
        @required($required)
        @disabled($disabled)
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $optVal => $optLabel)
            <option value="{{ $optVal }}" @selected(old($name, $value) == $optVal)>{{ $optLabel }}</option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1 text-xs" style="color: var(--color-danger);">{{ $message }}</p>
    @enderror
</div>
