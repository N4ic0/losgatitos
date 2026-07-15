@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
$alignmentClasses = match ($align) {
    'left' => 'dropdown-menu-start',
    'top' => 'dropdown-menu-top',
    default => 'dropdown-menu-end',
};
@endphp

<div class="dropdown">
    <div data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <div class="dropdown-menu {{ $contentClasses }} {{ $alignmentClasses }} rounded-md shadow-lg border-0"
         style="{{ $width === '48' ? 'min-width: 12rem;' : '' }}">
        {{ $content }}
    </div>
</div>
