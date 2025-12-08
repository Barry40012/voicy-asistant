<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 leading-tight flex items-center">
                    <i class="fas fa-history text-secondary-600 mr-3 text-2xl"></i>
                    Historique des connexions
                </h2>
                <p class="text-sm text-gray-500 mt-1">Consultez l'historique de vos connexions à votre compte</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour au profil
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-200 overflow-hidden" data-aos="fade-up">
                <div class="bg-gradient-to-r from-secondary-500 to-secondary-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        Connexions récentes
                    </h3>
                </div>
                
                <div class="p-6">
                    @if($sessions->count() > 0)
                        <div class="space-y-4">
                            @foreach($sessions as $session)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border-2 border-gray-200 hover:border-secondary-300 transition-all" data-aos="fade-right" data-aos-delay="{{ $loop->index * 50 }}">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-full p-3 shadow-lg">
                                        <i class="fas fa-sign-in-alt text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $session->title }}</p>
                                        <p class="text-sm text-gray-600">{{ $session->message }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $session->created_at->format('d/m/Y à H:i') }}
                                        </p>
                                        @if($session->data && isset($session->data['ip_address']))
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-network-wired mr-1"></i>
                                            IP: {{ $session->data['ip_address'] }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Réussi
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-history text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500 font-semibold">Aucune connexion enregistrée</p>
                            <p class="text-sm text-gray-400 mt-2">Vos connexions futures apparaîtront ici</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg" data-aos="fade-up">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mr-3 mt-1"></i>
                    <div>
                        <p class="text-sm font-semibold text-blue-900 mb-1">À propos des connexions</p>
                        <p class="text-xs text-blue-700">
                            Cette page affiche l'historique de vos connexions à votre compte Voicy Assistant. 
                            Les notifications de connexion sont créées automatiquement lors de chaque connexion réussie 
                            (une fois par jour maximum pour éviter le spam).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

