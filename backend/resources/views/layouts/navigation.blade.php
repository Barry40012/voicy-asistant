<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <x-app-logo class="h-9" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard.audios.index')" :active="request()->routeIs('dashboard.audios.*')">
                        {{ __('Audios') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard.whatsapp.index')" :active="request()->routeIs('dashboard.whatsapp.*')">
                        {{ __('WhatsApp') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard.subscription.index')" :active="request()->routeIs('dashboard.subscription.*')">
                        {{ __('Abonnement') }}
                    </x-nav-link>
                    @if(auth()->user()->isAdmin())
                        <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.*')">
                            {{ __('Admin') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Notifications -->
                <x-notifications-dropdown />
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center">
                                <span>{{ Auth::user()->name }}</span>
                                @php
                                    $activeSubscription = Auth::user()->activeSubscription();
                                @endphp
                                @if($activeSubscription)
                                    <span class="ml-2 px-3 py-1.5 text-xs font-bold rounded-full bg-gradient-to-r from-orange-50 to-amber-50 border-2 border-orange-300 flex items-center gap-2 relative overflow-hidden group">
                                        <!-- Animated background shimmer -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 animate-shimmer"></div>
                                        
                                        <!-- 3D Star without shadow -->
                                        <svg class="w-5 h-5 star-3d-gold relative z-10" fill="currentColor" viewBox="0 0 20 20">
                                            <defs>
                                                <linearGradient id="goldGradient{{ $activeSubscription->id }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                                    <stop offset="0%" style="stop-color:#FFD700;stop-opacity:1" />
                                                    <stop offset="50%" style="stop-color:#FFA500;stop-opacity:1" />
                                                    <stop offset="100%" style="stop-color:#FF8C00;stop-opacity:1" />
                                                </linearGradient>
                                                <filter id="glow{{ $activeSubscription->id }}">
                                                    <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                                                    <feMerge>
                                                        <feMergeNode in="coloredBlur"/>
                                                        <feMergeNode in="SourceGraphic"/>
                                                    </feMerge>
                                                </filter>
                                            </defs>
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" fill="url(#goldGradient{{ $activeSubscription->id }})" filter="url(#glow{{ $activeSubscription->id }})"></path>
                                        </svg>
                                        <span class="text-blue-600 font-extrabold relative z-10">{{ $activeSubscription->plan->name }}</span>
                                    </span>
                                @endif
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                            <i class="fas fa-user-circle mr-2 text-primary-600"></i>
                            Profil
                        </x-dropdown-link>
                        
                        <x-dropdown-link :href="route('profile.sessions')" class="flex items-center">
                            <i class="fas fa-history mr-2 text-secondary-600"></i>
                            Connexions
                        </x-dropdown-link>

                        <div class="border-t border-gray-200 my-1"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="flex items-center text-red-600 hover:text-red-700">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Déconnexion
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger & Notifications Mobile -->
            <div class="-me-2 flex items-center sm:hidden space-x-2">
                <!-- Notifications Mobile -->
                <x-notifications-dropdown />
                
                <!-- Hamburger -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" 
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="hidden sm:hidden border-t border-gray-200 bg-white shadow-lg">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard.audios.index')" :active="request()->routeIs('dashboard.audios.*')">
                {{ __('Audios') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard.whatsapp.index')" :active="request()->routeIs('dashboard.whatsapp.*')">
                {{ __('WhatsApp') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard.subscription.index')" :active="request()->routeIs('dashboard.subscription.*')">
                {{ __('Abonnement') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                @php
                    $activeSubscription = Auth::user()->activeSubscription();
                @endphp
                @if($activeSubscription)
                    <div class="mt-2">
                        <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-gradient-to-r from-orange-50 to-amber-50 border-2 border-orange-300 flex items-center gap-2 inline-flex relative overflow-hidden group">
                            <!-- Animated background shimmer -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 animate-shimmer"></div>
                            
                            <!-- 3D Star without shadow -->
                            <svg class="w-5 h-5 star-3d-gold relative z-10" fill="currentColor" viewBox="0 0 20 20">
                                <defs>
                                    <linearGradient id="goldGradientMobile{{ $activeSubscription->id }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#FFD700;stop-opacity:1" />
                                        <stop offset="50%" style="stop-color:#FFA500;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#FF8C00;stop-opacity:1" />
                                    </linearGradient>
                                    <filter id="glowMobile{{ $activeSubscription->id }}">
                                        <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                                        <feMerge>
                                            <feMergeNode in="coloredBlur"/>
                                            <feMergeNode in="SourceGraphic"/>
                                        </feMerge>
                                    </filter>
                                </defs>
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" fill="url(#goldGradientMobile{{ $activeSubscription->id }})" filter="url(#glowMobile{{ $activeSubscription->id }})"></path>
                            </svg>
                            <span class="text-blue-600 font-extrabold relative z-10">{{ $activeSubscription->plan->name }}</span>
                        </span>
                    </div>
                @endif
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center">
                    <i class="fas fa-user-circle mr-2 text-primary-600"></i>
                    Profil
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('profile.sessions')" class="flex items-center">
                    <i class="fas fa-history mr-2 text-secondary-600"></i>
                    Connexions
                </x-responsive-nav-link>

                <div class="border-t border-gray-200 my-2"></div>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="flex items-center text-red-600 hover:text-red-700">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Déconnexion
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
