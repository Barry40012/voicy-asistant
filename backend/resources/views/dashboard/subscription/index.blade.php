<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon Abonnement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Succès!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Information!</strong>
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif
            @if (session('warning'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Attention!</strong>
                    <span class="block sm:inline">{{ session('warning') }}</span>
                    @if(session('pending_payment_ids'))
                        <div class="mt-3">
                            <p class="text-sm font-semibold mb-2">Paiements en attente (IDs) :</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(session('pending_payment_ids') as $paymentId)
                                    <form method="POST" action="{{ route('dashboard.subscription.activate-payment', $paymentId) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-yellow-600 text-white text-xs font-semibold rounded hover:bg-yellow-700 transition">
                                            Activer le paiement #{{ $paymentId }}
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                            <p class="text-xs mt-2 text-yellow-600">
                                ⚠️ Cliquez uniquement si vous avez bien effectué le paiement
                            </p>
                        </div>
                    @endif
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Erreur!</strong>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if(isset($pendingPayments) && $pendingPayments->count() > 0)
                <!-- Alerte paiements en attente avec vérification automatique -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-lg shadow-md" id="pending-payments-alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400 animate-spin" id="checking-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <svg class="h-6 w-6 text-blue-400 hidden" id="checking-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-semibold text-blue-800" id="checking-message">
                                Vérification automatique de vos paiements en cours...
                            </p>
                            <p class="text-xs text-blue-600 mt-1">
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
                <!-- Abonnement actif -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $currentSubscription->plan->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Actif jusqu'au {{ $currentSubscription->expires_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Actif</span>
                        </div>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Audios autorisés par mois</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $currentSubscription->plan->allowed_audio_per_month }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Durée max par audio</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $currentSubscription->plan->allowed_audio_per_minute_length }} minutes</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Plans disponibles -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($plans as $plan)
                    <div class="bg-white border-2 rounded-lg overflow-hidden {{ $currentSubscription && $currentSubscription->plan_id === $plan->id ? 'border-primary-500' : 'border-gray-200' }}">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                                @if($currentSubscription && $currentSubscription->plan_id === $plan->id)
                                    <span class="px-2 py-1 bg-primary-100 text-primary-800 text-xs font-medium rounded">Actuel</span>
                                @endif
                            </div>
                            
                            <div class="mb-4">
                                @if($plan->isFree())
                                    <span class="text-3xl font-bold text-gray-900">Gratuit</span>
                                @else
                                    <span class="text-3xl font-bold text-gray-900">{{ number_format($plan->price_monthly, 2) }}€</span>
                                    <span class="text-gray-500">/mois</span>
                                @endif
                            </div>

                            <p class="text-sm text-gray-600 mb-6">{{ $plan->description }}</p>

                            <ul class="space-y-3 mb-6">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $plan->allowed_audio_per_month }} audios/mois</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">Jusqu'à {{ $plan->allowed_audio_per_minute_length }} min/audio</span>
                                </li>
                            </ul>

                            @if(!$currentSubscription || $currentSubscription->plan_id !== $plan->id)
                                <form method="POST" action="{{ route('dashboard.subscription.subscribe', $plan) }}">
                                    @csrf
                                    @if($plan->isFree())
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Activer gratuitement
                                        </button>
                                    @else
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                            S'abonner avec ma carte Visa
                                        </button>
                                    @endif
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

