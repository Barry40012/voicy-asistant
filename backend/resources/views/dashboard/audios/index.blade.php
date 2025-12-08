<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mes Audios') }}
            </h2>
            <a href="{{ route('dashboard.test-audio.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-md hover:bg-green-700 transition shadow-lg hover:shadow-xl">
                <i class="fas fa-flask mr-2"></i>
                Tester un Audio
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($audios->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($audios as $audio)
                                <a href="{{ route('dashboard.audios.show', $audio) }}" class="block p-4 border border-gray-200 rounded-lg hover:border-primary-300 hover:shadow-md transition-all">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                @if($audio->status === 'done')
                                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                                        </svg>
                                                    </div>
                                                @elseif($audio->status === 'processing')
                                                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-yellow-600 animate-spin" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    Audio de {{ $audio->sender_phone }}
                                                </h3>
                                                <p class="text-sm text-gray-500">
                                                    {{ $audio->created_at->format('d/m/Y H:i') }}
                                                    @if($audio->analysis)
                                                        • {{ Str::limit($audio->analysis->summary ?? 'Aucun résumé', 50) }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($audio->status === 'done')
                                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Traité</span>
                                            @elseif($audio->status === 'processing')
                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">En cours</span>
                                            @elseif($audio->status === 'error')
                                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">Erreur</span>
                                            @else
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">En attente</span>
                                            @endif
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $audios->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Aucun audio</h3>
                        <p class="mt-1 text-sm text-gray-500">Aucun message vocal n'a encore été reçu.</p>
                        <div class="mt-6 space-x-4">
                            <a href="{{ route('dashboard.test-audio.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-flask mr-2"></i>
                                Tester avec un Audio
                            </a>
                            <a href="{{ route('dashboard.whatsapp.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Connecter WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

