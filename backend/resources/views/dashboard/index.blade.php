<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Bienvenue, {{ auth()->user()->name }} !</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="bg-primary-100 rounded-full p-2 animate-pulse-glow">
                    <i class="fas fa-chart-line text-primary-600"></i>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-8">
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
                        <!-- Abonnement actif avec animations -->
                        <div class="bg-gradient-to-br from-green-200 via-green-300 to-emerald-300 overflow-hidden sm:rounded-2xl mb-8 mt-20 card-hover-effect animate-slide-in" data-aos="fade-down">
                            <div class="p-4 sm:p-6 relative overflow-hidden">
                                <!-- Animated background decoration -->
                                <div class="absolute top-0 right-0 w-32 h-32 bg-green-300 rounded-full opacity-20 -mr-16 -mt-16 animate-float"></div>
                                <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-300 rounded-full opacity-20 -ml-12 -mb-12 animate-float" style="animation-delay: 1s;"></div>
                                
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl p-4 shadow-lg animate-pulse-glow flex items-center justify-center">
                                                <i class="fas fa-crown text-white text-2xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-bold text-gray-900">{{ $subscription->plan->name }}</h3>
                                                <p class="text-xs text-gray-600 mt-1 flex items-center">
                                                    <i class="fas fa-calendar-check text-primary-600 mr-2"></i>
                                                    Actif jusqu'au <span class="font-semibold text-gray-700 ml-1">{{ $subscription->expires_at->format('d/m/Y') }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <span class="px-4 py-2 bg-white text-primary-600 text-sm font-bold rounded-full animate-scale-in flex items-center shadow-lg border-2 border-primary-200">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Actif
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div class="bg-white/80 rounded-xl p-4 hover:bg-white transition-all" data-aos="fade-right" data-aos-delay="100">
                                            <div class="flex items-center justify-between mb-3">
                                                <p class="text-xs font-semibold text-gray-700 flex items-center">
                                                    <i class="fas fa-microphone-alt text-primary-600 mr-2"></i>
                                                    Audios autorisés par mois
                                                </p>
                                                <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-full p-2 shadow-md">
                                                    <i class="fas fa-infinity text-white text-sm"></i>
                                                </div>
                                            </div>
                                            <p class="text-3xl font-bold text-gray-900" x-data="{ count: 0, target: {{ $subscription->plan->allowed_audio_per_month }} }" x-init="let interval = setInterval(() => { if (count < target) { count += Math.ceil(target / 50); if (count > target) count = target; } else clearInterval(interval); }, 30)">
                                                <span x-text="count >= target ? target : count"></span>
                                            </p>
                                        </div>
                                        <div class="bg-white/80 rounded-xl p-4 hover:bg-white transition-all" data-aos="fade-left" data-aos-delay="200">
                                            <div class="flex items-center justify-between mb-3">
                                                <p class="text-xs font-semibold text-gray-700 flex items-center">
                                                    <i class="fas fa-clock text-secondary-600 mr-2"></i>
                                                    Durée max par audio
                                                </p>
                                                <div class="bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-full p-2 shadow-md">
                                                    <i class="fas fa-hourglass-half text-white text-sm"></i>
                                                </div>
                                            </div>
                                            <p class="text-3xl font-bold text-gray-900">{{ $subscription->plan->allowed_audio_per_minute_length }} <span class="text-lg text-gray-600 font-normal">min</span></p>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress bar for subscription -->
                                    @php
                                        $daysRemaining = now()->diffInDays($subscription->expires_at, false);
                                        $totalDays = now()->diffInDays($subscription->started_at, false) + $daysRemaining;
                                        $progress = $totalDays > 0 ? (($totalDays - $daysRemaining) / $totalDays) * 100 : 0;
                                    @endphp
                                    <div class="mt-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-xs font-semibold text-gray-700">Temps restant</p>
                                            <p class="text-sm font-bold text-primary-600">{{ max(0, $daysRemaining) }} jours</p>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-gradient-to-r from-primary-500 to-secondary-500 h-2.5 rounded-full transition-all duration-1000" style="width: {{ min(100, max(0, $progress)) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Aucun plan actif avec animations -->
                        <div class="mb-8 bg-gradient-to-br from-gray-50 via-white to-primary-50 border-2 border-dashed border-primary-300 rounded-2xl p-8 sm:p-10 text-center shadow-xl animate-slide-in" data-aos="zoom-in">
                            <div class="max-w-md mx-auto">
                                <div class="bg-gradient-to-br from-primary-100 to-secondary-100 rounded-full p-6 w-24 h-24 mx-auto mb-6 flex items-center justify-center animate-pulse-glow">
                                    <i class="fas fa-crown text-primary-600 text-4xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">Aucun abonnement actif</h3>
                                <p class="text-gray-600 mb-8">Choisissez un plan pour commencer à utiliser toutes les fonctionnalités de Voicy Assistant</p>
                                <a href="{{ route('dashboard.subscription.index') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary-600 to-secondary-600 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transform transition-all duration-300 hover:scale-105">
                                    <i class="fas fa-rocket mr-2"></i>
                                    Voir les plans disponibles
                                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Card 1: Audios traités -->
                        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl p-6 sm:p-8 text-white card-hover-effect animate-scale-in" data-aos="fade-up" data-aos-delay="100">
                            <div class="flex items-center justify-between mb-4">
                                <div class="bg-white/20 rounded-xl p-3 backdrop-blur-sm animate-float">
                                    <i class="fas fa-headphones text-white text-3xl"></i>
                                </div>
                                <div class="bg-white/20 rounded-full p-2 animate-pulse">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                            </div>
                            <div>
                                <p class="text-primary-100 text-sm font-medium mb-2">Audios traités</p>
                                <p class="text-4xl font-bold mb-1" x-data="{ count: 0, target: {{ auth()->user()->audios()->where('status', '=', 'done')->count() }} }" x-init="let interval = setInterval(() => { if (count < target) { count += Math.ceil(target / 30); if (count > target) count = target; } else clearInterval(interval); }, 50)">
                                    <span x-text="count >= target ? target : count"></span>
                                </p>
                                <p class="text-primary-100 text-xs flex items-center mt-2">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    Total traité avec succès
                                </p>
                            </div>
                            <!-- Animated wave decoration -->
                            <div class="absolute bottom-0 left-0 right-0 h-2 bg-white/10 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-slide-right" style="animation: slideRight 3s linear infinite;"></div>
                            </div>
                        </div>

                        <!-- Card 2: Abonnement -->
                        <div class="bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl p-6 sm:p-8 text-white card-hover-effect animate-scale-in" data-aos="fade-up" data-aos-delay="200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="bg-white/20 rounded-xl p-3 backdrop-blur-sm animate-float" style="animation-delay: 0.5s;">
                                    <i class="fas fa-crown text-white text-3xl"></i>
                                </div>
                                @if($subscription)
                                    <div class="bg-white/20 rounded-full p-2 animate-pulse">
                                        <i class="fas fa-star text-yellow-300"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-secondary-100 text-sm font-medium mb-2">Plan actuel</p>
                                <p class="text-3xl font-bold mb-1">
                                    {{ $subscription ? $subscription->plan->name : 'Aucun' }}
                                </p>
                                @if($subscription)
                                    <p class="text-secondary-100 text-xs flex items-center mt-2">
                                        <i class="fas fa-infinity mr-1"></i>
                                        {{ $subscription->plan->allowed_audio_per_month }} audios/mois
                                    </p>
                                @else
                                    <p class="text-secondary-100 text-xs flex items-center mt-2">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Aucun plan actif
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Card 3: WhatsApp connecté -->
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 sm:p-8 text-white card-hover-effect animate-scale-in" data-aos="fade-up" data-aos-delay="300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="bg-white/20 rounded-xl p-3 backdrop-blur-sm animate-float" style="animation-delay: 1s;">
                                    <i class="fab fa-whatsapp text-white text-3xl"></i>
                                </div>
                                @php
                                    $whatsapp = auth()->user()->whatsappConnections()->first();
                                @endphp
                                @if($whatsapp && $whatsapp->webhook_verified)
                                    <div class="bg-white/20 rounded-full p-2 animate-pulse">
                                        <i class="fas fa-wifi text-white"></i>
                                    </div>
                                @else
                                    <div class="bg-red-500/50 rounded-full p-2">
                                        <i class="fas fa-unlink text-white"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-green-100 text-sm font-medium mb-2">WhatsApp</p>
                                <p class="text-3xl font-bold mb-1">
                                    {{ $whatsapp && $whatsapp->webhook_verified ? 'Connecté' : 'Non connecté' }}
                                </p>
                                <p class="text-green-100 text-xs flex items-center mt-2">
                                    @if($whatsapp && $whatsapp->webhook_verified)
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Prêt à recevoir des vocaux
                                    @else
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Connectez votre compte
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Connecter WhatsApp -->
                        <a href="{{ route('dashboard.whatsapp.index') }}" class="group block p-6 bg-white border-2 border-gray-200 rounded-2xl hover:border-primary-300 hover:shadow-2xl transition-all duration-300 card-hover-effect" data-aos="fade-right" data-aos-delay="400">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fab fa-whatsapp text-white text-2xl"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors">Connecter WhatsApp</h3>
                                    <p class="text-sm text-gray-500 mt-1">Connecte ton compte WhatsApp Business</p>
                                </div>
                                <div class="text-primary-600 group-hover:translate-x-2 transition-transform">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>

                        <!-- Voir les audios -->
                        <a href="{{ route('dashboard.audios.index') }}" class="group block p-6 bg-white border-2 border-gray-200 rounded-2xl hover:border-secondary-300 hover:shadow-2xl transition-all duration-300 card-hover-effect" data-aos="fade-left" data-aos-delay="500">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-headphones text-white text-2xl"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-secondary-600 transition-colors">Mes Audios</h3>
                                    <p class="text-sm text-gray-500 mt-1">Voir tous les messages vocaux traités</p>
                                </div>
                                <div class="text-secondary-600 group-hover:translate-x-2 transition-transform">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Confetti animation on subscription activation
        @if (session('subscription_activated'))
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    confetti({
                        particleCount: 200,
                        spread: 90,
                        origin: { y: 0.5 },
                        colors: ['#0ea5e9', '#a855f7', '#10b981', '#3b82f6']
                    });
                    
                    setTimeout(function() {
                        confetti({
                            particleCount: 150,
                            angle: 60,
                            spread: 55,
                            origin: { x: 0 },
                            colors: ['#0ea5e9', '#a855f7', '#10b981']
                        });
                        confetti({
                            particleCount: 150,
                            angle: 120,
                            spread: 55,
                            origin: { x: 1 },
                            colors: ['#0ea5e9', '#a855f7', '#10b981']
                        });
                    }, 300);
                }, 500);
            });
        @endif
    </script>
</x-app-layout>

