<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Connexion WhatsApp') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($connection && $connection->webhook_verified)
                        <!-- WhatsApp connecté -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-green-900">WhatsApp connecté ✅</h3>
                                    <p class="text-sm text-green-700 mt-1">
                                        Ton compte WhatsApp Business est connecté et actif.
                                        @if($connection->phone_number)
                                            <br>Numéro : <strong>{{ $connection->phone_number }}</strong>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Assistant de Connexion -->
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Connecter WhatsApp Business</h3>
                            <p class="text-gray-600 mb-6">
                                Suis notre assistant pas-à-pas pour connecter ton WhatsApp Business en moins de 5 minutes.
                            </p>

                            <!-- Assistant Interactif -->
                            <div x-data="{ step: 1, totalSteps: 4 }" class="space-y-6">
                                <!-- Étape 1 : Créer l'app Meta -->
                                <div x-show="step === 1" class="bg-gradient-to-br from-primary-50 to-primary-100 border-2 border-primary-200 rounded-xl p-6">
                                    <div class="flex items-start mb-4">
                                        <div class="flex-shrink-0 bg-primary-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4">
                                            1
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold text-gray-900 mb-2">Créer une Application Meta</h4>
                                            <p class="text-sm text-gray-700 mb-4">
                                                Tu dois créer une application Meta pour accéder à WhatsApp Business API.
                                            </p>
                                            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 mb-4">
                                                <li>Va sur <a href="https://developers.facebook.com" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium underline">Meta Developers</a></li>
                                                <li>Clique sur <strong>"Mes applications"</strong> puis <strong>"Créer une application"</strong></li>
                                                <li>Choisis le type <strong>"Business"</strong></li>
                                                <li>Remplis les informations et clique sur <strong>"Créer"</strong></li>
                                            </ol>
                                            <a href="https://developers.facebook.com/apps/create" target="_blank" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                                                Créer l'application Meta
                                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <button @click="step = 2" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                                            Suivant →
                                        </button>
                                    </div>
                                </div>

                                <!-- Étape 2 : Ajouter WhatsApp -->
                                <div x-show="step === 2" class="bg-gradient-to-br from-secondary-50 to-secondary-100 border-2 border-secondary-200 rounded-xl p-6">
                                    <div class="flex items-start mb-4">
                                        <div class="flex-shrink-0 bg-secondary-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4">
                                            2
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold text-gray-900 mb-2">Ajouter WhatsApp à l'application</h4>
                                            <p class="text-sm text-gray-700 mb-4">
                                                Une fois l'application créée, ajoute le produit WhatsApp.
                                            </p>
                                            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 mb-4">
                                                <li>Dans le dashboard de ton application, cherche <strong>"Ajouter un produit"</strong></li>
                                                <li>Trouve <strong>"WhatsApp"</strong> dans la liste</li>
                                                <li>Clique sur <strong>"Configurer"</strong> à côté de WhatsApp</li>
                                                <li>Clique sur <strong>"Commencer"</strong> ou <strong>"Get Started"</strong></li>
                                            </ol>
                                        </div>
                                    </div>
                                    <div class="flex justify-between">
                                        <button @click="step = 1" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                            ← Précédent
                                        </button>
                                        <button @click="step = 3" class="px-4 py-2 bg-secondary-600 text-white rounded-lg hover:bg-secondary-700 transition">
                                            Suivant →
                                        </button>
                                    </div>
                                </div>

                                <!-- Étape 3 : Récupérer les Credentials -->
                                <div x-show="step === 3" class="bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200 rounded-xl p-6">
                                    <div class="flex items-start mb-4">
                                        <div class="flex-shrink-0 bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4">
                                            3
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold text-gray-900 mb-2">Récupérer les Credentials</h4>
                                            <p class="text-sm text-gray-700 mb-4">
                                                Copie ces informations depuis Meta Developers.
                                            </p>
                                            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 mb-4">
                                                <li>Dans le menu de gauche, clique sur <strong>"API Setup"</strong> ou <strong>"Configuration API"</strong></li>
                                                <li>Copie le <strong>"Phone number ID"</strong></li>
                                                <li>Copie le <strong>"WhatsApp Business Account ID"</strong></li>
                                                <li>Copie le <strong>"Temporary access token"</strong> (commence par EAA...)</li>
                                            </ol>
                                            <div class="bg-white rounded-lg p-4 border border-green-200">
                                                <p class="text-xs text-gray-500 mb-2">💡 Astuce : Ouvre Meta Developers dans un autre onglet pour copier ces informations</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between">
                                        <button @click="step = 2" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                            ← Précédent
                                        </button>
                                        <button @click="step = 4" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                            Suivant →
                                        </button>
                                    </div>
                                </div>

                                <!-- Étape 4 : Entrer les Credentials -->
                                <div x-show="step === 4" class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 rounded-xl p-6">
                                    <div class="flex items-start mb-4">
                                        <div class="flex-shrink-0 bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4">
                                            4
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold text-gray-900 mb-4">Entrer les Credentials</h4>
                                            
                                            <!-- Formulaire -->
                                            <form method="POST" action="{{ route('dashboard.whatsapp.store') }}" class="space-y-4">
                                                @csrf

                                                <div>
                                                    <label for="phone_number_id" class="block text-sm font-medium text-gray-700 mb-2">
                                                        Phone Number ID *
                                                    </label>
                                                    <input type="text" name="phone_number_id" id="phone_number_id" value="{{ old('phone_number_id', $connection->phone_number_id ?? '') }}" required
                                                           placeholder="Ex: 123456789012345"
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                                    @error('phone_number_id')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label for="whatsapp_business_account_id" class="block text-sm font-medium text-gray-700 mb-2">
                                                        WhatsApp Business Account ID *
                                                    </label>
                                                    <input type="text" name="whatsapp_business_account_id" id="whatsapp_business_account_id" value="{{ old('whatsapp_business_account_id', $connection->whatsapp_business_account_id ?? '') }}" required
                                                           placeholder="Ex: 987654321098765"
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                                    @error('whatsapp_business_account_id')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label for="access_token" class="block text-sm font-medium text-gray-700 mb-2">
                                                        Access Token (Temporaire) *
                                                    </label>
                                                    <textarea name="access_token" id="access_token" rows="3" required
                                                              placeholder="EAAxxxxxxxxxxxxx..."
                                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('access_token', $connection->access_token ?? '') }}</textarea>
                                                    @error('access_token')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                    <p class="mt-1 text-xs text-gray-500">⚠️ Ce token expire après 24h. Pour la production, tu devras créer un token permanent.</p>
                                                </div>

                                                <div>
                                                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                                                        Numéro de téléphone (optionnel)
                                                    </label>
                                                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $connection->phone_number ?? '') }}"
                                                           placeholder="+33 6 12 34 56 78"
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                                </div>

                                                <div class="flex justify-between pt-4">
                                                    <button type="button" @click="step = 3" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                                        ← Précédent
                                                    </button>
                                                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold">
                                                        ✅ Connecter WhatsApp
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Documentation -->
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">📚 Ressources</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <a href="https://developers.facebook.com/docs/whatsapp/cloud-api/get-started" target="_blank" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <svg class="w-5 h-5 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Documentation WhatsApp API
                            </a>
                            <a href="https://developers.facebook.com/apps" target="_blank" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <svg class="w-5 h-5 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Mes applications Meta
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
