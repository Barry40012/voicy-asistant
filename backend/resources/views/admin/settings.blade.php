<x-admin-layout pageTitle="Paramètres de la Plateforme">
    <div class="max-w-6xl mx-auto space-y-6">
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Paramètres de Contact -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-primary-50 to-primary-100">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-envelope mr-3 text-primary-600"></i>
                    Paramètres de Contact
                </h2>
                <p class="text-sm text-gray-600 mt-1">Configurez les informations de contact affichées sur le site</p>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6" id="contact-form">
                @csrf
                
                <div class="space-y-6">
                    <!-- Email de contact -->
                    <div>
                        <label for="contact_email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Adresse email de contact <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="contact_email" 
                               id="contact_email"
                               value="{{ old('contact_email', $contactSettings['contact_email']) }}"
                               required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all @error('contact_email') border-red-500 @enderror"
                               placeholder="info@voicyassistant.com">
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Cette adresse sera affichée sur la page d'accueil et utilisée pour les liens "mailto:"
                        </p>
                        @error('contact_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone de contact -->
                    <div>
                        <label for="contact_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            Numéro de téléphone (optionnel)
                        </label>
                        <input type="text" 
                               name="contact_phone" 
                               id="contact_phone"
                               value="{{ old('contact_phone', $contactSettings['contact_phone']) }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all @error('contact_phone') border-red-500 @enderror"
                               placeholder="+221 XX XXX XX XX">
                        @error('contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Bouton pour Contact -->
                    <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-semibold">
                            <i class="fas fa-save mr-2"></i>
                            Enregistrer les paramètres de contact
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Paramètres SMTP / Email -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-server mr-3 text-blue-600"></i>
                    Paramètres SMTP (Envoi d'emails)
                </h2>
                <p class="text-sm text-gray-600 mt-1">Configurez les paramètres d'envoi d'emails de la plateforme</p>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6" id="mail-form">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Adresse email d'envoi -->
                    <div class="md:col-span-2">
                        <label for="mail_from_address" class="block text-sm font-semibold text-gray-700 mb-2">
                            Adresse email d'envoi (expéditeur) <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="mail_from_address" 
                               id="mail_from_address"
                               value="{{ old('mail_from_address', $mailSettings['mail_from_address']) }}"
                               required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all @error('mail_from_address') border-red-500 @enderror"
                               placeholder="info.voicyassistant@gmail.com">
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Adresse email utilisée comme expéditeur pour tous les emails envoyés par la plateforme
                        </p>
                        @error('mail_from_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom de l'expéditeur -->
                    <div class="md:col-span-2">
                        <label for="mail_from_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom de l'expéditeur <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="mail_from_name" 
                               id="mail_from_name"
                               value="{{ old('mail_from_name', $mailSettings['mail_from_name']) }}"
                               required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all @error('mail_from_name') border-red-500 @enderror"
                               placeholder="Voicy Assistant">
                        @error('mail_from_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Serveur SMTP -->
                    <div>
                        <label for="mail_host" class="block text-sm font-semibold text-gray-700 mb-2">
                            Serveur SMTP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="mail_host" 
                               id="mail_host"
                               value="{{ old('mail_host', $mailSettings['mail_host']) }}"
                               required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all @error('mail_host') border-red-500 @enderror"
                               placeholder="smtp.gmail.com">
                        @error('mail_host')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Port SMTP -->
                    <div>
                        <label for="mail_port" class="block text-sm font-semibold text-gray-700 mb-2">
                            Port SMTP <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="mail_port" 
                               id="mail_port"
                               value="{{ old('mail_port', $mailSettings['mail_port']) }}"
                               required
                               min="1"
                               max="65535"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all @error('mail_port') border-red-500 @enderror"
                               placeholder="587">
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Port standard : 587 (TLS) ou 465 (SSL)
                        </p>
                        @error('mail_port')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom d'utilisateur SMTP -->
                    <div>
                        <label for="mail_username" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom d'utilisateur SMTP <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="mail_username" 
                               id="mail_username"
                               value="{{ old('mail_username', $mailSettings['mail_username']) }}"
                               required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all @error('mail_username') border-red-500 @enderror"
                               placeholder="info.voicyassistant@gmail.com">
                        @error('mail_username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe SMTP -->
                    <div>
                        <label for="mail_password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Mot de passe SMTP <span class="text-gray-500 text-xs">(laisser vide pour ne pas modifier)</span>
                        </label>
                        <input type="password" 
                               name="mail_password" 
                               id="mail_password"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all @error('mail_password') border-red-500 @enderror"
                               placeholder="••••••••">
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-lock mr-1"></i>
                            Laissez vide si vous ne souhaitez pas modifier le mot de passe actuel
                        </p>
                        @error('mail_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Chiffrement -->
                    <div class="md:col-span-2">
                        <label for="mail_encryption" class="block text-sm font-semibold text-gray-700 mb-2">
                            Type de chiffrement <span class="text-red-500">*</span>
                        </label>
                        <select name="mail_encryption" 
                                id="mail_encryption"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all @error('mail_encryption') border-red-500 @enderror">
                            <option value="tls" {{ old('mail_encryption', $mailSettings['mail_encryption']) === 'tls' ? 'selected' : '' }}>TLS (recommandé pour port 587)</option>
                            <option value="ssl" {{ old('mail_encryption', $mailSettings['mail_encryption']) === 'ssl' ? 'selected' : '' }}>SSL (pour port 465)</option>
                        </select>
                        @error('mail_encryption')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <div class="mt-6 pt-6 border-t border-gray-200 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.index') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                        Annuler
                    </a>
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer les paramètres
                    </button>
                </div>
            </form>
        </div>

        <!-- Gestion du Logo -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Logo de la Plateforme</h2>
                <p class="text-sm text-gray-600 mt-1">Téléversez un logo qui sera affiché sur toute la plateforme</p>
            </div>

            <div class="p-6">
                <form action="{{ route('admin.settings.logo') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Logo actuel -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Logo actuel</label>
                        <div class="flex items-center space-x-4">
                            @if($logoUrl)
                                <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                                    <img src="{{ asset($logoUrl) }}" alt="Logo actuel" class="h-20 w-auto max-w-xs object-contain">
                                </div>
                            @else
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 bg-gray-50 text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Aucun logo uploadé</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload nouveau logo -->
                    <div>
                        <label for="logo" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nouveau logo <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex items-center">
                            <input type="file" 
                                   name="logo" 
                                   id="logo" 
                                   accept="image/jpeg,image/png,image/jpg,image/svg+xml,image/webp"
                                   required
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 file:cursor-pointer border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('logo') border-red-500 @enderror">
                        </div>
                        <div class="mt-2 text-xs text-gray-500 space-y-1">
                            <p>• Formats acceptés : JPEG, PNG, JPG, SVG, WEBP</p>
                            <p>• Taille maximale : 2 MB</p>
                            <p>• Dimensions recommandées : 500x500 pixels maximum</p>
                        </div>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Aperçu -->
                    <div id="preview-container" class="hidden">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Aperçu</label>
                        <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                            <img id="preview" src="" alt="Aperçu" class="h-20 w-auto max-w-xs object-contain">
                        </div>
                    </div>

                    <!-- Bouton -->
                    <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="flex items-center justify-center px-8 py-3.5 bg-primary-50 border-2 border-primary-500 text-primary-700 rounded-lg hover:bg-primary-100 hover:border-primary-600 hover:text-primary-800 transition-all duration-200 font-bold text-sm shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            <span class="text-primary-700 font-bold tracking-wide">Téléverser le logo</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Aperçu du logo avant upload
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-admin-layout>
