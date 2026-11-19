@blaze
@props(['id', 'title', 'count' => null, 'sort', 'group', 'color' => null])

@php
    // accent | danger | warning | success | info | neutral: tints this column's cards.
    $columnColor = $color ? '--ob-column: var(--color-'.$color.')' : null;
@endphp

<section {{ $attributes->merge(['class' => 'w-[85vw] sm:w-80 shrink-0 snap-start rounded-panel bg-surface-1/60 border border-hairline', 'style' => $columnColor]) }} aria-labelledby="ob-kanban-{{ $id }}">
    <header class="flex items-center justify-between gap-2 px-3 pt-3 pb-1">
        <h3 id="ob-kanban-{{ $id }}" class="min-w-0 truncate text-sm font-medium text-fg">{{ $title }}</h3>
        @if ($count !== null)
            <x-ob::badge>{{ $count }}</x-ob::badge>
        @endif
    </header>
    <div class="relative">
        <div wire:sort="{{ $sort }}" wire:sort:group="{{ $group }}" wire:sort:group-id="{{ $id }}" class="flex min-h-24 flex-col gap-2 p-2">
            {{ $slot }}
        </div>
        @if (isset($empty) && trim((string) $slot) === '')
            {{-- Overlaid, not a list child, so drops still land in the sortable list. --}}
            <div class="pointer-events-none absolute inset-0 grid place-items-center p-4 text-center text-xs text-fg-3">{{ $empty }}</div>
        @endif
    </div>
</section>
