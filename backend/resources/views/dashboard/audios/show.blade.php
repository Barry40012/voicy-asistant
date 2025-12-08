<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détail Audio') }}
            </h2>
            <a href="{{ route('dashboard.audios.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Informations générales -->
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Audio de {{ $audio->sender_phone }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Reçu le {{ $audio->created_at->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                            @if($audio->status === 'done')
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Traité</span>
                            @elseif($audio->status === 'processing')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">En cours</span>
                            @elseif($audio->status === 'error')
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">Erreur</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">En attente</span>
                            @endif
                        </div>
                    </div>

                    @if($audio->analysis)
                        <!-- Transcript -->
                        <div class="mb-6">
                            <h4 class="text-md font-semibold text-gray-900 mb-3">Transcription</h4>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-gray-700 leading-relaxed">{{ $audio->analysis->transcript }}</p>
                            </div>
                        </div>

                        <!-- Résumé -->
                        @if($audio->analysis->summary)
                            <div class="mb-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-3">Résumé</h4>
                                <div class="bg-primary-50 rounded-lg p-4 border border-primary-200">
                                    <p class="text-gray-700 leading-relaxed">{{ $audio->analysis->summary }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Actions extraites -->
                        @if($audio->analysis->actions && count($audio->analysis->actions) > 0)
                            <div class="mb-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-3">Actions identifiées</h4>
                                <div class="space-y-2">
                                    @foreach($audio->analysis->actions as $action)
                                        <div class="bg-secondary-50 rounded-lg p-3 border border-secondary-200">
                                            <p class="text-gray-700">{{ $action }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Réponse générée -->
                        @if($audio->analysis->generated_reply)
                            <div class="mb-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-3">Réponse suggérée</h4>
                                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                    <p class="text-gray-700 leading-relaxed">{{ $audio->analysis->generated_reply }}</p>
                                </div>
                                <div class="mt-4">
                                    <button class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Envoyer la réponse
                                    </button>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            @if($audio->status === 'processing')
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
                                <h3 class="mt-4 text-sm font-semibold text-gray-900">Analyse en cours...</h3>
                                <p class="mt-1 text-sm text-gray-500">Le traitement peut prendre 10-30 secondes. Rafraîchissez la page dans quelques instants.</p>
                                <div class="mt-4">
                                    <button onclick="location.reload()" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-md hover:bg-primary-700">
                                        <i class="fas fa-sync-alt mr-2"></i>
                                        Rafraîchir
                                    </button>
                                </div>
                            @elseif($audio->status === 'error')
                                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900">Erreur lors du traitement</h3>
                                <div class="mt-3 bg-red-50 border border-red-200 rounded-lg p-4">
                                    <p class="text-sm text-red-800 font-medium mb-2">Message d'erreur :</p>
                                    <p class="text-sm text-red-700">{{ $audio->error_message ?? 'Une erreur est survenue lors du traitement de l\'audio.' }}</p>
                                </div>
                                <div class="mt-4 space-x-3">
                                    <a href="{{ route('dashboard.audios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-md hover:bg-gray-700">
                                        Retour à la liste
                                    </a>
                                    <a href="{{ route('dashboard.test-audio.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-md hover:bg-primary-700">
                                        <i class="fas fa-redo mr-2"></i>
                                        Réessayer
                                    </a>
                                </div>
                            @else
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900">En attente de traitement</h3>
                                <p class="mt-1 text-sm text-gray-500">L'audio est en attente de traitement. Le traitement devrait commencer sous peu.</p>
                                <div class="mt-4">
                                    <button onclick="location.reload()" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-md hover:bg-primary-700">
                                        <i class="fas fa-sync-alt mr-2"></i>
                                        Rafraîchir
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

