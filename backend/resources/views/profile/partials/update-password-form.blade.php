<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-lock mr-2 text-primary-600"></i>
                Mot de passe actuel *
            </label>
            <input id="update_password_current_password" 
                   name="current_password" 
                   type="password" 
                   autocomplete="current-password"
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-key mr-2 text-primary-600"></i>
                Nouveau mot de passe *
            </label>
            <input id="update_password_password" 
                   name="password" 
                   type="password" 
                   autocomplete="new-password"
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            <p class="mt-2 text-xs text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Le mot de passe doit contenir au moins 8 caractères.
            </p>
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-check-double mr-2 text-primary-600"></i>
                Confirmer le nouveau mot de passe *
            </label>
            <input id="update_password_password_confirmation" 
                   name="password_confirmation" 
                   type="password" 
                   autocomplete="new-password"
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-secondary-600 to-secondary-700 text-white font-bold rounded-xl hover:from-secondary-700 hover:to-secondary-800 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="fas fa-save mr-2"></i>
                Modifier le mot de passe
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   class="text-sm text-green-600 font-semibold flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    Mot de passe modifié avec succès !
                </p>
            @endif
        </div>
    </form>
</section>
