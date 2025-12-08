<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-user mr-2 text-primary-600"></i>
                Nom complet *
            </label>
            <input id="name" 
                   name="name" 
                   type="text" 
                   value="{{ old('name', $user->name) }}" 
                   required 
                   autofocus 
                   autocomplete="name"
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-envelope mr-2 text-primary-600"></i>
                Adresse email *
            </label>
            <input id="email" 
                   name="email" 
                   type="email" 
                   value="{{ old('email', $user->email) }}" 
                   required 
                   autocomplete="username"
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-4 bg-yellow-50 border-2 border-yellow-200 rounded-xl">
                    <p class="text-sm text-yellow-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Votre adresse email n'est pas vérifiée.
                    </p>
                    <button form="send-verification" 
                            class="text-sm text-primary-600 hover:text-primary-700 font-semibold underline">
                        Cliquez ici pour renvoyer l'email de vérification.
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>
                            Un nouveau lien de vérification a été envoyé à votre adresse email.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="fas fa-save mr-2"></i>
                Enregistrer les modifications
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   class="text-sm text-green-600 font-semibold flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    Enregistré avec succès !
                </p>
            @endif
        </div>
    </form>
</section>
