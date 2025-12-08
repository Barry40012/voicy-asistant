<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-3 shadow-lg">
                <i class="fab fa-whatsapp text-white text-2xl"></i>
            </div>
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 leading-tight">
                    {{ __('Connexion WhatsApp') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Connectez votre compte WhatsApp Business</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($connection && $connection->webhook_verified)
                <!-- WhatsApp connecté -->
                <div class="bg-gradient-to-br from-green-50 via-green-100 to-emerald-50 border-2 border-green-300 rounded-2xl p-6 sm:p-8 mb-6 shadow-xl" data-aos="zoom-in">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center">
                        <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-6">
                            <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-2xl animate-pulse-glow">
                                <i class="fab fa-whatsapp text-white text-4xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl sm:text-3xl font-bold text-green-900 mb-2 flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                WhatsApp connecté ✅
                            </h3>
                            <p class="text-base sm:text-lg text-green-700 mb-3">
                                Ton compte WhatsApp Business est connecté et actif.
                            </p>
                            @if($connection->phone_number)
                                <div class="bg-white/80 rounded-xl p-4 shadow-md inline-block">
                                    <p class="text-sm text-gray-600 font-medium mb-1">Numéro connecté</p>
                                    <p class="text-lg font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-phone-alt text-green-600 mr-2"></i>
                                        {{ $connection->phone_number }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- Assistant de Connexion -->
                <div class="mb-8" data-aos="fade-up">
                    <div class="bg-gradient-to-br from-primary-50 to-secondary-50 rounded-2xl p-6 sm:p-8 border-2 border-primary-200 shadow-xl mb-6">
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3 flex items-center">
                            <i class="fab fa-whatsapp text-green-600 mr-3 text-3xl"></i>
                            Connecter WhatsApp Business
                        </h3>
                        <p class="text-base sm:text-lg text-gray-700 mb-4">
                            Suis notre assistant pas-à-pas pour connecter ton WhatsApp Business en moins de 5 minutes.
                        </p>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-clock text-primary-600"></i>
                            <span>⏱️ Temps estimé : 5 minutes</span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-shield-alt text-primary-600"></i>
                            <span>🔒 100% sécurisé</span>
                        </div>
                    </div>

                    <!-- Assistant Interactif -->
                    <div x-data="{ step: 1, totalSteps: 4 }" class="space-y-6">
                        <!-- Étape 1 : Créer l'app Meta -->
                        <div x-show="step === 1" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-x-10"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             class="bg-gradient-to-br from-primary-50 to-primary-100 border-2 border-primary-300 rounded-2xl p-6 sm:p-8 shadow-xl" 
                             data-aos="fade-right">
                            <div class="flex items-start mb-6">
                                <div class="flex-shrink-0 bg-gradient-to-br from-primary-600 to-primary-700 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-xl mr-4 shadow-lg animate-pulse-glow">
                                    1
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 flex items-center">
                                        <i class="fab fa-meta text-primary-600 mr-2 text-2xl"></i>
                                        Créer une Application Meta
                                    </h4>
                                    <p class="text-sm sm:text-base text-gray-700 mb-4">
                                        Tu dois créer une application Meta pour accéder à WhatsApp Business API.
                                    </p>
                                    <ol class="list-decimal list-inside space-y-3 text-sm sm:text-base text-gray-700 mb-6 bg-white/60 rounded-xl p-4">
                                        <li>Va sur <a href="https://developers.facebook.com" target="_blank" class="text-primary-600 hover:text-primary-700 font-bold underline">Meta Developers</a></li>
                                        <li>Clique sur <strong>"Mes applications"</strong> puis <strong>"Créer une application"</strong></li>
                                        <li>Choisis le type <strong>"Business"</strong></li>
                                        <li>Remplis les informations et clique sur <strong>"Créer"</strong></li>
                                    </ol>
                                    <a href="https://developers.facebook.com/apps/create" target="_blank" 
                                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105">
                                        <i class="fab fa-meta mr-2"></i>
                                        Créer l'application Meta
                                        <i class="fas fa-external-link-alt ml-2"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button @click="step = 2" 
                                        class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105">
                                    Suivant <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Étape 2 : Ajouter WhatsApp -->
                        <div x-show="step === 2" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-x-10"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             class="bg-gradient-to-br from-secondary-50 to-secondary-100 border-2 border-secondary-300 rounded-2xl p-6 sm:p-8 shadow-xl" 
                             data-aos="fade-right">
                            <div class="flex items-start mb-6">
                                <div class="flex-shrink-0 bg-gradient-to-br from-secondary-600 to-secondary-700 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-xl mr-4 shadow-lg animate-pulse-glow">
                                    2
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 flex items-center">
                                        <i class="fab fa-whatsapp text-green-600 mr-2 text-2xl"></i>
                                        Ajouter WhatsApp à l'application
                                    </h4>
                                    <p class="text-sm sm:text-base text-gray-700 mb-4">
                                        Une fois l'application créée, ajoute le produit WhatsApp.
                                    </p>
                                    <ol class="list-decimal list-inside space-y-3 text-sm sm:text-base text-gray-700 mb-6 bg-white/60 rounded-xl p-4">
                                        <li>Dans le dashboard de ton application, cherche <strong>"Ajouter un produit"</strong></li>
                                        <li>Trouve <strong>"WhatsApp"</strong> dans la liste</li>
                                        <li>Clique sur <strong>"Configurer"</strong> à côté de WhatsApp</li>
                                        <li>Clique sur <strong>"Commencer"</strong> ou <strong>"Get Started"</strong></li>
                                    </ol>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <button @click="step = 1" 
                                        class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition-all duration-300">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Précédent
                                </button>
                                <button @click="step = 3" 
                                        class="px-6 py-3 bg-gradient-to-r from-secondary-600 to-secondary-700 text-white font-bold rounded-xl hover:from-secondary-700 hover:to-secondary-800 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105">
                                    Suivant <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Étape 3 : Récupérer les Credentials -->
                        <div x-show="step === 3" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-x-10"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             class="bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-300 rounded-2xl p-6 sm:p-8 shadow-xl" 
                             data-aos="fade-right">
                            <div class="flex items-start mb-6">
                                <div class="flex-shrink-0 bg-gradient-to-br from-green-600 to-green-700 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-xl mr-4 shadow-lg animate-pulse-glow">
                                    3
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 flex items-center">
                                        <i class="fas fa-key text-green-600 mr-2 text-2xl"></i>
                                        Récupérer les Credentials
                                    </h4>
                                    <p class="text-sm sm:text-base text-gray-700 mb-4">
                                        Copie ces informations depuis Meta Developers.
                                    </p>
                                    <ol class="list-decimal list-inside space-y-3 text-sm sm:text-base text-gray-700 mb-6 bg-white/60 rounded-xl p-4">
                                        <li>Dans le menu de gauche, clique sur <strong>"API Setup"</strong> ou <strong>"Configuration API"</strong></li>
                                        <li>Copie le <strong>"Phone number ID"</strong></li>
                                        <li>Copie le <strong>"WhatsApp Business Account ID"</strong></li>
                                        <li>Copie le <strong>"Temporary access token"</strong> (commence par EAA...)</li>
                                    </ol>
                                    <div class="bg-white rounded-xl p-4 border-2 border-green-200 shadow-md">
                                        <p class="text-sm text-gray-700 flex items-center">
                                            <i class="fas fa-lightbulb text-yellow-500 mr-2 text-lg"></i>
                                            <strong>💡 Astuce :</strong> Ouvre Meta Developers dans un autre onglet pour copier ces informations facilement
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <button @click="step = 2" 
                                        class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition-all duration-300">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Précédent
                                </button>
                                <button @click="step = 4" 
                                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105">
                                    Suivant <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Étape 4 : Entrer les Credentials -->
                        <div x-show="step === 4" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-x-10"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-300 rounded-2xl p-6 sm:p-8 shadow-xl" 
                             data-aos="fade-right">
                            <div class="flex items-start mb-6">
                                <div class="flex-shrink-0 bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-xl mr-4 shadow-lg animate-pulse-glow">
                                    4
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-paper-plane text-blue-600 mr-2 text-2xl"></i>
                                        Entrer les Credentials
                                    </h4>
                                    
                                    <!-- Formulaire -->
                                    <form method="POST" action="{{ route('dashboard.whatsapp.store') }}" class="space-y-5">
                                        @csrf

                                        <div>
                                            <label for="phone_number_id" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                                                <i class="fas fa-mobile-alt text-primary-600 mr-2"></i>
                                                Phone Number ID *
                                            </label>
                                            <input type="text" name="phone_number_id" id="phone_number_id" value="{{ old('phone_number_id', $connection->phone_number_id ?? '') }}" required
                                                   placeholder="Ex: 123456789012345"
                                                   class="w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all py-3 px-4">
                                            @error('phone_number_id')
                                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="whatsapp_business_account_id" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                                                <i class="fas fa-building text-primary-600 mr-2"></i>
                                                WhatsApp Business Account ID *
                                            </label>
                                            <input type="text" name="whatsapp_business_account_id" id="whatsapp_business_account_id" value="{{ old('whatsapp_business_account_id', $connection->whatsapp_business_account_id ?? '') }}" required
                                                   placeholder="Ex: 987654321098765"
                                                   class="w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all py-3 px-4">
                                            @error('whatsapp_business_account_id')
                                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="access_token" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                                                <i class="fas fa-key text-primary-600 mr-2"></i>
                                                Access Token (Temporaire) *
                                            </label>
                                            <textarea name="access_token" id="access_token" rows="4" required
                                                      placeholder="EAAxxxxxxxxxxxxx..."
                                                      class="w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all py-3 px-4">{{ old('access_token', $connection->access_token ?? '') }}</textarea>
                                            @error('access_token')
                                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                            <p class="mt-2 text-xs text-gray-600 bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-start">
                                                <i class="fas fa-exclamation-triangle text-yellow-600 mr-2 mt-0.5"></i>
                                                <span>⚠️ Ce token expire après 24h. Pour la production, tu devras créer un token permanent.</span>
                                            </p>
                                        </div>

                                        <div>
                                            <label for="phone_number" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                                                <i class="fas fa-phone text-primary-600 mr-2"></i>
                                                Numéro de téléphone (optionnel)
                                            </label>
                                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $connection->phone_number ?? '') }}"
                                                   placeholder="+33 6 12 34 56 78"
                                                   class="w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all py-3 px-4">
                                        </div>

                                        <div class="flex justify-between pt-4">
                                            <button type="button" @click="step = 3" 
                                                    class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition-all duration-300">
                                                <i class="fas fa-arrow-left mr-2"></i>
                                                Précédent
                                            </button>
                                            <button type="submit" 
                                                    class="px-8 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-105">
                                                <i class="fab fa-whatsapp mr-2"></i>
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
            <div class="mt-8 border-t-2 border-gray-200 pt-6" data-aos="fade-up">
                <h4 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-book text-primary-600 mr-2"></i>
                    📚 Ressources
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="https://developers.facebook.com/docs/whatsapp/cloud-api/get-started" target="_blank" 
                       class="flex items-center p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-primary-300 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="bg-primary-100 rounded-lg p-3 mr-4">
                            <i class="fas fa-book text-primary-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">Documentation WhatsApp API</p>
                            <p class="text-xs text-gray-500">Guide complet d'intégration</p>
                        </div>
                        <i class="fas fa-external-link-alt text-primary-600"></i>
                    </a>
                    <a href="https://developers.facebook.com/apps" target="_blank" 
                       class="flex items-center p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-primary-300 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="bg-primary-100 rounded-lg p-3 mr-4">
                            <i class="fab fa-meta text-primary-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">Mes applications Meta</p>
                            <p class="text-xs text-gray-500">Gérer vos applications</p>
                        </div>
                        <i class="fas fa-external-link-alt text-primary-600"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
