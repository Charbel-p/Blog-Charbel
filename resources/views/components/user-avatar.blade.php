@props(['user', 'size' => 'sm'])

@php
    $sizes = [
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-16 h-16 text-lg',
        'lg' => 'w-24 h-24 text-3xl',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['sm'];
@endphp

@if($user->hasProfilePhoto())
    <img src="{{ $user->profilePhotoUrl() }}" alt="{{ $user->name }}"
         {{ $attributes->merge(['class' => "$sizeClass rounded-full object-cover border border-brand-100 shrink-0"]) }}>
@else
    <div {{ $attributes->merge(['class' => "$sizeClass rounded-full bg-brand-600 flex items-center justify-center text-white font-semibold shrink-0"]) }}>
        {{ $user->profileInitial() }}
    </div>
@endif
