@props(['pageTitle' => 'Administration'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }} - {{ config('app.name', 'Voicy Assistant') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar - Menu latéral avec palette officielle -->
        <aside class="w-72 bg-white border-r border-gray-200 shadow-lg flex-shrink-0">
            <div class="flex flex-col h-screen">
                <!-- Logo Header -->
                <div class="h-20 px-6 flex items-center border-b border-gray-200 bg-white">
                    <div class="flex items-center space-x-3">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-gray-900 leading-tight">Admin Panel</h1>
                            <p class="text-xs text-gray-500 font-medium">Voicy Assistant</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.index') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.index') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <!-- Utilisateurs -->
                    <a href="{{ route('admin.users') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.users*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="font-medium">Utilisateurs</span>
                    </a>

                    <!-- Plans -->
                    <a href="{{ route('admin.plans') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.plans*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.plans*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-medium">Plans</span>
                    </a>

                    <!-- Abonnements -->
                    <a href="{{ route('admin.subscriptions') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.subscriptions*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.subscriptions*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                        <span class="font-medium">Abonnements</span>
                    </a>

                    <!-- Transactions Paiements -->
                    <a href="{{ route('admin.payments') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.payments*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.payments*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="font-medium">Transactions</span>
                    </a>

                    <!-- Audios -->
                    <a href="{{ route('admin.audios') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.audios*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.audios*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                        <span class="font-medium">Audios</span>
                    </a>

                    <!-- Logs -->
                    <a href="{{ route('admin.logs') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.logs*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.logs*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-medium">Logs</span>
                    </a>

                    <!-- Commentaires -->
                    <a href="{{ route('admin.comments') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.comments*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.comments*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="font-medium">Commentaires</span>
                    </a>

                    <!-- Newsletter -->
                    <a href="{{ route('admin.newsletter') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.newsletter*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.newsletter*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium">Newsletter</span>
                    </a>

                    <!-- Messages de Contact -->
                    <a href="{{ route('admin.contact-messages') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.contact-messages*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.contact-messages*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium">Contact</span>
                    </a>

                    @php
                        $isSuperAdmin = auth()->user()->role === 'super_admin';
                    @endphp
                    @if($isSuperAdmin)
                        <!-- Administrateurs -->
                        <a href="{{ route('admin.admins') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.admins*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.admins*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="font-medium">Administrateurs</span>
                        </a>
                    @endif

                    <!-- Providers de paiement -->
                    <a href="{{ route('admin.payment-providers') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.payment-providers*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.payment-providers*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">Providers</span>
                    </a>

                    <a href="{{ route('admin.settings') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.settings*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">Paramètres</span>
                    </a>
                </nav>

                <!-- User Section - Zone profil fixée en bas -->
                <div class="mt-auto px-5 py-5 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                    <!-- Informations utilisateur -->
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-md ring-2 ring-primary-100">
                                <span class="text-white font-bold text-xl leading-none select-none">{{ strtoupper(substr(trim(auth()->user()->name), 0, 1)) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate leading-tight mb-0.5">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate leading-tight">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    
                    <!-- Bouton retour -->
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 rounded-lg transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Retour au dashboard</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-10">
                <div class="px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
                            <p class="text-sm text-gray-600 mt-1.5 font-medium">{{ now()->translatedFormat('l, d F Y') }}</p>
                        </div>
                        <div class="flex items-center space-x-6">
                            <div class="text-right">
                                <p class="text-lg font-bold text-primary-600" id="current-time">{{ now()->format('H:i') }}</p>
                                <p class="text-xs text-gray-500 font-medium">Heure actuelle</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="p-8">
                    <!-- Success Messages - Utilisation couleur officielle #10b981 -->
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
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

                    <!-- Error Messages - Utilisation couleur officielle #ef4444 -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-semibold text-red-800 mb-1">Erreurs détectées</h3>
                                    <ul class="text-sm text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li class="flex items-start">
                                                <span class="mr-2">•</span>
                                                <span>{{ $error }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Page Content -->
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Script pour mettre à jour l'heure en temps réel -->
    <script>
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const timeString = `${hours}:${minutes}`;
            
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }

        // Mettre à jour l'heure immédiatement
        updateTime();

        // Mettre à jour l'heure toutes les secondes
        setInterval(updateTime, 1000);
    </script>
</body>
</html>
