@props(['heading'])

<div role="group" aria-label="{{ $heading }}" {{ $attributes->merge(['class' => 'not-first:mt-3.5']) }}>
    <h2 x-show="!collapsed" class="px-3.5 pb-1 text-xs font-medium text-fg-3">{{ $heading }}</h2>
    <div x-show="collapsed" x-cloak class="mx-3.5 mb-2 h-px bg-hairline" aria-hidden="true"></div>
    <div class="flex flex-col gap-0.5">{{ $slot }}</div>
</div>
