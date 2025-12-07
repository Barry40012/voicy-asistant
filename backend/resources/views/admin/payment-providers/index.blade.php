<x-admin-layout pageTitle="Gestion des Providers de Paiement">
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Providers de Paiement</h2>
                        <p class="text-sm text-gray-500 mt-1">Gérez les clés API et configurations des providers de paiement</p>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mx-6 mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($providers as $provider)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition {{ $provider->is_active ? 'bg-green-50 border-green-300' : 'bg-gray-50' }}">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $provider->display_name }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $provider->name }}</p>
                                </div>
                                <div class="flex flex-col items-end space-y-1">
                                    @if($provider->is_default)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-primary-600 text-white">
                                            Par défaut
                                        </span>
                                    @endif
                                    @if($provider->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-600 text-white">
                                            Actif
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-400 text-white">
                                            Inactif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($provider->description)
                                <p class="text-sm text-gray-600 mb-4">{{ $provider->description }}</p>
                            @endif

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Environnement:</span>
                                    <span class="font-medium {{ $provider->environment === 'live' ? 'text-green-600' : 'text-yellow-600' }}">
                                        {{ $provider->environment === 'live' ? 'Production' : 'Test' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Clés configurées:</span>
                                    <span class="font-medium">
                                        @php
                                            $configured = 0;
                                            $total = count($provider->credentials ?? []);
                                            foreach($provider->credentials ?? [] as $key => $value) {
                                                if (!empty($value)) $configured++;
                                            }
                                        @endphp
                                        {{ $configured }}/{{ $total }}
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('admin.payment-providers.edit', $provider) }}" class="block w-full text-center px-4 py-2 bg-primary-50 border-2 border-primary-500 text-primary-700 font-semibold rounded-lg hover:bg-primary-100 transition">
                                Configurer
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

