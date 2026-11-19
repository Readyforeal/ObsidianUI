{{-- Lucide icons (https://lucide.dev/icons). One outline style everywhere, so there is no variant. --}}
@props(['name'])

@php
    $callerClass = (string) $attributes->get('class', '');
    $hasSizeUtility = preg_match('/(^|\s)(?:[\w-]+:)*(size|w|h)-/', $callerClass) === 1;
    $classes = trim(($hasSizeUtility ? '' : 'size-5 ').'shrink-0 '.$callerClass);
@endphp

{{ svg('lucide-'.$name, $classes, $attributes->except('class')->merge(['aria-hidden' => 'true'])->getAttributes()) }}
