<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-base font-semibold text-green-800">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-green-400 hover:text-green-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @if(session('subscription_activated'))
                    <script>
                        // Recharger la page après 1 seconde pour mettre à jour le badge
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    </script>
                @endif
            @endif
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @php
                        $subscription = auth()->user()->activeSubscription();
                    @endphp
                    
                    @if($subscription && $subscription->plan)
                        <!-- Abonnement actif -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $subscription->plan->name }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Actif jusqu'au {{ $subscription->expires_at->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Actif</span>
                                </div>
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Audios autorisés par mois</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $subscription->plan->allowed_audio_per_month }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Durée max par audio</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $subscription->plan->allowed_audio_per_minute_length }} minutes</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Aucun plan actif -->
                        <div class="mb-6 bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-dashed border-gray-300 rounded-xl p-8 text-center">
                            <div class="max-w-md mx-auto">
                                <div class="bg-gray-200 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">Aucun abonnement actif</h3>
                                <p class="text-gray-600 mb-6">Choisissez un plan pour commencer à utiliser toutes les fonctionnalités de Voicy Assistant</p>
                                <a href="{{ route('dashboard.subscription.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform transition hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                    Voir les plans disponibles
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Card 1: Audios traités -->
                        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-primary-100 text-sm font-medium">Audios traités</p>
                                    <p class="text-3xl font-bold mt-2">{{ auth()->user()->audios()->where('status', '=', 'done')->count() }}</p>
                                </div>
                                <div class="bg-white/20 rounded-full p-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Abonnement -->
                        <div class="bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-secondary-100 text-sm font-medium">Plan actuel</p>
                                    <p class="text-2xl font-bold mt-2">
                                        {{ $subscription ? $subscription->plan->name : 'Aucun' }}
                                    </p>
                                    @if($subscription)
                                        <p class="text-secondary-100 text-xs mt-1">
                                            {{ $subscription->plan->allowed_audio_per_month }} audios/mois
                                        </p>
                                    @endif
                                </div>
                                <div class="bg-white/20 rounded-full p-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: WhatsApp connecté -->
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm font-medium">WhatsApp</p>
                                    @php
                                        $whatsapp = auth()->user()->whatsappConnections()->first();
                                    @endphp
                                    <p class="text-2xl font-bold mt-2">
                                        {{ $whatsapp && $whatsapp->webhook_verified ? 'Connecté' : 'Non connecté' }}
                                    </p>
                                </div>
                                <div class="bg-white/20 rounded-full p-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Connecter WhatsApp -->
                        <a href="{{ route('dashboard.whatsapp.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-primary-100 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Connecter WhatsApp</h3>
                                    <p class="text-sm text-gray-500 mt-1">Connecte ton compte WhatsApp Business</p>
                                </div>
                            </div>
                        </a>

                        <!-- Voir les audios -->
                        <a href="{{ route('dashboard.audios.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-secondary-100 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Mes Audios</h3>
                                    <p class="text-sm text-gray-500 mt-1">Voir tous les messages vocaux traités</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

