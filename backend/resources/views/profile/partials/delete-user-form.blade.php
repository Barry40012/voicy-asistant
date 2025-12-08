<section class="space-y-6">
    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4">
        <p class="text-sm text-red-800">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Attention :</strong> Une fois votre compte supprimé, toutes vos ressources et données seront définitivement supprimées. 
            Avant de supprimer votre compte, veuillez télécharger toutes les données ou informations que vous souhaitez conserver.
        </p>
    </div>

    <button x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold rounded-xl hover:from-red-700 hover:to-red-800 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
        <i class="fas fa-trash-alt mr-2"></i>
        Supprimer mon compte
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-center mb-4">
                <div class="bg-red-100 rounded-full p-3 mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900">
                    Êtes-vous sûr de vouloir supprimer votre compte ?
                </h2>
            </div>

            <p class="mt-4 text-sm text-gray-600">
                Une fois votre compte supprimé, toutes vos ressources et données seront définitivement supprimées. 
                Veuillez entrer votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.
            </p>

            <div class="mt-6">
                <label for="password" class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-red-600"></i>
                    Mot de passe *
                </label>
                <input id="password"
                       name="password"
                       type="password"
                       class="mt-1 block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-red-500 focus:ring-4 focus:ring-red-200 transition-all"
                       placeholder="Entrez votre mot de passe pour confirmer">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" 
                        x-on:click="$dispatch('close')"
                        class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition-all">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>

                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold rounded-xl hover:from-red-700 hover:to-red-800 transition-all shadow-lg">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Supprimer définitivement
                </button>
            </div>
        </form>
    </x-modal>
</section>
