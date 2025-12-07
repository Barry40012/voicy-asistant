<x-admin-layout pageTitle="Modifier le Plan : {{ $plan->name }}">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Modifier le Plan</h2>
                        <p class="text-sm text-gray-600 mt-1">Les modifications seront appliquées sur toute la plateforme</p>
                    </div>
                    <a href="{{ route('admin.plans') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.plans.update', $plan) }}" method="POST" class="p-6">
                @csrf
                @method('PATCH')

                <div class="space-y-6">
                    <!-- Nom du plan -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom du plan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $plan->name) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prix et devise -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price_monthly" class="block text-sm font-semibold text-gray-700 mb-2">
                                Prix mensuel <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="price_monthly" 
                                   id="price_monthly" 
                                   value="{{ old('price_monthly', $plan->price_monthly) }}"
                                   step="0.01"
                                   min="0"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('price_monthly') border-red-500 @enderror">
                            @error('price_monthly')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="price_currency" class="block text-sm font-semibold text-gray-700 mb-2">
                                Devise <span class="text-red-500">*</span>
                            </label>
                            <select name="price_currency" 
                                    id="price_currency" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('price_currency') border-red-500 @enderror">
                                <option value="EUR" {{ old('price_currency', $plan->price_currency) === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                <option value="USD" {{ old('price_currency', $plan->price_currency) === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="XOF" {{ old('price_currency', $plan->price_currency) === 'XOF' ? 'selected' : '' }}>XOF (FCFA)</option>
                                <option value="GNF" {{ old('price_currency', $plan->price_currency) === 'GNF' ? 'selected' : '' }}>GNF (Franc guinéen)</option>
                                <option value="NGN" {{ old('price_currency', $plan->price_currency) === 'NGN' ? 'selected' : '' }}>NGN (₦)</option>
                            </select>
                            @error('price_currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Limites audio -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="allowed_audio_per_month" class="block text-sm font-semibold text-gray-700 mb-2">
                                Audios par mois <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="allowed_audio_per_month" 
                                   id="allowed_audio_per_month" 
                                   value="{{ old('allowed_audio_per_month', $plan->allowed_audio_per_month) }}"
                                   min="0"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('allowed_audio_per_month') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Nombre maximum d'audios autorisés par mois</p>
                            @error('allowed_audio_per_month')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="allowed_audio_per_minute_length" class="block text-sm font-semibold text-gray-700 mb-2">
                                Durée max par audio (minutes) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="allowed_audio_per_minute_length" 
                                   id="allowed_audio_per_minute_length" 
                                   value="{{ old('allowed_audio_per_minute_length', $plan->allowed_audio_per_minute_length) }}"
                                   min="1"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('allowed_audio_per_minute_length') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Durée maximale autorisée pour chaque audio</p>
                            @error('allowed_audio_per_minute_length')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('description') border-red-500 @enderror">{{ old('description', $plan->description) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Description du plan affichée aux utilisateurs</p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Statut actif -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               id="is_active" 
                               value="1"
                               {{ old('is_active', $plan->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                            Plan actif
                        </label>
                        <p class="ml-2 text-xs text-gray-500">Si désactivé, le plan ne sera plus visible pour les nouveaux abonnements</p>
                    </div>

                    <!-- Informations sur les abonnements existants -->
                    @if($plan->subscriptions()->count() > 0)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-800">
                                        <strong>Attention :</strong> Ce plan a {{ $plan->subscriptions()->count() }} abonnement(s) actif(s). 
                                        Les modifications affecteront uniquement les nouveaux abonnements. Les abonnements existants conserveront leurs conditions actuelles.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t-2 border-gray-200">
                    <a href="{{ route('admin.plans') }}" class="flex items-center justify-center px-8 py-3.5 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-bold text-sm shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class="font-bold tracking-wide">Annuler</span>
                    </a>
                    <button type="submit" class="flex items-center justify-center px-8 py-3.5 bg-primary-50 border-2 border-primary-500 text-primary-700 rounded-lg hover:bg-primary-100 hover:border-primary-600 hover:text-primary-800 transition-all duration-200 font-bold text-sm shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-primary-700 font-bold tracking-wide">Enregistrer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

