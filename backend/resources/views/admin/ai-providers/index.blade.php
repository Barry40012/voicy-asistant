<x-admin-layout pageTitle="Gestion des Providers IA">
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Providers IA</h2>
                        <p class="text-sm text-gray-500 mt-1">Gérez les clés API et configurations des providers d'intelligence artificielle</p>
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
                                    <span class="text-gray-500">Clé API:</span>
                                    <span class="font-medium">
                                        @php
                                            $apiKey = $provider->getCredential('api_key');
                                            $hasKey = !empty($apiKey);
                                        @endphp
                                        @if($hasKey)
                                            <span class="text-green-600">✓ Configurée</span>
                                        @else
                                            <span class="text-red-600">✗ Non configurée</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('admin.ai-providers.edit', $provider) }}" class="flex-1 text-center px-4 py-2 bg-primary-50 border-2 border-primary-500 text-primary-700 font-semibold rounded-lg hover:bg-primary-100 transition">
                                    Configurer
                                </a>
                                @if($hasKey)
                                    <button onclick="testProvider({{ $provider->id }})" class="px-4 py-2 bg-green-50 border-2 border-green-500 text-green-700 font-semibold rounded-lg hover:bg-green-100 transition">
                                        Tester
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function testProvider(providerId) {
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Test en cours...';
            button.disabled = true;

            fetch(`/admin/ai-providers/${providerId}/test`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ' + data.message);
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(error => {
                alert('❌ Erreur lors du test: ' + error.message);
            })
            .finally(() => {
                button.textContent = originalText;
                button.disabled = false;
            });
        }
    </script>
</x-admin-layout>

