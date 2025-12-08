<x-guest-layout>
    @php
        $title = 'Connexion';
    @endphp

    <!-- Header -->
    <div class="mb-6 sm:mb-8 text-center" data-aos="fade-down">
        <div class="inline-flex items-center justify-center w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-primary-500 via-primary-600 to-secondary-600 rounded-full mb-4 sm:mb-6 shadow-2xl border-4 border-white animate-pulse-glow relative">
            <i class="fas fa-user-lock text-white text-2xl sm:text-3xl relative z-10"></i>
            <div class="absolute inset-0 bg-primary-400 rounded-full animate-ping opacity-20"></div>
        </div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2 sm:mb-3">Connexion</h2>
        <p class="text-sm sm:text-base text-gray-600 max-w-md mx-auto px-4">
            Connectez-vous à votre compte pour accéder à votre dashboard
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- CSRF Token Error -->
    @if ($errors->has('_token'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg" data-aos="shake">
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

    <form method="POST" action="{{ route('login') }}" class="space-y-5 sm:space-y-6" id="login-form" x-data="{ submitted: false }" @submit="submitted = true">
        @csrf

        <!-- Email Address -->
        <div class="field-container" data-aos="fade-right" data-aos-delay="0">
            <x-input-label for="email" :value="__('Email')" class="field-label text-gray-700 font-bold flex items-center mb-2 sm:mb-3">
                <i class="fas fa-envelope field-icon text-primary-600 mr-2 text-sm sm:text-base"></i>
            </x-input-label>
            <div class="relative">
                <x-text-input 
                    id="email" 
                    class="field-input block w-full pl-12 pr-4 py-3 sm:py-4 rounded-xl border-2 border-gray-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all shadow-sm hover:shadow-md bg-white text-gray-900 placeholder-gray-400" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="votre@email.com" />
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                    <i class="fas fa-envelope text-gray-400 transition-colors duration-300"></i>
                </div>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="field-container" data-aos="fade-left" data-aos-delay="100">
            <x-input-label for="password" :value="__('Mot de passe')" class="field-label text-gray-700 font-bold flex items-center mb-2 sm:mb-3">
                <i class="fas fa-lock field-icon text-primary-600 mr-2 text-sm sm:text-base"></i>
            </x-input-label>
            <div class="relative">
                <x-text-input 
                    id="password" 
                    class="field-input block w-full pl-12 pr-4 py-3 sm:py-4 rounded-xl border-2 border-gray-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-200 transition-all shadow-sm hover:shadow-md bg-white text-gray-900 placeholder-gray-400"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password"
                    placeholder="••••••••" />
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                    <i class="fas fa-lock text-gray-400 transition-colors duration-300"></i>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0" data-aos="fade-up" data-aos-delay="200">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 transition-all group-hover:scale-110" 
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">{{ __('Se souvenir de moi') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-primary-600 hover:text-primary-700 font-semibold transition-all hover:underline flex items-center" href="{{ route('password.request') }}">
                    <i class="fas fa-key mr-1"></i>
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div data-aos="fade-up" data-aos-delay="300">
            <button 
                type="submit" 
                :disabled="submitted"
                class="group relative w-full flex justify-center items-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-base sm:text-lg font-semibold rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 active:scale-95 overflow-hidden"
            >
                <!-- Shimmer effect -->
                <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-0 group-hover:opacity-20 group-hover:animate-shimmer"></span>
                
                <span class="flex items-center justify-center relative z-10" x-show="!submitted">
                    <i class="fas fa-sign-in-alt mr-2 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                    {{ __('Se connecter') }}
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                </span>
                <span class="flex items-center justify-center relative z-10" x-show="submitted">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Connexion en cours...
                </span>
            </button>
        </div>
    </form>

    <!-- Divider -->
    <div class="mt-6 sm:mt-8" data-aos="fade-up" data-aos-delay="400">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-3 sm:px-4 bg-white text-gray-500 font-medium">Ou</span>
            </div>
        </div>
    </div>

    <!-- Register Link -->
    <div class="mt-6 sm:mt-8 text-center" data-aos="fade-up" data-aos-delay="500">
        <p class="text-sm sm:text-base text-gray-600">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="font-bold text-primary-600 hover:text-primary-700 transition-all hover:underline inline-flex items-center ml-1">
                <i class="fas fa-user-plus mr-1"></i>
                Créez un compte gratuit
            </a>
        </p>
    </div>
</x-guest-layout>
