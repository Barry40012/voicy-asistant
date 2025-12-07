<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Contact - Voicy Assistant</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ route('welcome') }}" class="flex items-center">
                            <x-app-logo class="h-8 sm:h-10" />
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Accueil
                        </a>
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="bg-primary-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary-700 transition">
                            Commencer gratuitement
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contact Section -->
        <section class="py-20 bg-gradient-to-br from-primary-50 via-white to-secondary-50">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-primary-100 rounded-full mb-6">
                        <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Contactez-nous</h1>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Une question ? Une suggestion ? Nous sommes là pour vous aider !
                    </p>
                </div>

                @if (session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-semibold text-red-800 mb-2">Erreurs détectées</h3>
                                <ul class="text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10 border-2 border-gray-100">
                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', auth()->user()->name ?? '') }}"
                                    required
                                    class="w-full px-5 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition shadow-sm hover:shadow-md bg-white"
                                    placeholder="Votre nom"
                                >
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', auth()->user()->email ?? '') }}"
                                    required
                                    class="w-full px-5 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition shadow-sm hover:shadow-md bg-white"
                                    placeholder="votre@email.com"
                                >
                            </div>
                        </div>

                        <!-- Sujet -->
                        <div>
                            <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                                Sujet <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="subject" 
                                name="subject" 
                                value="{{ old('subject') }}"
                                required
                                class="w-full px-5 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition shadow-sm hover:shadow-md bg-white"
                                placeholder="Sujet de votre message"
                            >
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                                Message <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="6"
                                required
                                minlength="20"
                                class="w-full px-5 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition resize-none shadow-sm hover:shadow-md bg-white"
                                placeholder="Votre message (minimum 20 caractères)..."
                            >{{ old('message') }}</textarea>
                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Minimum 20 caractères requis
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-4">
                            <button 
                                type="submit"
                                class="px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                            >
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Envoyer le message
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center border-2 border-gray-100 hover:border-primary-200 transition-all duration-200 hover:shadow-xl">
                        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-5 shadow-md">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Email</h3>
                        <p class="text-sm text-gray-600">support@voicyassistant.com</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-8 text-center border-2 border-gray-100 hover:border-primary-200 transition-all duration-200 hover:shadow-xl">
                        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-5 shadow-md">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Réponse</h3>
                        <p class="text-sm text-gray-600">Sous 24-48h</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-8 text-center border-2 border-gray-100 hover:border-primary-200 transition-all duration-200 hover:shadow-xl">
                        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-5 shadow-md">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Support</h3>
                        <p class="text-sm text-gray-600">7j/7 disponible</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-lg font-bold mb-4">Voicy Assistant</h3>
                        <p class="text-gray-400 text-sm">
                            Automatisez vos messages vocaux WhatsApp avec l'intelligence artificielle.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Produit</h4>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><a href="{{ route('welcome') }}" class="hover:text-white transition">Fonctionnalités</a></li>
                            <li><a href="{{ route('welcome') }}#pricing" class="hover:text-white transition">Tarifs</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Support</h4>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><a href="{{ route('contact.show') }}" class="hover:text-white transition">Contact</a></li>
                            <li><a href="#" class="hover:text-white transition">Documentation</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Légal</h4>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><a href="#" class="hover:text-white transition">CGU</a></li>
                            <li><a href="#" class="hover:text-white transition">Confidentialité</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-800 text-center text-sm text-gray-400">
                    <p>&copy; {{ date('Y') }} Voicy Assistant. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </body>
</html>

