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
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- AOS Animation Library -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        
        <!-- Canvas Confetti Library -->
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50" x-data="{ submitted: false, showSuccess: false }" onload="AOS.init({ duration: 1000, once: true, offset: 100 })">
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
        <section class="relative py-20 bg-gradient-to-br from-green-50 via-green-100 to-green-50 overflow-hidden">
            <!-- Animated Confetti Background -->
            <canvas id="contact-confetti-canvas" class="absolute inset-0 w-full h-full pointer-events-none" style="z-index: 0;"></canvas>
            
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <!-- Header -->
                <div class="text-center mb-16" data-aos="fade-down">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full mb-6 shadow-2xl border-4 border-white overflow-hidden">
                        <i class="fas fa-envelope text-white text-4xl"></i>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Contactez-nous</h1>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Une question ? Une suggestion ? Nous sommes là pour vous aider !
                    </p>
                </div>

                <!-- Success Message with Animation -->
                @if (session('success'))
                    <div class="mb-8 max-w-2xl mx-auto bg-green-50 border border-green-200 p-6 rounded-xl shadow-lg" data-aos="zoom-in" x-data="{ show: true }" x-show="show" x-transition>
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-green-500 rounded-full w-12 h-12 flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-semibold text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-8 max-w-2xl mx-auto bg-red-50 border border-red-200 p-6 rounded-xl shadow-lg" data-aos="shake">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-red-500 rounded-full w-12 h-12 flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-base font-semibold text-red-800 mb-2">Erreurs détectées</h3>
                                <ul class="text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10 border-2 border-primary-200" data-aos="fade-up">
                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-6" @submit="submitted = true" x-show="!showSuccess">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <label for="name" class="block text-sm font-bold text-gray-800 mb-3">
                                    <i class="fas fa-user text-primary-600 mr-2"></i>
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', auth()->user()->name ?? '') }}"
                                    required
                                    class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-primary-200 focus:border-primary-500 transition-all shadow-sm hover:shadow-md bg-white text-gray-900 placeholder-gray-400"
                                    placeholder="Votre nom"
                                >
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-800 mb-3">
                                    <i class="fas fa-envelope text-primary-600 mr-2"></i>
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', auth()->user()->email ?? '') }}"
                                    required
                                    class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-primary-200 focus:border-primary-500 transition-all shadow-sm hover:shadow-md bg-white text-gray-900 placeholder-gray-400"
                                    placeholder="votre@email.com"
                                >
                            </div>
                        </div>

                        <!-- Sujet -->
                        <div>
                            <label for="subject" class="block text-sm font-bold text-gray-800 mb-3">
                                <i class="fas fa-tag text-primary-600 mr-2"></i>
                                Sujet <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="subject" 
                                name="subject" 
                                value="{{ old('subject') }}"
                                required
                                class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-primary-200 focus:border-primary-500 transition-all shadow-sm hover:shadow-md bg-white text-gray-900 placeholder-gray-400"
                                placeholder="Sujet de votre message"
                            >
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-bold text-gray-800 mb-3">
                                <i class="fas fa-comment-alt text-primary-600 mr-2"></i>
                                Message <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="6"
                                required
                                minlength="10"
                                class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-primary-200 focus:border-primary-500 resize-none transition-all shadow-sm hover:shadow-md bg-white text-gray-900 placeholder-gray-400"
                                placeholder="Votre message (minimum 10 caractères)..."
                            >{{ old('message') }}</textarea>
                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle text-primary-500 mr-1"></i>
                                Minimum 10 caractères requis
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-4">
                            <button 
                                type="submit"
                                :disabled="submitted"
                                class="inline-flex items-center justify-center px-8 py-4 bg-primary-600 text-white text-lg font-semibold rounded-lg hover:bg-primary-700 transition shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span class="flex items-center justify-center" x-show="!submitted">
                                    Envoyer le message
                                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </span>
                                <span class="flex items-center justify-center" x-show="submitted">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Envoi en cours...
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Success Animation -->
                    <div x-show="showSuccess" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="text-center py-12" style="display: none;">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-green-400 to-green-600 rounded-full mb-6 shadow-2xl animate-bounce">
                            <i class="fas fa-check text-white text-4xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">Message envoyé !</h2>
                        <p class="text-lg text-gray-600 mb-6">Nous vous répondrons dans les plus brefs délais.</p>
                        <a href="{{ route('welcome') }}" class="inline-flex items-center justify-center px-8 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition">
                            <i class="fas fa-home mr-2"></i>
                            Retour à l'accueil
                        </a>
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

        <script>
            // Continuous animated confetti background
            document.addEventListener('DOMContentLoaded', function() {
                const canvas = document.getElementById('contact-confetti-canvas');
                if (!canvas) return;
                
                const ctx = canvas.getContext('2d');
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                
                const particles = [];
                const particleCount = 30;
                const colors = ['#10b981', '#34d399', '#6ee7b7', '#a7f3d0', '#d1fae5', '#0ea5e9', '#3b82f6'];
                
                class Particle {
                    constructor() {
                        this.reset();
                        this.y = Math.random() * canvas.height;
                    }
                    
                    reset() {
                        this.x = Math.random() * canvas.width;
                        this.y = -10;
                        this.size = Math.random() * 5 + 2;
                        this.speed = Math.random() * 2 + 1;
                        this.color = colors[Math.floor(Math.random() * colors.length)];
                        this.opacity = Math.random() * 0.5 + 0.3;
                        this.rotation = Math.random() * 360;
                        this.rotationSpeed = Math.random() * 2 - 1;
                    }
                    
                    update() {
                        this.y += this.speed;
                        this.rotation += this.rotationSpeed;
                        this.x += Math.sin(this.y * 0.01) * 0.5;
                        
                        if (this.y > canvas.height) {
                            this.reset();
                        }
                    }
                    
                    draw() {
                        ctx.save();
                        ctx.globalAlpha = this.opacity;
                        ctx.fillStyle = this.color;
                        ctx.translate(this.x, this.y);
                        ctx.rotate(this.rotation * Math.PI / 180);
                        ctx.beginPath();
                        ctx.arc(0, 0, this.size, 0, Math.PI * 2);
                        ctx.fill();
                        ctx.restore();
                    }
                }
                
                for (let i = 0; i < particleCount; i++) {
                    particles.push(new Particle());
                }
                
                function animate() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    particles.forEach(particle => {
                        particle.update();
                        particle.draw();
                    });
                    requestAnimationFrame(animate);
                }
                
                animate();
                
                window.addEventListener('resize', () => {
                    canvas.width = window.innerWidth;
                    canvas.height = window.innerHeight;
                });
            });

            // Confetti animation on successful form submission
            @if (session('success'))
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(function() {
                        // Confetti avec les couleurs de la plateforme
                        confetti({
                            particleCount: 150,
                            spread: 80,
                            origin: { y: 0.6 },
                            colors: ['#0ea5e9', '#a855f7', '#10b981', '#3b82f6']
                        });
                        
                        // Confetti supplémentaire après un court délai
                        setTimeout(function() {
                            confetti({
                                particleCount: 100,
                                angle: 60,
                                spread: 55,
                                origin: { x: 0 },
                                colors: ['#0ea5e9', '#a855f7', '#10b981']
                            });
                            confetti({
                                particleCount: 100,
                                angle: 120,
                                spread: 55,
                                origin: { x: 1 },
                                colors: ['#0ea5e9', '#a855f7', '#10b981']
                            });
                        }, 300);
                    }, 500);
                });
            @endif
        </script>
    </body>
</html>

