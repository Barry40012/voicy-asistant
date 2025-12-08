<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 leading-tight flex items-center">
                    <i class="fas fa-headphones text-primary-600 mr-3 text-2xl"></i>
                    {{ __('Mes Audios') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Tous vos messages vocaux traités</p>
            </div>
            <a href="{{ route('dashboard.test-audio.index') }}" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-bold rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105" data-aos="fade-left">
                <i class="fas fa-flask mr-2"></i>
                <span class="hidden sm:inline">Tester un Audio</span>
                <span class="sm:hidden">Tester</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($audios->count() > 0)
                <div class="bg-white/95 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-2xl border-2 border-gray-100" data-aos="fade-up">
                    <div class="p-4 sm:p-6 lg:p-8">
                        <!-- Header Stats -->
                        <div class="mb-6 flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl p-3 shadow-lg">
                                    <i class="fas fa-microphone-alt text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-medium">Total d'audios</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $audios->total() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @php
                                    $doneCount = $audios->where('status', 'done')->count();
                                    $processingCount = $audios->where('status', 'processing')->count();
                                    $errorCount = $audios->where('status', 'error')->count();
                                @endphp
                                <div class="bg-green-100 rounded-lg px-3 py-2">
                                    <p class="text-xs text-green-700 font-semibold">{{ $doneCount }} Traités</p>
                                </div>
                                @if($processingCount > 0)
                                <div class="bg-yellow-100 rounded-lg px-3 py-2">
                                    <p class="text-xs text-yellow-700 font-semibold">{{ $processingCount }} En cours</p>
                                </div>
                                @endif
                                @if($errorCount > 0)
                                <div class="bg-red-100 rounded-lg px-3 py-2">
                                    <p class="text-xs text-red-700 font-semibold">{{ $errorCount }} Erreurs</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3 sm:space-y-4">
                            @foreach($audios as $index => $audio)
                                <a href="{{ route('dashboard.audios.show', $audio) }}" 
                                   class="group block p-4 sm:p-5 border-2 border-gray-200 rounded-xl hover:border-primary-300 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 bg-white" 
                                   data-aos="fade-up" 
                                   data-aos-delay="{{ $index * 50 }}">
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                                            <div class="flex-shrink-0">
                                                @if($audio->status === 'done')
                                                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300 animate-pulse-glow">
                                                        <i class="fas fa-check-circle text-white text-xl sm:text-2xl"></i>
                                                    </div>
                                                @elseif($audio->status === 'processing')
                                                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                    </div>
                                                @elseif($audio->status === 'error')
                                                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                        <i class="fas fa-exclamation-triangle text-white text-xl sm:text-2xl"></i>
                                                    </div>
                                                @else
                                                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-gray-400 to-gray-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                        <i class="fas fa-clock text-white text-xl sm:text-2xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors truncate">
                                                        Audio de {{ $audio->sender_phone }}
                                                    </h3>
                                                </div>
                                                <div class="flex flex-wrap items-center gap-2 text-xs sm:text-sm text-gray-500">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-calendar-alt mr-1"></i>
                                                        {{ $audio->created_at->format('d/m/Y H:i') }}
                                                    </span>
                                                    @if($audio->analysis && $audio->analysis->summary)
                                                        <span class="hidden sm:inline">•</span>
                                                        <span class="truncate max-w-xs">
                                                            <i class="fas fa-file-alt mr-1"></i>
                                                            {{ Str::limit($audio->analysis->summary, 60) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3 flex-shrink-0">
                                            @if($audio->status === 'done')
                                                <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-green-500 to-green-600 text-white text-xs sm:text-sm font-bold rounded-full shadow-md flex items-center">
                                                    <i class="fas fa-check-circle mr-1.5"></i>
                                                    <span class="hidden sm:inline">Traité</span>
                                                    <span class="sm:hidden">OK</span>
                                                </span>
                                            @elseif($audio->status === 'processing')
                                                <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs sm:text-sm font-bold rounded-full shadow-md flex items-center animate-pulse">
                                                    <i class="fas fa-spinner mr-1.5 animate-spin"></i>
                                                    En cours
                                                </span>
                                            @elseif($audio->status === 'error')
                                                <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs sm:text-sm font-bold rounded-full shadow-md flex items-center">
                                                    <i class="fas fa-exclamation-triangle mr-1.5"></i>
                                                    Erreur
                                                </span>
                                            @else
                                                <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-gray-400 to-gray-500 text-white text-xs sm:text-sm font-bold rounded-full shadow-md flex items-center">
                                                    <i class="fas fa-clock mr-1.5"></i>
                                                    En attente
                                                </span>
                                            @endif
                                            <div class="text-primary-600 group-hover:text-primary-700 group-hover:translate-x-1 transition-all duration-300">
                                                <i class="fas fa-arrow-right text-lg"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($audios->hasPages())
                        <div class="mt-6 sm:mt-8 flex justify-center" data-aos="fade-up">
                            {{ $audios->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white/95 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-2xl border-2 border-gray-100" data-aos="zoom-in">
                    <div class="p-12 sm:p-16 text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-primary-100 to-secondary-100 rounded-full mb-6 animate-pulse-glow">
                            <i class="fas fa-headphones text-primary-600 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">Aucun audio</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-8 max-w-md mx-auto">
                            Aucun message vocal n'a encore été reçu. Connectez WhatsApp ou testez avec un audio.
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a href="{{ route('dashboard.test-audio.index') }}" 
                               class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105" 
                               data-aos="fade-up" 
                               data-aos-delay="100">
                                <i class="fas fa-flask mr-2"></i>
                                Tester avec un Audio
                            </a>
                            <a href="{{ route('dashboard.whatsapp.index') }}" 
                               class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105" 
                               data-aos="fade-up" 
                               data-aos-delay="200">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Connecter WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
