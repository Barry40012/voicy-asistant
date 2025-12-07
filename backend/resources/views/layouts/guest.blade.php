<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' - ' : '' }}{{ config('app.name', 'Voicy Assistant') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-primary-50 via-white to-secondary-50">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 min-w-0">
                    <div class="flex items-center min-w-0 flex-shrink">
                        <a href="{{ route('welcome') }}" class="flex-shrink-0 min-w-0">
                            <x-app-logo class="h-8 sm:h-10" />
                        </a>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
                        @if (Route::currentRouteName() === 'login')
                            <span class="text-sm text-gray-600">Pas encore de compte ?</span>
                            <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm whitespace-nowrap">
                                S'inscrire
                            </a>
                        @else
                            <span class="text-sm text-gray-600">Déjà un compte ?</span>
                            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm whitespace-nowrap">
                                Se connecter
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 sm:px-0">
            <div class="w-full sm:max-w-md">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <a href="{{ route('welcome') }}" class="inline-block">
                        <div class="flex items-center justify-center mb-2">
                            <x-app-logo class="h-12" />
                        </div>
                        <p class="text-sm text-gray-600">Automatisez vos messages vocaux WhatsApp</p>
                    </a>
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 sm:px-10 sm:py-8">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-6 text-center text-sm text-gray-600">
                    <a href="{{ route('welcome') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        ← Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
