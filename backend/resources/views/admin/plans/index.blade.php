<x-admin-layout pageTitle="Gestion des Plans">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Plans d'Abonnement</h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($plans as $plan)
                    <div class="border-2 rounded-lg p-6 {{ $plan->is_active ? 'border-primary-500' : 'border-gray-300 opacity-60' }}">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                            @if($plan->is_active)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">Actif</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded">Inactif</span>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($plan->price_monthly, 2) }}</span>
                            <span class="text-gray-500">{{ $plan->price_currency }}</span>
                            <span class="text-gray-500">/mois</span>
                        </div>

                        <p class="text-sm text-gray-600 mb-4">{{ $plan->description }}</p>

                        <ul class="space-y-2 mb-4">
                            <li class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ number_format($plan->allowed_audio_per_month) }} audios/mois
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Jusqu'à {{ $plan->allowed_audio_per_minute_length }} min/audio
                            </li>
                            <li class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $plan->subscriptions_count ?? 0 }} abonnement(s)
                            </li>
                        </ul>
                        
                        <!-- Bouton Modifier - Bien visible -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.plans.edit', $plan) }}" 
                               class="w-full flex items-center justify-center px-4 py-3 bg-primary-50 border-2 border-primary-500 text-primary-700 rounded-lg hover:bg-primary-100 hover:border-primary-600 hover:text-primary-800 transition-all duration-200 font-bold text-sm shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span class="text-primary-700 font-bold tracking-wide">Modifier le plan</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-500">Aucun plan trouvé</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
