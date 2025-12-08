<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 leading-tight flex items-center">
                    <i class="fas fa-user-circle text-primary-600 mr-3 text-2xl"></i>
                    Mon Profil
                </h2>
                <p class="text-sm text-gray-500 mt-1">Gérez vos informations personnelles et paramètres de compte</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status') === 'profile-updated')
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-md" data-aos="fade-right">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <p class="text-sm font-semibold text-green-800">Profil mis à jour avec succès !</p>
                    </div>
                </div>
            @endif

            @if (session('status') === 'password-updated')
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-md" data-aos="fade-right">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <p class="text-sm font-semibold text-green-800">Mot de passe modifié avec succès !</p>
                    </div>
                </div>
            @endif

            <!-- User Info Card -->
            <div class="bg-gradient-to-br from-primary-50 via-white to-secondary-50 rounded-2xl shadow-xl border-2 border-primary-200 overflow-hidden mb-6" data-aos="fade-down">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-24 h-24 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center shadow-2xl border-4 border-white">
                                <span class="text-4xl font-bold text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        
                        <!-- User Info -->
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ auth()->user()->name }}</h3>
                            <p class="text-gray-600 mb-3 flex items-center">
                                <i class="fas fa-envelope mr-2 text-primary-600"></i>
                                {{ auth()->user()->email }}
                            </p>
                            @if(auth()->user()->phone)
                            <p class="text-gray-600 mb-3 flex items-center">
                                <i class="fas fa-phone mr-2 text-primary-600"></i>
                                {{ auth()->user()->phone }}
                            </p>
                            @endif
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-xs font-semibold">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Membre depuis {{ auth()->user()->created_at->format('M Y') }}
                                </span>
                                @if(auth()->user()->email_verified_at)
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Email vérifié
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Forms -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Profile Information -->
                    <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-200 overflow-hidden" data-aos="fade-up">
                        <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-6 py-4">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-user-edit mr-2"></i>
                                Informations du profil
                            </h3>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Update Password -->
                    <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-200 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                        <div class="bg-gradient-to-r from-secondary-500 to-secondary-600 px-6 py-4">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-lock mr-2"></i>
                                Modifier le mot de passe
                            </h3>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="bg-white rounded-2xl shadow-lg border-2 border-red-200 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Zone de danger
                            </h3>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

                <!-- Right Column: Info & Stats -->
                <div class="space-y-6">
                    <!-- Subscription Info -->
                    @php
                        $subscription = auth()->user()->activeSubscription();
                    @endphp
                    @if($subscription)
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl shadow-lg border-2 border-green-200 p-6" data-aos="fade-left">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-crown text-yellow-500 mr-2"></i>
                            Abonnement actif
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Plan</span>
                                <span class="font-bold text-primary-600">{{ $subscription->plan->name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Expire le</span>
                                <span class="font-bold text-gray-900">{{ $subscription->expires_at->format('d/m/Y') }}</span>
                            </div>
                            <a href="{{ route('dashboard.subscription.index') }}" class="block mt-4 text-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-all text-sm font-semibold">
                                Gérer l'abonnement
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Supported Languages -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-lg border-2 border-blue-200 p-6" data-aos="fade-left" data-aos-delay="100">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-globe text-blue-600 mr-2"></i>
                            Langues supportées
                        </h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Le système détecte automatiquement la langue de vos messages vocaux et génère des réponses dans la même langue.
                        </p>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between bg-white rounded-lg p-3 border border-blue-200">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-3">🇫🇷</span>
                                    <div>
                                        <p class="font-semibold text-gray-900">Français</p>
                                        <p class="text-xs text-gray-500">Détection automatique</p>
                                    </div>
                                </div>
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                            <div class="flex items-center justify-between bg-white rounded-lg p-3 border border-blue-200">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-3">🇬🇧</span>
                                    <div>
                                        <p class="font-semibold text-gray-900">Anglais</p>
                                        <p class="text-xs text-gray-500">Détection automatique</p>
                                    </div>
                                </div>
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-blue-100 rounded-lg">
                            <p class="text-xs text-blue-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Note :</strong> Le système peut également gérer les messages en langues mixtes (français + anglais).
                            </p>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    @php
                        $totalAudios = auth()->user()->audios()->count();
                        $processedAudios = auth()->user()->audios()->where('status', 'done')->count();
                    @endphp
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl shadow-lg border-2 border-purple-200 p-6" data-aos="fade-left" data-aos-delay="200">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                            Statistiques rapides
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Total d'audios</span>
                                <span class="font-bold text-purple-600">{{ $totalAudios }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Audios traités</span>
                                <span class="font-bold text-green-600">{{ $processedAudios }}</span>
                            </div>
                            <a href="{{ route('dashboard.audios.index') }}" class="block mt-4 text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all text-sm font-semibold">
                                Voir tous les audios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
