@props([
    'size' => 'md', // xs (28px), sm (32px), md (36-40px), lg (48px), xl (64px)
    'variant' => 'filled' // filled (badge with border/shadow), transparent (raw transparent PNG image)
])

@php
    $containerSizes = [
        'xs' => 'w-7 h-7 rounded-lg',
        'sm' => 'w-8 h-8 rounded-xl',
        'md' => 'w-9 h-9 sm:w-10 sm:h-10 rounded-xl',
        'lg' => 'w-12 h-12 rounded-2xl',
        'xl' => 'w-16 h-16 rounded-3xl',
    ];

    $containerClass = $containerSizes[$size] ?? $containerSizes['md'];
@endphp

@if($variant === 'transparent')
    <img src="{{ asset('images/logo-transparent.png') }}"
         alt="UrbanPulse Logo"
         {{ $attributes->merge(['class' => 'shrink-0 object-contain ' . $containerClass]) }} />
@else
    <div {{ $attributes->merge(['class' => 'relative flex items-center justify-center bg-white shrink-0 overflow-hidden group-hover:scale-105 transition-transform duration-200 p-0.5 ' . $containerClass]) }}>
        <img src="{{ asset('images/logo.png') }}"
             alt="UrbanPulse Logo"
             class="w-full h-full object-cover rounded-[inherit]" />
    </div>
@endif
