<x-guest-layout>
    @php
        $title = 'Connexion';
    @endphp

    <!-- Header -->
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full mb-4 shadow-lg">
            <i class="fas fa-user-lock text-white text-2xl"></i>
        </div>
        <h2 class="text-3xl font-bold text-gray-900">Connexion</h2>
        <p class="mt-2 text-sm text-gray-600">
            Connectez-vous à votre compte pour accéder à votre dashboard
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- CSRF Token Error -->
    @if ($errors->has('_token'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ $errors->first('_token') }}</p>
                    <p class="text-xs text-red-600 mt-1">Solution : Actualisez la page (F5) et réessayez.</p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6" id="login-form">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium flex items-center">
                <i class="fas fa-envelope text-primary-600 mr-2 text-sm"></i>
            </x-input-label>
            <x-text-input 
                id="email" 
                class="block mt-2 w-full rounded-lg border-2 border-gray-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all shadow-sm hover:shadow-md" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="votre@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-700 font-medium flex items-center">
                <i class="fas fa-lock text-primary-600 mr-2 text-sm"></i>
            </x-input-label>
            <x-text-input 
                id="password" 
                class="block mt-2 w-full rounded-lg border-2 border-gray-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all shadow-sm hover:shadow-md"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500" 
                    name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-primary-600 hover:text-primary-700 font-medium" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full flex justify-center items-center px-8 py-4 bg-primary-600 text-white text-lg font-semibold rounded-lg hover:bg-primary-700 transition shadow-lg hover:shadow-xl">
                {{ __('Se connecter') }}
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <!-- Register Link -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-700">
                Créez un compte gratuit
            </a>
        </p>
    </div>
</x-guest-layout>
