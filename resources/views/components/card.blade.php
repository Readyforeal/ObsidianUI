@blaze
@props(['padding' => true])

<div data-ob-reveal {{ $attributes->merge(['class' => 'rounded-panel border border-hairline bg-surface-1'.($padding ? ' p-5 max-sm:p-4' : '')]) }}>
    {{ $slot }}
</div>
