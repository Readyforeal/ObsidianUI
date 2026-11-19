{{-- Collapsible navigation rail; reads and writes `collapsed` from the surrounding <x-ob::app-shell>. --}}
<aside
    x-bind:class="{ 'w-16!': collapsed }"
    {{ $attributes->merge(['class' => 'flex h-full w-60 flex-col border-r border-hairline transition-[width] duration-200 ease-ob']) }}
>
    {{-- 22px left and top: the brand lines up with the item icons (nav px-2 + item px-3.5) and sits as far from the top as from the side. --}}
    <div x-bind:class="{ 'pl-3!': collapsed }" class="flex shrink-0 items-center gap-2 pt-5.5 pr-3 pb-3 pl-5.5">
        <div x-show="!collapsed" class="min-w-0 flex-1 truncate">{{ $brand ?? '' }}</div>
        <div x-show="!collapsed" class="shrink-0">
            <x-ob::button variant="ghost" size="sm" icon="chevrons-left" label="Collapse sidebar" x-on:click="collapsed = true" />
        </div>
        <div x-show="collapsed" x-cloak class="mx-auto shrink-0">
            <x-ob::button variant="ghost" size="sm" icon="chevrons-right" label="Expand sidebar" x-on:click="collapsed = false" />
        </div>
    </div>

    {{-- 2px between rows: close enough to feel continuous, apart enough that a hovered row never merges with the selected one. --}}
    <nav aria-label="Primary" class="flex flex-1 flex-col gap-0.5 px-2 py-2">
        {{ $slot }}
    </nav>

    @isset($status)
        <div class="shrink-0 px-3 py-3">{{ $status }}</div>
    @endisset

    @isset($footer)
        <div class="shrink-0 border-t border-hairline p-2">{{ $footer }}</div>
    @endisset
</aside>
