<x-admin-layout pageTitle="Configurer : {{ $provider->display_name }}">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Configurer {{ $provider->display_name }}</h2>
                        <p class="text-sm text-gray-600 mt-1">Configurez les clés API et paramètres de ce provider</p>
                    </div>
                    <a href="{{ route('admin.payment-providers') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.payment-providers.update', $provider) }}" method="POST" class="p-6">
                @csrf
                @method('PATCH')

                <div class="space-y-6">
                    <!-- Informations générales -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations générales</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="display_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nom d'affichage <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="display_name" 
                                       id="display_name" 
                                       value="{{ old('display_name', $provider->display_name) }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('display_name') border-red-500 @enderror">
                                @error('display_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('description') border-red-500 @enderror">{{ old('description', $provider->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="environment" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Environnement <span class="text-red-500">*</span>
                                    </label>
                                    <select name="environment" 
                                            id="environment" 
                                            required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('environment') border-red-500 @enderror">
                                        <option value="test" {{ old('environment', $provider->environment) === 'test' ? 'selected' : '' }}>Test / Sandbox</option>
                                        <option value="live" {{ old('environment', $provider->environment) === 'live' ? 'selected' : '' }}>Production / Live</option>
                                    </select>
                                    @error('environment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex items-center space-x-6">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           value="1"
                                           {{ old('is_active', $provider->is_active) ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Activer ce provider</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="is_default" 
                                           value="1"
                                           {{ old('is_default', $provider->is_default) ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Provider par défaut</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Credentials -->
                    @if(count($credentialFields) > 0)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Clés API</h3>
                            <p class="text-sm text-gray-500 mb-4">Configurez les clés API fournies par {{ $provider->display_name }}</p>
                            
                            <div class="space-y-4">
                                @foreach($credentialFields as $field => $label)
                                    <div>
                                        <label for="credentials_{{ $field }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                            {{ $label }}
                                        </label>
                                        <input type="password" 
                                               name="credentials[{{ $field }}]" 
                                               id="credentials_{{ $field }}" 
                                               value="{{ old("credentials.{$field}", $provider->getCredential($field)) }}"
                                               placeholder="Entrez votre {{ strtolower($label) }}"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error("credentials.{$field}") border-red-500 @enderror">
                                        @error("credentials.{$field}")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Laissez vide pour ne pas modifier</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Config -->
                    @if(count($configFields) > 0)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Configuration</h3>
                            
                            <div class="space-y-4">
                                @foreach($configFields as $field => $label)
                                    <div>
                                        <label for="config_{{ $field }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                            {{ $label }}
                                        </label>
                                        <input type="text" 
                                               name="config[{{ $field }}]" 
                                               id="config_{{ $field }}" 
                                               value="{{ old("config.{$field}", $provider->getConfig($field)) }}"
                                               placeholder="Entrez {{ strtolower($label) }}"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error("config.{$field}") border-red-500 @enderror">
                                        @error("config.{$field}")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Avertissement -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-800">
                                    <strong>Important :</strong> Les clés API sont sensibles. Assurez-vous de les garder secrètes et de ne les partager avec personne.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.payment-providers') }}" class="px-8 py-3.5 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Annuler
                        </a>
                        <button type="submit" class="px-8 py-3.5 bg-primary-50 border-2 border-primary-500 text-primary-700 font-bold tracking-wide rounded-lg hover:bg-primary-100 transition shadow-md flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

