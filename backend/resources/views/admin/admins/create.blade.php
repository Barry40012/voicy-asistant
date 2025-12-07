<x-admin-layout pageTitle="Créer un Administrateur">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Créer un Nouvel Administrateur</h2>
                        <p class="text-sm text-gray-600 mt-1">Ajoutez un nouvel administrateur avec des permissions spécifiques</p>
                    </div>
                    <a href="{{ route('admin.admins') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.admins.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Informations de base -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Mot de passe -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Mot de passe <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Confirmer le mot de passe <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>

                    <!-- Rôle -->
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                            Rôle <span class="text-red-500">*</span>
                        </label>
                        <select name="role" 
                                id="role" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('role') border-red-500 @enderror">
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin (toutes les permissions)</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Les super admins ont automatiquement toutes les permissions</p>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Permissions (seulement pour admin) -->
                    <div id="permissions-section">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Permissions
                        </label>
                        <p class="text-sm text-gray-500 mb-4">Sélectionnez les permissions pour cet administrateur (laissez vide pour toutes les permissions)</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($permissions as $permission)
                                <div class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                    <input type="checkbox" 
                                           name="permissions[]" 
                                           id="permission_{{ $permission->id }}" 
                                           value="{{ $permission->id }}"
                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                                           class="mt-1 h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label for="permission_{{ $permission->id }}" class="ml-3 flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $permission->name }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $permission->description }}</div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('permissions.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Boutons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.admins') }}" class="px-8 py-3.5 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Annuler
                        </a>
                        <button type="submit" class="px-8 py-3.5 bg-primary-50 border-2 border-primary-500 text-primary-700 font-bold tracking-wide rounded-lg hover:bg-primary-100 transition shadow-md flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Créer l'Administrateur
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Hide permissions section if super_admin is selected
        document.getElementById('role').addEventListener('change', function() {
            const permissionsSection = document.getElementById('permissions-section');
            if (this.value === 'super_admin') {
                permissionsSection.style.display = 'none';
                // Uncheck all permissions
                document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
            } else {
                permissionsSection.style.display = 'block';
            }
        });

        // Initial state
        if (document.getElementById('role').value === 'super_admin') {
            document.getElementById('permissions-section').style.display = 'none';
        }
    </script>
</x-admin-layout>

