@props(['textColor' => 'text-gray-900'])

@php
    $logoPath = \App\Models\Setting::get('logo_path');
    $logoUrl = $logoPath ? \Illuminate\Support\Facades\Storage::url($logoPath) : null;
    // Déterminer la taille du texte en fonction de la hauteur du logo
    $logoClass = $attributes->get('class', 'h-9');
    if (str_contains($logoClass, 'h-10')) {
        $textSize = 'text-base sm:text-lg';
    } elseif (str_contains($logoClass, 'h-8')) {
        $textSize = 'text-xs sm:text-sm';
    } else {
        $textSize = 'text-sm sm:text-base';
    }
@endphp

<div class="flex items-center space-x-1 sm:space-x-2 min-w-0 max-w-full overflow-hidden">
    @if($logoUrl)
        <img src="{{ asset($logoUrl) }}" 
             alt="{{ config('app.name', 'Voicy Assistant') }}" 
             {{ $attributes->merge(['class' => 'h-full w-auto object-contain flex-shrink-0']) }}
             style="max-height: 100%; max-width: 50px; height: auto;">
    @else
        @php
            // Adapter la taille de l'icône selon la classe passée
            $height = $attributes->get('class', 'h-9');
            if (str_contains($height, 'h-10')) {
                $iconSize = 'w-10 h-10';
                $svgSize = 'w-6 h-6';
            } elseif (str_contains($height, 'h-8')) {
                $iconSize = 'w-8 h-8';
                $svgSize = 'w-5 h-5';
            } else {
                $iconSize = 'w-9 h-9';
                $svgSize = 'w-6 h-6';
            }
        @endphp
        <div class="{{ $iconSize }} rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center flex-shrink-0">
            <svg class="{{ $svgSize }} text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
            </svg>
        </div>
    @endif
    <span class="{{ $textSize }} font-bold {{ $textColor }} truncate min-w-0 max-w-full">{{ config('app.name', 'Voicy Assistant') }}</span>
</div>

