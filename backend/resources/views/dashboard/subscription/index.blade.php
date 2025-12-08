<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl p-3 shadow-lg">
                <i class="fas fa-crown text-white text-2xl"></i>
            </div>
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 leading-tight">
                    {{ __('Mon Abonnement') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Gérez votre abonnement et choisissez un plan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Messages de session -->
            @if (session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg shadow-lg" data-aos="fade-down">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-base font-bold text-green-800">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-green-400 hover:text-green-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            @if (session('info'))
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-lg shadow-lg" data-aos="fade-down">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-base font-bold text-blue-800">{{ session('info') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            @if (session('warning'))
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg shadow-lg" data-aos="fade-down">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-base font-bold text-yellow-800">{{ session('warning') }}</p>
                            @if(session('pending_payment_ids'))
                                <div class="mt-3">
                                    <p class="text-sm font-semibold mb-2">Paiements en attente (IDs) :</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(session('pending_payment_ids') as $paymentId)
                                            <form method="POST" action="{{ route('dashboard.subscription.activate-payment', $paymentId) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-yellow-600 text-white text-sm font-bold rounded-lg hover:bg-yellow-700 transition-all shadow-md hover:shadow-lg">
                                                    Activer le paiement #{{ $paymentId }}
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                    <p class="text-xs mt-2 text-yellow-700">
                                        ⚠️ Cliquez uniquement si vous avez bien effectué le paiement
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg shadow-lg" data-aos="fade-down">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-600 text-2xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-base font-bold text-red-800">Erreur!</p>
                            <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(isset($pendingPayments) && $pendingPayments->count() > 0)
                <!-- Alerte paiements en attente avec vérification automatique -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-400 p-6 mb-6 rounded-r-lg shadow-xl" id="pending-payments-alert" data-aos="fade-up">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600 animate-spin" id="checking-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <svg class="h-8 w-8 text-green-600 hidden" id="checking-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-base font-bold text-blue-800" id="checking-message">
                                Vérification automatique de vos paiements en cours...
                            </p>
                            <p class="text-sm text-blue-600 mt-1">
                                Cette page vérifie automatiquement vos paiements. Si votre paiement a été effectué, votre abonnement sera activé dans quelques secondes.
                            </p>
                        </div>
                    </div>
                </div>
                
                <script>
                    // Vérification automatique en arrière-plan avec plusieurs tentatives
                    (function() {
                        let attempts = 0;
                        const maxAttempts = 5;
                        const checkInterval = 3000; // 3 secondes entre chaque vérification
                        
                        function checkPayments() {
                            attempts++;
                            
                            fetch('{{ route("dashboard.subscription.check-pending") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({})
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success || (data.activated && data.activated > 0)) {
                                    // Paiement activé ! Recharger la page immédiatement
                                    window.location.href = '{{ route("dashboard") }}';
                                } else if (attempts < maxAttempts) {
                                    // Continuer à vérifier
                                    setTimeout(checkPayments, checkInterval);
                                } else {
                                    // Arrêter après maxAttempts tentatives
                                    document.getElementById('checking-spinner').classList.add('hidden');
                                    document.getElementById('checking-success').classList.remove('hidden');
                                    document.getElementById('checking-message').textContent = 'Vérification terminée. Si votre paiement a été effectué, votre abonnement sera activé automatiquement.';
                                }
                            })
                            .catch(error => {
                                console.error('Erreur vérification:', error);
                                if (attempts < maxAttempts) {
                                    setTimeout(checkPayments, checkInterval);
                                } else {
                                    document.getElementById('checking-spinner').classList.add('hidden');
                                    document.getElementById('checking-message').textContent = 'Vérification terminée.';
                                }
                            });
                        }
                        
                        // Démarrer la vérification après 2 secondes
                        setTimeout(checkPayments, 2000);
                    })();
                </script>
            @endif
            
            @if($currentSubscription)
                <!-- Abonnement actif avec design amélioré -->
                <div class="bg-gradient-to-br from-green-200 via-green-300 to-emerald-300 overflow-hidden sm:rounded-2xl mb-8 shadow-2xl border-2 border-green-400" data-aos="zoom-in">
                    <div class="p-6 sm:p-8 relative overflow-hidden">
                        <!-- Animated background decoration -->
                        <div class="absolute top-0 right-0 w-40 h-40 bg-green-300 rounded-full opacity-20 -mr-20 -mt-20 animate-float"></div>
                        <div class="absolute bottom-0 left-0 w-32 h-32 bg-emerald-300 rounded-full opacity-20 -ml-16 -mb-16 animate-float" style="animation-delay: 1s;"></div>
                        
                        <div class="relative z-10">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6">
                                <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                                    <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl p-4 shadow-lg animate-pulse-glow flex items-center justify-center">
                                        <i class="fas fa-crown text-white text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $currentSubscription->plan->name }}</h3>
                                        <p class="text-sm sm:text-base text-gray-700 mt-1 flex items-center">
                                            <i class="fas fa-calendar-check text-primary-600 mr-2"></i>
                                            Actif jusqu'au <span class="font-bold text-gray-900 ml-1">{{ $currentSubscription->expires_at->format('d/m/Y') }}</span>
                                        </p>
                                    </div>
                                </div>
                                <span class="px-4 py-2 bg-white text-primary-600 text-sm font-bold rounded-full animate-scale-in flex items-center shadow-lg border-2 border-primary-200">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Actif
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <div class="bg-white/90 rounded-xl p-4 sm:p-6 hover:bg-white transition-all shadow-md" data-aos="fade-right" data-aos-delay="100">
                                    <div class="flex items-center justify-between mb-3">
                                        <p class="text-sm font-bold text-gray-700 flex items-center">
                                            <i class="fas fa-microphone-alt text-primary-600 mr-2"></i>
                                            Audios autorisés par mois
                                        </p>
                                        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-full p-2 shadow-md">
                                            <i class="fas fa-infinity text-white text-sm"></i>
                                        </div>
                                    </div>
                                    <p class="text-3xl sm:text-4xl font-bold text-gray-900">{{ $currentSubscription->plan->allowed_audio_per_month }}</p>
                                </div>
                                <div class="bg-white/90 rounded-xl p-4 sm:p-6 hover:bg-white transition-all shadow-md" data-aos="fade-left" data-aos-delay="200">
                                    <div class="flex items-center justify-between mb-3">
                                        <p class="text-sm font-bold text-gray-700 flex items-center">
                                            <i class="fas fa-clock text-secondary-600 mr-2"></i>
                                            Durée max par audio
                                        </p>
                                        <div class="bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-full p-2 shadow-md">
                                            <i class="fas fa-hourglass-half text-white text-sm"></i>
                                        </div>
                                    </div>
                                    <p class="text-3xl sm:text-4xl font-bold text-gray-900">{{ $currentSubscription->plan->allowed_audio_per_minute_length }} <span class="text-lg text-gray-600 font-normal">min</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Plans disponibles -->
            <div class="mb-6">
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 flex items-center" data-aos="fade-up">
                    <i class="fas fa-rocket text-primary-600 mr-3 text-3xl"></i>
                    Plans disponibles
                </h3>
                <p class="text-gray-600 mb-6" data-aos="fade-up" data-aos-delay="100">
                    Choisissez le plan qui correspond le mieux à vos besoins
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                @foreach($plans as $index => $plan)
                    <div class="bg-white/95 backdrop-blur-sm border-2 rounded-2xl overflow-hidden transition-all duration-300 transform hover:-translate-y-2 hover:shadow-2xl {{ $currentSubscription && $currentSubscription->plan_id === $plan->id ? 'border-primary-500 ring-4 ring-primary-200' : 'border-gray-200 hover:border-primary-300' }}" 
                         data-aos="fade-up" 
                         data-aos-delay="{{ $index * 100 }}">
                        <!-- Header du plan -->
                        <div class="bg-gradient-to-br {{ $plan->isFree() ? 'from-gray-100 to-gray-200' : ($plan->name === 'Pro' ? 'from-primary-500 to-primary-600' : 'from-secondary-500 to-secondary-600') }} p-6 text-center relative overflow-hidden">
                            @if($currentSubscription && $currentSubscription->plan_id === $plan->id)
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 bg-white text-primary-600 text-xs font-bold rounded-full shadow-lg flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Actuel
                                    </span>
                                </div>
                            @endif
                            
                            <!-- Animated background -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-1000 animate-shimmer"></div>
                            
                            <div class="relative z-10">
                                <div class="mb-4">
                                    @if($plan->isFree())
                                        <i class="fas fa-gift text-gray-600 text-4xl mb-3"></i>
                                    @elseif($plan->name === 'Pro')
                                        <i class="fas fa-crown text-white text-4xl mb-3 animate-pulse-glow"></i>
                                    @else
                                        <i class="fas fa-star text-white text-4xl mb-3"></i>
                                    @endif
                                </div>
                                <h3 class="text-2xl sm:text-3xl font-bold {{ $plan->isFree() ? 'text-gray-900' : 'text-white' }} mb-2">{{ $plan->name }}</h3>
                                <div class="mb-4">
                                    @if($plan->isFree())
                                        <span class="text-4xl sm:text-5xl font-bold text-gray-900">Gratuit</span>
                                    @else
                                        <span class="text-4xl sm:text-5xl font-bold text-white">{{ number_format($plan->price_monthly, 2) }}€</span>
                                        <span class="text-white/80 text-lg">/mois</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <p class="text-sm sm:text-base text-gray-600 mb-6 min-h-[3rem]">{{ $plan->description }}</p>

                            <ul class="space-y-4 mb-8">
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 bg-green-100 rounded-full p-1.5 mr-3">
                                        <i class="fas fa-check text-green-600 text-sm"></i>
                                    </div>
                                    <span class="text-sm sm:text-base text-gray-700 font-medium">{{ $plan->allowed_audio_per_month }} audios/mois</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 bg-green-100 rounded-full p-1.5 mr-3">
                                        <i class="fas fa-check text-green-600 text-sm"></i>
                                    </div>
                                    <span class="text-sm sm:text-base text-gray-700 font-medium">Jusqu'à {{ $plan->allowed_audio_per_minute_length }} min/audio</span>
                                </li>
                            </ul>

                            @if(!$currentSubscription || $currentSubscription->plan_id !== $plan->id)
                                <form method="POST" action="{{ route('dashboard.subscription.subscribe', $plan) }}">
                                    @csrf
                                    @if($plan->isFree())
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Activer gratuitement
                                        </button>
                                    @else
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105">
                                            <i class="fas fa-credit-card mr-2"></i>
                                            S'abonner avec ma carte Visa
                                        </button>
                                    @endif
                                </form>
                            @else
                                <div class="w-full inline-flex justify-center items-center px-6 py-3 bg-gray-200 text-gray-600 font-bold rounded-xl cursor-not-allowed">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Plan actuel
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
