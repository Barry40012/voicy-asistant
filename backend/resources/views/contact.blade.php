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
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/react-app.jsx'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- AOS Animation Library -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        
        <!-- Canvas Confetti Library -->
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
        
        <style>
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.3); }
                50% { box-shadow: 0 0 40px rgba(16, 185, 129, 0.6); }
            }
            @keyframes shimmer {
                0% { background-position: -1000px 0; }
                100% { background-position: 1000px 0; }
            }
            @keyframes iconBounce {
                0%, 100% { transform: translateY(0) rotate(0deg); }
                25% { transform: translateY(-5px) rotate(-5deg); }
                75% { transform: translateY(-5px) rotate(5deg); }
            }
            @keyframes labelFloat {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-3px); }
            }
            @keyframes successCheck {
                0% { transform: scale(0) rotate(-180deg); }
                50% { transform: scale(1.2) rotate(0deg); }
                100% { transform: scale(1) rotate(0deg); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.6s ease-out forwards;
            }
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            .animate-pulse-glow {
                animation: pulse-glow 3s ease-in-out infinite;
            }
            .field-container {
                position: relative;
                transition: all 0.3s ease;
            }
            .field-container:hover {
                transform: translateY(-2px);
            }
            .field-container:focus-within {
                transform: translateY(-4px);
            }
            .field-container:focus-within .field-icon {
                animation: iconBounce 0.6s ease-in-out;
                color: #10b981;
            }
            .field-container:focus-within .field-label {
                animation: labelFloat 1s ease-in-out infinite;
                color: #10b981;
            }
            .field-input {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
            }
            .field-input:focus {
                transform: scale(1.02);
                box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2), 0 0 0 4px rgba(16, 185, 129, 0.1);
            }
            .field-input:hover:not(:focus) {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(16, 185, 129, 0.15);
            }
            .field-icon {
                transition: all 0.3s ease;
            }
            .field-label {
                transition: all 0.3s ease;
            }
            .shimmer-effect {
                background: linear-gradient(
                    90deg,
                    transparent,
                    rgba(255, 255, 255, 0.4),
                    transparent
                );
                background-size: 1000px 100%;
                animation: shimmer 3s infinite;
            }
            .success-animation {
                animation: successCheck 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            }
        </style>
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

                <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 md:p-10 border-2 border-green-200 relative overflow-hidden" data-aos="fade-up">
                    <!-- Shimmer effect overlay -->
                    <div class="absolute inset-0 shimmer-effect opacity-0 hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    
                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-6 relative z-10" @submit="submitted = true" x-show="!showSuccess">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div class="field-container" data-aos="fade-right" data-aos-delay="0">
                                <label for="name" class="field-label block text-sm font-bold text-gray-800 mb-3">
                                    <i class="fas fa-user field-icon text-green-600 mr-2"></i>
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name', auth()->user()->name ?? '') }}"
                                        required
                                        class="field-input w-full px-6 py-4 pl-12 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all shadow-sm hover:shadow-md bg-white text-gray-900 placeholder-gray-400"
                                        placeholder="Votre nom"
                                    >
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                        <i class="fas fa-user text-gray-400 transition-colors duration-300"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="field-container" data-aos="fade-left" data-aos-delay="100">
                                <label for="email" class="field-label block text-sm font-bold text-gray-800 mb-3">
                                    <i class="fas fa-envelope field-icon text-green-600 mr-2"></i>
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email', auth()->user()->email ?? '') }}"
                                        required
                                        class="field-input w-full px-6 py-4 pl-12 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all shadow-sm hover:shadow-md bg-white text-gray-900 placeholder-gray-400"
                                        placeholder="votre@email.com"
                                    >
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400 transition-colors duration-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sujet -->
                        <div class="field-container" data-aos="fade-up" data-aos-delay="200">
                            <label for="subject" class="field-label block text-sm font-bold text-gray-800 mb-3">
                                <i class="fas fa-tag field-icon text-green-600 mr-2"></i>
                                Sujet <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="subject" 
                                    name="subject" 
                                    value="{{ old('subject') }}"
                                    required
                                    class="field-input w-full px-6 py-4 pl-12 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all shadow-sm hover:shadow-md bg-white text-gray-900 placeholder-gray-400"
                                    placeholder="Sujet de votre message"
                                >
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="fas fa-tag text-gray-400 transition-colors duration-300"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="field-container" data-aos="fade-up" data-aos-delay="300">
                            <label for="message" class="field-label block text-sm font-bold text-gray-800 mb-3">
                                <i class="fas fa-comment-alt field-icon text-green-600 mr-2"></i>
                                Message <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    rows="6"
                                    required
                                    minlength="10"
                                    class="field-input w-full px-6 py-4 pl-12 pt-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 resize-none transition-all shadow-sm hover:shadow-md bg-white text-gray-900 placeholder-gray-400"
                                    placeholder="Votre message (minimum 10 caractères)..."
                                >{{ old('message') }}</textarea>
                                <div class="absolute left-4 top-6 pointer-events-none">
                                    <i class="fas fa-comment-alt text-gray-400 transition-colors duration-300"></i>
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <p class="text-xs text-gray-500 flex items-center">
                                        <i class="fas fa-info-circle text-green-500 mr-1 animate-pulse"></i>
                                        Minimum 10 caractères requis
                                    </p>
                                    <p class="text-xs text-gray-400" x-data="{ count: 0 }" x-init="$watch('$el.previousElementSibling.value', value => count = value ? value.length : 0)">
                                        <span x-text="count" class="font-semibold" :class="count >= 10 ? 'text-green-600' : 'text-gray-400'"></span>/10
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-4" data-aos="fade-up" data-aos-delay="400">
                            <button 
                                type="submit"
                                :disabled="submitted"
                                class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-green-600 to-green-700 text-white text-base sm:text-lg font-semibold rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 active:scale-95 overflow-hidden"
                            >
                                <!-- Shimmer effect on button -->
                                <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-0 group-hover:opacity-20 group-hover:animate-shimmer"></span>
                                
                                <span class="flex items-center justify-center relative z-10" x-show="!submitted">
                                    <i class="fas fa-paper-plane mr-2 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                                    Envoyer le message
                                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                                </span>
                                <span class="flex items-center justify-center relative z-10" x-show="submitted">
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
                    <div x-show="showSuccess" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95 rotate-12" x-transition:enter-end="opacity-100 scale-100 rotate-0" class="text-center py-12 relative z-10" style="display: none;">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-green-400 to-green-600 rounded-full mb-6 shadow-2xl success-animation relative">
                            <i class="fas fa-check text-white text-4xl relative z-10"></i>
                            <div class="absolute inset-0 bg-green-400 rounded-full animate-ping opacity-75"></div>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3 animate-fade-in-up">Message envoyé !</h2>
                        <p class="text-base sm:text-lg text-gray-600 mb-6 animate-fade-in-up" style="animation-delay: 0.2s;">Nous vous répondrons dans les plus brefs délais.</p>
                        <a href="{{ route('welcome') }}" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 animate-fade-in-up" style="animation-delay: 0.4s;">
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

            // Enhanced field animations
            document.addEventListener('DOMContentLoaded', function() {
                // Add focus animations to all inputs
                const inputs = document.querySelectorAll('.field-input');
                inputs.forEach(input => {
                    // Add ripple effect on focus
                    input.addEventListener('focus', function() {
                        this.parentElement.classList.add('ring-4', 'ring-green-200');
                        const icon = this.parentElement.querySelector('i');
                        if (icon) {
                            icon.classList.remove('text-gray-400');
                            icon.classList.add('text-green-600', 'scale-110');
                        }
                    });
                    
                    input.addEventListener('blur', function() {
                        this.parentElement.classList.remove('ring-4', 'ring-green-200');
                        const icon = this.parentElement.querySelector('i');
                        if (icon && !this.value) {
                            icon.classList.add('text-gray-400');
                            icon.classList.remove('text-green-600', 'scale-110');
                        }
                    });
                    
                    // Animate on input
                    input.addEventListener('input', function() {
                        if (this.value) {
                            const icon = this.parentElement.querySelector('i');
                            if (icon) {
                                icon.classList.add('animate-pulse');
                                setTimeout(() => {
                                    icon.classList.remove('animate-pulse');
                                }, 500);
                            }
                        }
                    });
                });
                
                // Form submission animation
                const form = document.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        // Add success animation after a short delay
                        setTimeout(() => {
                            const button = this.querySelector('button[type="submit"]');
                            if (button) {
                                button.classList.add('animate-pulse-glow');
                            }
                        }, 100);
                    });
                }
            });

            // Confetti animation on successful form submission
            @if (session('success'))
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(function() {
                        // Confetti avec les couleurs vertes
                        confetti({
                            particleCount: 200,
                            spread: 90,
                            origin: { y: 0.6 },
                            colors: ['#10b981', '#34d399', '#6ee7b7', '#a7f3d0', '#0ea5e9']
                        });
                        
                        // Confetti supplémentaire après un court délai
                        setTimeout(function() {
                            confetti({
                                particleCount: 150,
                                angle: 60,
                                spread: 65,
                                origin: { x: 0 },
                                colors: ['#10b981', '#34d399', '#6ee7b7']
                            });
                            confetti({
                                particleCount: 150,
                                angle: 120,
                                spread: 65,
                                origin: { x: 1 },
                                colors: ['#10b981', '#34d399', '#6ee7b7']
                            });
                        }, 300);
                    }, 500);
                });
            @endif
        </script>
    </body>
</html>

