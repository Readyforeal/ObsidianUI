@props(['href'])

{{-- The one "back to the list" control: sits above a page title, optically aligned with its left edge. --}}
<div class="-ml-2 mb-4">
    <a
        href="{{ $href }}"
        wire:navigate
        {{ $attributes->merge(['class' => 'inline-flex h-8 items-center gap-1 rounded-full pl-2 pr-3 text-[13px] font-medium text-fg-2 transition duration-120 ease-ob hover:bg-surface-3 hover:text-fg focus-ring max-sm:h-11']) }}
    >
        <x-ob::icon name="chevron-left" class="size-4" />
        {{ $slot }}
    </a>
</div>
