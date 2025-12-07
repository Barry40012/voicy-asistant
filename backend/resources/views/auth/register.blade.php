<x-guest-layout>
    @php
        $title = 'Inscription';
    @endphp

    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Créer un compte</h2>
        <p class="mt-2 text-sm text-gray-600">
            Commencez gratuitement et automatisez vos messages vocaux WhatsApp
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nom complet')" class="text-gray-700 font-medium" />
            <x-text-input 
                id="name" 
                class="block mt-2 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" 
                type="text" 
                name="name" 
                :value="old('name')" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="Jean Dupont" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
            <x-text-input 
                id="email" 
                class="block mt-2 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autocomplete="username"
                placeholder="votre@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone (Optional) -->
        <div>
            <x-input-label for="phone" :value="__('Téléphone (optionnel)')" class="text-gray-700 font-medium" />
            <x-text-input 
                id="phone" 
                class="block mt-2 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" 
                type="tel" 
                name="phone" 
                :value="old('phone')" 
                autocomplete="tel"
                placeholder="+33 6 12 34 56 78" />
            <p class="mt-1 text-xs text-gray-500">Pour recevoir des notifications importantes</p>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-700 font-medium" />
            <x-text-input 
                id="password" 
                class="block mt-2 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                type="password"
                name="password"
                required 
                autocomplete="new-password"
                placeholder="••••••••" />
            <p class="mt-1 text-xs text-gray-500">Minimum 8 caractères</p>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="text-gray-700 font-medium" />
            <x-text-input 
                id="password_confirmation" 
                class="block mt-2 w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                type="password"
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms -->
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input 
                    id="terms" 
                    type="checkbox" 
                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500" 
                    required>
            </div>
            <div class="ml-3 text-sm">
                <label for="terms" class="text-gray-600">
                    J'accepte les 
                    <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">conditions d'utilisation</a>
                    et la 
                    <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">politique de confidentialité</a>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full flex justify-center items-center px-4 py-3 bg-primary-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg hover:shadow-xl">
                {{ __('Créer mon compte') }}
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </button>
        </div>
    </form>

    <!-- Divider -->
    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Ou</span>
            </div>
        </div>
    </div>

    <!-- Login Link -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Déjà un compte ?
            <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">
                Connectez-vous
            </a>
        </p>
    </div>
</x-guest-layout>
