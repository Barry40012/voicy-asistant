<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Voicy Assistant - Automatisez vos messages vocaux WhatsApp</title>
        <meta name="description" content="Transformez vos messages vocaux WhatsApp en réponses automatisées avec l'IA. Gagnez du temps et améliorez votre productivité.">

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
            @keyframes floatAround {
                0% { transform: translate(0, 0) rotate(0deg); }
                25% { transform: translate(100px, -50px) rotate(90deg); }
                50% { transform: translate(-80px, 80px) rotate(180deg); }
                75% { transform: translate(120px, 50px) rotate(270deg); }
                100% { transform: translate(0, 0) rotate(360deg); }
            }
            @keyframes floatAround2 {
                0% { transform: translate(0, 0) rotate(0deg); }
                25% { transform: translate(-120px, 60px) rotate(-90deg); }
                50% { transform: translate(90px, -70px) rotate(-180deg); }
                75% { transform: translate(-100px, -40px) rotate(-270deg); }
                100% { transform: translate(0, 0) rotate(-360deg); }
            }
            @keyframes floatAround3 {
                0% { transform: translate(0, 0) rotate(0deg); }
                33% { transform: translate(150px, 100px) rotate(120deg); }
                66% { transform: translate(-130px, -90px) rotate(240deg); }
                100% { transform: translate(0, 0) rotate(360deg); }
            }
            @keyframes floatAround4 {
                0% { transform: translate(0, 0) rotate(0deg); }
                33% { transform: translate(-140px, 80px) rotate(-120deg); }
                66% { transform: translate(110px, -110px) rotate(-240deg); }
                100% { transform: translate(0, 0) rotate(-360deg); }
            }
            @keyframes floatAround5 {
                0% { transform: translate(0, 0) rotate(0deg) scale(1); }
                20% { transform: translate(80px, -100px) rotate(72deg) scale(1.1); }
                40% { transform: translate(-90px, 70px) rotate(144deg) scale(0.9); }
                60% { transform: translate(120px, 90px) rotate(216deg) scale(1.15); }
                80% { transform: translate(-70px, -80px) rotate(288deg) scale(0.95); }
                100% { transform: translate(0, 0) rotate(360deg) scale(1); }
            }
            @keyframes floatAround6 {
                0% { transform: translate(0, 0) rotate(0deg) scale(1); }
                25% { transform: translate(-150px, 60px) rotate(-90deg) scale(1.2); }
                50% { transform: translate(100px, -120px) rotate(-180deg) scale(0.8); }
                75% { transform: translate(130px, 100px) rotate(-270deg) scale(1.1); }
                100% { transform: translate(0, 0) rotate(-360deg) scale(1); }
            }
            @keyframes floatAround7 {
                0% { transform: translate(0, 0) rotate(0deg) scale(1); }
                30% { transform: translate(110px, 80px) rotate(108deg) scale(1.05); }
                60% { transform: translate(-130px, -90px) rotate(216deg) scale(0.9); }
                100% { transform: translate(0, 0) rotate(360deg) scale(1); }
            }
            @keyframes floatAround8 {
                0% { transform: translate(0, 0) rotate(0deg) scale(1); }
                33% { transform: translate(90px, -110px) rotate(-108deg) scale(1.15); }
                66% { transform: translate(-100px, 120px) rotate(-216deg) scale(0.85); }
                100% { transform: translate(0, 0) rotate(-360deg) scale(1); }
            }
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 20px rgba(14, 165, 233, 0.3); }
                50% { box-shadow: 0 0 40px rgba(14, 165, 233, 0.6); }
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
            .gradient-mask {
                background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(168, 85, 247, 0.1) 100%);
            }
            .cursor-blink {
                animation: blink 1s infinite;
            }
            @keyframes blink {
                0%, 50% { opacity: 1; }
                51%, 100% { opacity: 0; }
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                AOS.init({
                    duration: 1000,
                    once: true,
                    offset: 100,
                    easing: 'ease-out-cubic'
                });

                // Animation de particules en arrière-plan pour la section Hero
                const canvas = document.getElementById('particles-canvas');
                if (canvas) {
                    const ctx = canvas.getContext('2d');
                    canvas.width = window.innerWidth;
                    canvas.height = window.innerHeight;

                    const particles = [];
                    const particleCount = 50;
                    const colors = ['#0ea5e9', '#a855f7', '#10b981', '#3b82f6'];

                    class Particle {
                        constructor() {
                            this.x = Math.random() * canvas.width;
                            this.y = Math.random() * canvas.height;
                            this.size = Math.random() * 3 + 1;
                            this.speedX = Math.random() * 1 - 0.5;
                            this.speedY = Math.random() * 1 - 0.5;
                            this.color = colors[Math.floor(Math.random() * colors.length)];
                            this.opacity = Math.random() * 0.5 + 0.2;
                        }

                        update() {
                            this.x += this.speedX;
                            this.y += this.speedY;

                            if (this.x > canvas.width) this.x = 0;
                            if (this.x < 0) this.x = canvas.width;
                            if (this.y > canvas.height) this.y = 0;
                            if (this.y < 0) this.y = canvas.height;
                        }

                        draw() {
                            ctx.fillStyle = this.color;
                            ctx.globalAlpha = this.opacity;
                            ctx.beginPath();
                            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                            ctx.fill();
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
                }

                // Animation des étoiles au survol
                document.querySelectorAll('.star-rating').forEach(star => {
                    star.addEventListener('mouseenter', function() {
                        this.style.transform = 'scale(1.3)';
                        this.style.transition = 'transform 0.2s ease';
                    });
                    star.addEventListener('mouseleave', function() {
                        this.style.transform = 'scale(1)';
                    });
                });

                // Typewriter animation pour le titre Hero - Animation en boucle infinie
                const typewriterText = document.getElementById('typewriter-text');
                if (typewriterText) {
                    const text = "Ne perdez plus de temps à écouter vos vocaux";
                    let index = 0;
                    let isDeleting = false;
                    let currentText = '';
                    
                    function typeWriter() {
                        if (!isDeleting && index < text.length) {
                            // Écriture
                            currentText += text.charAt(index);
                            typewriterText.textContent = currentText;
                            index++;
                            setTimeout(typeWriter, 100);
                        } else if (!isDeleting && index >= text.length) {
                            // Pause avant de commencer à effacer
                            setTimeout(() => {
                                isDeleting = true;
                                typeWriter();
                            }, 2000); // Pause de 2 secondes
                        } else if (isDeleting && currentText.length > 0) {
                            // Effacement
                            currentText = currentText.slice(0, -1);
                            typewriterText.textContent = currentText;
                            setTimeout(typeWriter, 50); // Plus rapide pour l'effacement
                        } else if (isDeleting && currentText.length === 0) {
                            // Recommencer
                            isDeleting = false;
                            index = 0;
                            setTimeout(typeWriter, 500); // Court délai avant de recommencer
                        }
                    }
                    
                    // Démarrer l'animation après un court délai
                    setTimeout(typeWriter, 500);
                }

                // Confetti animation on success messages
                @if (session('success'))
                    setTimeout(function() {
                        // Confetti avec les couleurs de la plateforme
                        confetti({
                            particleCount: 100,
                            spread: 70,
                            origin: { y: 0.6 },
                            colors: ['#0ea5e9', '#a855f7', '#10b981', '#3b82f6']
                        });
                        
                        // Confetti supplémentaire après un court délai
                        setTimeout(function() {
                            confetti({
                                particleCount: 50,
                                angle: 60,
                                spread: 55,
                                origin: { x: 0 },
                                colors: ['#0ea5e9', '#a855f7', '#10b981']
                            });
                            confetti({
                                particleCount: 50,
                                angle: 120,
                                spread: 55,
                                origin: { x: 1 },
                                colors: ['#0ea5e9', '#a855f7', '#10b981']
                            });
                        }, 250);
                    }, 300);
                @endif

                // Confetti sur soumission réussie du formulaire de commentaire
                const commentForm = document.querySelector('form[action*="comments.store"]');
                if (commentForm) {
                    commentForm.addEventListener('submit', function(e) {
                        // Attendre la réponse du serveur (simulation)
                        setTimeout(function() {
                            if (document.querySelector('.bg-green-50')) {
                                confetti({
                                    particleCount: 150,
                                    spread: 80,
                                    origin: { y: 0.6 },
                                    colors: ['#0ea5e9', '#a855f7', '#10b981', '#3b82f6']
                                });
                            }
                        }, 1000);
                    });
                }

                // Confetti sur soumission réussie de la newsletter
                const newsletterForm = document.querySelector('form[action*="newsletter.subscribe"]');
                if (newsletterForm) {
                    newsletterForm.addEventListener('submit', function(e) {
                        setTimeout(function() {
                            if (document.querySelector('.bg-green-50')) {
                                confetti({
                                    particleCount: 120,
                                    spread: 75,
                                    origin: { y: 0.7 },
                                    colors: ['#0ea5e9', '#a855f7', '#10b981']
                                });
                            }
                        }, 1000);
                    });
                }
            });
        </script>
    </head>
    <body class="font-sans antialiased bg-white" x-data="{ activeTab: 'comments' }" onload="AOS.init({ duration: 1000, once: true, offset: 100 })">
        <!-- Navigation -->
        <nav class="bg-white/95 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 sm:h-20">
                    <div class="flex items-center">
                        <a href="{{ route('welcome') }}" class="flex items-center group">
                            <x-app-logo class="h-8 sm:h-10 transition-transform duration-300 group-hover:scale-110" />
                        </a>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm sm:text-base font-medium transition-colors duration-200">
                            <i class="fas fa-sign-in-alt mr-1 sm:mr-2"></i>
                            <span class="hidden sm:inline">Connexion</span>
                            <span class="sm:hidden">Connexion</span>
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-primary-600 to-primary-700 text-white px-4 sm:px-6 py-2 rounded-lg text-sm sm:text-base font-semibold hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                            <i class="fas fa-rocket mr-1 sm:mr-2"></i>
                            <span class="hidden sm:inline">Commencer gratuitement</span>
                            <span class="sm:hidden">Commencer</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-primary-50 via-white to-secondary-50 py-12 sm:py-16 lg:py-24 xl:py-32 overflow-hidden min-h-[500px] sm:min-h-[600px] lg:min-h-[700px] flex items-center">
            <!-- Animated Background Particles -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <canvas id="particles-canvas" class="absolute inset-0 w-full h-full"></canvas>
                <div class="absolute top-0 left-0 w-full h-full">
                    <div class="absolute top-20 left-10 w-2 h-2 bg-primary-400 rounded-full animate-float opacity-60"></div>
                    <div class="absolute top-40 right-20 w-3 h-3 bg-secondary-400 rounded-full animate-float opacity-40" style="animation-delay: 1s;"></div>
                    <div class="absolute bottom-20 left-1/4 w-2 h-2 bg-green-400 rounded-full animate-float opacity-50" style="animation-delay: 2s;"></div>
                    <div class="absolute bottom-40 right-1/3 w-3 h-3 bg-primary-300 rounded-full animate-float opacity-30" style="animation-delay: 3s;"></div>
                    <div class="absolute top-1/2 left-1/3 w-2 h-2 bg-secondary-300 rounded-full animate-float opacity-40" style="animation-delay: 4s;"></div>
                </div>
                <!-- Animated Social Media Icons - Hidden on mobile, visible on larger screens -->
                <!-- Left side icons -->
                <div class="hidden md:block absolute top-1/4 left-1/4 w-12 h-12 lg:w-16 lg:h-16 opacity-70 hover:opacity-100 transition-opacity z-0" style="animation: floatAround 20s ease-in-out infinite;">
                    <div class="w-full h-full bg-green-500 rounded-full flex items-center justify-center shadow-2xl hover:scale-125 transition-transform border-2 border-green-400">
                        <i class="fab fa-whatsapp text-white text-lg lg:text-2xl"></i>
                    </div>
                </div>
                <div class="hidden md:block absolute bottom-1/4 left-1/3 w-10 h-10 lg:w-14 lg:h-14 opacity-70 hover:opacity-100 transition-opacity z-0" style="animation: floatAround3 22s ease-in-out infinite;">
                    <div class="w-full h-full bg-blue-400 rounded-full flex items-center justify-center shadow-2xl hover:scale-125 transition-transform border-2 border-blue-300">
                        <i class="fab fa-twitter text-white text-base lg:text-xl"></i>
                    </div>
                </div>
                
                <!-- Right side icons -->
                <div class="hidden md:block absolute top-1/3 right-1/4 w-12 h-12 lg:w-16 lg:h-16 opacity-70 hover:opacity-100 transition-opacity z-0" style="animation: floatAround2 25s ease-in-out infinite;">
                    <div class="w-full h-full bg-blue-600 rounded-full flex items-center justify-center shadow-2xl hover:scale-125 transition-transform border-2 border-blue-500">
                        <i class="fab fa-facebook text-white text-lg lg:text-2xl"></i>
                    </div>
                </div>
                <div class="hidden md:block absolute bottom-1/3 right-1/3 w-11 h-11 lg:w-15 lg:h-15 opacity-70 hover:opacity-100 transition-opacity z-0" style="animation: floatAround4 18s ease-in-out infinite;">
                    <div class="w-full h-full bg-purple-600 rounded-full flex items-center justify-center shadow-2xl hover:scale-125 transition-transform border-2 border-purple-500">
                        <i class="fab fa-instagram text-white text-base lg:text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                <div class="text-center">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4 sm:mb-6" data-aos="fade-up">
                        <span id="typewriter-text" class="text-primary-600 block sm:inline"></span>
                        <span class="cursor-blink">|</span>
                    </h1>
                    <p class="text-base sm:text-lg md:text-xl text-gray-600 mb-6 sm:mb-8 max-w-3xl mx-auto px-4" data-aos="fade-up" data-aos-delay="200">
                        Voicy Assistant transcrit, résume et répond automatiquement à vos messages vocaux WhatsApp Business grâce à l'intelligence artificielle.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center px-4" data-aos="fade-up" data-aos-delay="400">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-base sm:text-lg font-semibold rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 w-full sm:w-auto">
                            <i class="fas fa-rocket mr-2"></i>
                            Essayer gratuitement
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                        <a href="#features" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-white text-gray-700 text-base sm:text-lg font-semibold rounded-lg border-2 border-gray-300 hover:border-primary-500 hover:text-primary-600 transition-all duration-300 w-full sm:w-auto">
                            <i class="fas fa-info-circle mr-2"></i>
                            En savoir plus
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-12 sm:py-16 lg:py-20 bg-gradient-to-b from-white via-primary-50/30 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10 sm:mb-12 lg:mb-16" data-aos="fade-up">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                        <i class="fas fa-star text-primary-600 mr-2"></i>
                        Fonctionnalités puissantes
                    </h2>
                    <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-2xl mx-auto px-4">
                        Tout ce dont vous avez besoin pour automatiser vos messages vocaux WhatsApp
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-primary-200 transform transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:-translate-y-2 group" data-aos="fade-up" data-aos-delay="0">
                        <div class="bg-gradient-to-br from-primary-600 to-primary-700 rounded-lg w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center mb-4 sm:mb-6 shadow-lg transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                            <i class="fas fa-microphone-alt text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Transcription automatique</h3>
                        <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                            Vos messages vocaux sont automatiquement transcrits avec une précision de 99% grâce à l'IA Whisper.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-secondary-200 transform transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:-translate-y-2 group" data-aos="fade-up" data-aos-delay="200">
                        <div class="bg-gradient-to-br from-secondary-600 to-secondary-700 rounded-lg w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center mb-4 sm:mb-6 shadow-lg transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                            <i class="fas fa-file-alt text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Résumé intelligent</h3>
                        <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                            Chaque vocal est résumé en 3 lignes et les actions importantes sont automatiquement extraites.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-green-200 transform transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:-translate-y-2 group" data-aos="fade-up" data-aos-delay="400">
                        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-lg w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center mb-4 sm:mb-6 shadow-lg transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                            <i class="fas fa-comments text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Réponses automatiques</h3>
                        <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                            Recevez des réponses professionnelles générées par IA, prêtes à être envoyées ou personnalisées.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it works -->
        <section class="py-12 sm:py-16 lg:py-20 bg-gradient-to-br from-gray-50 via-white to-primary-50/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10 sm:mb-12 lg:mb-16" data-aos="fade-up">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                        <i class="fas fa-cogs text-secondary-600 mr-2"></i>
                        Comment ça fonctionne ?
                    </h2>
                    <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-2xl mx-auto px-4">
                        En 3 étapes simples, automatisez vos messages vocaux
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 lg:gap-12">
                    <!-- Step 1 -->
                    <div class="text-center group" data-aos="fade-up" data-aos-delay="0">
                        <div class="bg-gradient-to-br from-primary-600 to-primary-700 text-white rounded-full w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center text-2xl sm:text-3xl font-bold mx-auto mb-4 sm:mb-6 shadow-lg transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                            <i class="fas fa-plug"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Connectez WhatsApp</h3>
                        <p class="text-sm sm:text-base text-gray-600 px-4">
                            Connectez votre compte WhatsApp Business en quelques clics via Meta Developers.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center group" data-aos="fade-up" data-aos-delay="200">
                        <div class="bg-gradient-to-br from-secondary-600 to-secondary-700 text-white rounded-full w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center text-2xl sm:text-3xl font-bold mx-auto mb-4 sm:mb-6 shadow-lg transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Recevez des vocaux</h3>
                        <p class="text-sm sm:text-base text-gray-600 px-4">
                            Vos messages vocaux sont automatiquement capturés et traités en temps réel.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center group" data-aos="fade-up" data-aos-delay="400">
                        <div class="bg-gradient-to-br from-green-600 to-green-700 text-white rounded-full w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center text-2xl sm:text-3xl font-bold mx-auto mb-4 sm:mb-6 shadow-lg transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Obtenez les réponses</h3>
                        <p class="text-sm sm:text-base text-gray-600 px-4">
                            Consultez les transcriptions, résumés et réponses suggérées dans votre dashboard.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-12 sm:py-16 lg:py-20 bg-gradient-to-b from-white via-secondary-50/30 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10 sm:mb-12 lg:mb-16" data-aos="fade-up">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                        <i class="fas fa-tags text-secondary-600 mr-2"></i>
                        Tarifs simples et transparents
                    </h2>
                    <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-2xl mx-auto px-4">
                        Choisissez le plan qui correspond à vos besoins
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 max-w-5xl mx-auto">
                    @php
                        try {
                            $plans = \App\Models\Plan::where('is_active', true)->get();
                            // Réorganiser l'ordre : Free, Starter, VIP (ou Pro)
                            $orderedPlans = collect();
                            $freePlan = $plans->firstWhere('name', 'Free');
                            $starterPlan = $plans->firstWhere('name', 'Starter');
                            $vipPlan = $plans->firstWhere('name', 'VIP') ?? $plans->firstWhere('name', 'Pro');
                            
                            if ($freePlan) $orderedPlans->push($freePlan);
                            if ($starterPlan) $orderedPlans->push($starterPlan);
                            if ($vipPlan) $orderedPlans->push($vipPlan);
                            
                            // Ajouter les autres plans s'il y en a
                            $otherPlans = $plans->reject(function($plan) {
                                return in_array($plan->name, ['Free', 'Starter', 'VIP', 'Pro']);
                            });
                            $orderedPlans = $orderedPlans->merge($otherPlans);
                            
                            $plans = $orderedPlans->isEmpty() ? collect([
                                (object)['name' => 'Free', 'price_monthly' => 0, 'description' => 'Plan gratuit avec limitations', 'allowed_audio_per_month' => 50, 'allowed_audio_per_minute_length' => 2],
                                (object)['name' => 'Starter', 'price_monthly' => 9, 'description' => 'Parfait pour les petites entreprises', 'allowed_audio_per_month' => 500, 'allowed_audio_per_minute_length' => 5],
                                (object)['name' => 'VIP', 'price_monthly' => 29, 'description' => 'Pour les entreprises avec volume élevé', 'allowed_audio_per_month' => 5000, 'allowed_audio_per_minute_length' => 10],
                            ]) : $orderedPlans;
                        } catch (\Exception $e) {
                            $plans = collect([
                                (object)['name' => 'Free', 'price_monthly' => 0, 'description' => 'Plan gratuit avec limitations', 'allowed_audio_per_month' => 50, 'allowed_audio_per_minute_length' => 2],
                                (object)['name' => 'Starter', 'price_monthly' => 9, 'description' => 'Parfait pour les petites entreprises', 'allowed_audio_per_month' => 500, 'allowed_audio_per_minute_length' => 5],
                                (object)['name' => 'VIP', 'price_monthly' => 29, 'description' => 'Pour les entreprises avec volume élevé', 'allowed_audio_per_month' => 5000, 'allowed_audio_per_minute_length' => 10],
                            ]);
                        }
                    @endphp
                    @foreach($plans as $index => $plan)
                        <div class="bg-white border-2 rounded-xl sm:rounded-2xl p-6 sm:p-8 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 {{ $plan->name === 'Starter' ? 'border-primary-500 shadow-xl scale-105 sm:scale-110' : 'border-gray-200 hover:border-primary-300' }}" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                            @if($plan->name === 'Starter')
                                <div class="bg-gradient-to-r from-primary-600 to-primary-700 text-white text-xs sm:text-sm font-semibold px-3 py-1.5 rounded-full inline-block mb-4 shadow-md">
                                    <i class="fas fa-star mr-1"></i>
                                    POPULAIRE
                                </div>
                            @endif
                            <div class="flex items-center mb-3">
                                <i class="fas fa-crown text-2xl sm:text-3xl {{ $plan->name === 'Starter' ? 'text-primary-600' : 'text-gray-400' }} mr-2"></i>
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                            </div>
                            <div class="mb-4 sm:mb-6">
                                <span class="text-3xl sm:text-4xl font-bold text-gray-900">{{ number_format($plan->price_monthly, 0) }}€</span>
                                <span class="text-gray-600 text-sm sm:text-base">/mois</span>
                            </div>
                            <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">{{ $plan->description }}</p>
                            <ul class="space-y-2 sm:space-y-3 mb-6 sm:mb-8">
                                <li class="flex items-center text-sm sm:text-base">
                                    <i class="fas fa-check-circle text-green-500 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                                    <span class="text-gray-700">{{ number_format($plan->allowed_audio_per_month) }} audios/mois</span>
                                </li>
                                <li class="flex items-center text-sm sm:text-base">
                                    <i class="fas fa-check-circle text-green-500 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                                    <span class="text-gray-700">Jusqu'à {{ $plan->allowed_audio_per_minute_length }} min/audio</span>
                                </li>
                                <li class="flex items-center text-sm sm:text-base">
                                    <i class="fas fa-check-circle text-green-500 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                                    <span class="text-gray-700">Transcription automatique</span>
                                </li>
                                <li class="flex items-center text-sm sm:text-base">
                                    <i class="fas fa-check-circle text-green-500 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                                    <span class="text-gray-700">Résumé intelligent</span>
                                </li>
                            </ul>
                            <a href="{{ route('register') }}" class="block w-full text-center px-4 sm:px-6 py-2.5 sm:py-3 {{ $plan->name === 'Starter' ? 'bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white shadow-lg' : 'bg-gray-100 hover:bg-gray-200 text-gray-900' }} rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-arrow-right mr-2"></i>
                                Commencer
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Comments Section -->
        <section class="py-12 sm:py-16 lg:py-20 bg-gradient-to-b from-white via-primary-50/20 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10 sm:mb-12 lg:mb-16" data-aos="fade-up">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                        <i class="fas fa-comments text-primary-600 mr-2"></i>
                        Témoignages
                    </h2>
                    <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-2xl mx-auto px-4">
                        Découvrez ce que nos utilisateurs pensent de Voicy Assistant et partagez votre propre expérience
                    </p>
                </div>

                <!-- Success Message -->
                @if (session('success') && str_contains(session('success'), 'commentaire'))
                    <div class="mb-8 max-w-2xl mx-auto bg-green-50 border border-green-200 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                    <!-- Comment Form -->
                    <div class="order-2 lg:order-1" x-data="{ submitted: false }">
                        <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-8 border border-primary-200">
                            <div class="flex items-center mb-6">
                                <div class="bg-primary-600 rounded-lg w-12 h-12 flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Partagez votre avis</h3>
                            </div>
                            
                            <form method="POST" action="{{ route('comments.store') }}" class="space-y-6" @submit="submitted = true">
                                @csrf
                                <div>
                                    <label for="comment_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nom complet <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="comment_name" 
                                        name="name" 
                                        value="{{ old('name', auth()->user()->name ?? '') }}"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
                                        placeholder="Votre nom complet"
                                    >
                                </div>
                                <div>
                                    <label for="comment_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Adresse email <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="email" 
                                        id="comment_email" 
                                        name="email" 
                                        value="{{ old('email', auth()->user()->email ?? '') }}"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
                                        placeholder="votre@email.com"
                                    >
                                </div>
                                <div>
                                    <label for="comment_content" class="block text-sm font-medium text-gray-700 mb-2">
                                        Votre message <span class="text-red-500">*</span>
                                    </label>
                                    <textarea 
                                        id="comment_content" 
                                        name="content" 
                                        rows="5"
                                        required
                                        minlength="10"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none transition"
                                        placeholder="Partagez votre expérience avec Voicy Assistant (minimum 10 caractères)..."
                                    >{{ old('content') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-2">Votre commentaire sera publié après modération</p>
                                </div>
                                <div>
                                    <button 
                                        type="submit"
                                        :disabled="submitted"
                                        class="w-full px-6 py-3 bg-primary-600 text-white text-lg font-semibold rounded-lg hover:bg-primary-700 transition shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <span class="flex items-center justify-center" x-show="!submitted">
                                            Publier mon commentaire
                                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </span>
                                        <span class="flex items-center justify-center" x-show="submitted">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Publication en cours...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Approved Comments -->
                    <div class="order-1 lg:order-2">
                        @php
                            $approvedComments = \App\Models\Comment::where('status', 'approved')
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp

                        @if($approvedComments->count() > 0)
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">Commentaires récents</h3>
                                
                                @foreach($approvedComments as $index => $comment)
                                    @php
                                        // Calculer un score basé sur la longueur du commentaire (pour les étoiles dynamiques)
                                        $contentLength = strlen($comment->content);
                                        $score = min(5, max(3, ceil($contentLength / 30))); // Score entre 3 et 5
                                        
                                        // Générer un seed unique basé sur l'email ou le nom pour l'avatar
                                        $avatarSeed = md5($comment->email . $comment->id);
                                        $avatarUrl = "https://api.dicebear.com/7.x/avataaars/svg?seed={$avatarSeed}&backgroundColor=b6e3f4,c0aede,ffd5dc,ffdfbf";
                                    @endphp
                                    <div class="bg-gradient-to-br from-primary-50 via-white to-secondary-50 border-2 border-primary-200 rounded-xl p-6 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 hover:scale-[1.02] relative overflow-hidden group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                                        <!-- Animated background effect -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-primary-100/0 via-primary-100/20 to-primary-100/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 transform -translate-x-full group-hover:translate-x-full"></div>
                                        <div class="flex items-start space-x-3 mb-4 relative z-10">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full overflow-hidden shadow-md border-2 border-primary-300 group-hover:border-primary-500 transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                                                    <img src="{{ $avatarUrl }}" alt="{{ $comment->name }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'32\' height=\'32\'%3E%3Ccircle cx=\'16\' cy=\'16\' r=\'16\' fill=\'%230ea5e9\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'white\' font-size=\'14\' font-weight=\'bold\'%3E{{ strtoupper(substr($comment->name, 0, 1)) }}%3C/text%3E%3C/svg%3E';">
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0 relative z-10">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h4 class="font-bold text-gray-900 text-lg group-hover:text-primary-600 transition-colors duration-300">{{ $comment->name }}</h4>
                                                    <div class="flex items-center space-x-0.5" data-rating="{{ $score }}">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $score)
                                                                @if($i <= 3)
                                                                    <svg class="w-4 h-4 text-green-500 star-rating" data-star="{{ $i }}" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                    </svg>
                                                                @elseif($i <= 4)
                                                                    <svg class="w-4 h-4 text-orange-500 star-rating" data-star="{{ $i }}" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                    </svg>
                                                                @else
                                                                    <svg class="w-4 h-4 text-blue-500 star-rating" data-star="{{ $i }}" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                    </svg>
                                                                @endif
                                                            @else
                                                                <svg class="w-4 h-4 text-gray-300 star-rating" data-star="{{ $i }}" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                </svg>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                <p class="text-xs text-gray-500 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $comment->created_at->format('d/m/Y à H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="pl-11 relative z-10">
                                            <p class="text-gray-700 leading-relaxed text-base mb-3 group-hover:text-gray-900 transition-colors duration-300">{{ $comment->content }}</p>
                                            <div class="flex items-center text-primary-600 text-sm">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-medium">Commentaire vérifié</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-xl p-12 text-center border border-gray-200">
                                <div class="bg-primary-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Soyez le premier !</h3>
                                <p class="text-gray-600">Partagez votre expérience avec Voicy Assistant</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="py-12 sm:py-16 lg:py-20 bg-gradient-to-br from-gray-50 via-white to-secondary-50/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10 sm:mb-12 lg:mb-16" data-aos="fade-up">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                        <i class="fas fa-envelope text-primary-600 mr-2"></i>
                        Contactez-nous
                    </h2>
                    <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-2xl mx-auto px-4">
                        Une question ? Une suggestion ? Notre équipe est là pour vous aider
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-10 sm:mb-12 lg:mb-16">
                    <!-- Email Card -->
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-primary-200 text-center transform transition-all duration-300 hover:shadow-xl hover:-translate-y-2" data-aos="fade-up" data-aos-delay="0">
                        <div class="bg-gradient-to-br from-primary-600 to-primary-700 rounded-lg w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class="fas fa-envelope text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Email</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">{{ \App\Models\Setting::get('contact_email', 'info.voicyassistant@gmail.com') }}</p>
                        <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'info.voicyassistant@gmail.com') }}" class="text-primary-600 font-semibold hover:text-primary-700 transition-colors inline-flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Envoyer un email
                        </a>
                    </div>

                    <!-- Response Time Card -->
                    <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-secondary-200 text-center transform transition-all duration-300 hover:shadow-xl hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
                        <div class="bg-gradient-to-br from-secondary-600 to-secondary-700 rounded-lg w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class="fas fa-clock text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Réponse rapide</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">Sous 24-48h</p>
                        <p class="text-xs sm:text-sm text-gray-500">Nous nous engageons à répondre rapidement</p>
                    </div>

                    <!-- Support Card -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-green-200 text-center transform transition-all duration-300 hover:shadow-xl hover:-translate-y-2 sm:col-span-2 lg:col-span-1" data-aos="fade-up" data-aos-delay="400">
                        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-lg w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class="fas fa-headset text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Support 7j/7</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">Disponible en permanence</p>
                        <p class="text-xs sm:text-sm text-gray-500">Notre équipe est disponible pour vous aider</p>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="text-center mt-8 sm:mt-12" data-aos="fade-up">
                    <a 
                        href="{{ route('contact.show') }}" 
                        class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-base sm:text-lg font-semibold rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>
                        <span class="hidden sm:inline">Accéder au formulaire de contact</span>
                        <span class="sm:hidden">Formulaire de contact</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="py-12 sm:py-16 lg:py-20 bg-gradient-to-br from-primary-50 via-white to-secondary-50/30">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Success Message -->
                @if (session('success') && request()->routeIs('welcome') && !str_contains(session('success'), 'commentaire'))
                    <div class="mb-6 bg-green-50 border border-green-200 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Info Message -->
                @if (session('info') && request()->routeIs('welcome'))
                    <div class="mb-6 bg-primary-50 border border-primary-200 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-primary-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm font-medium text-primary-800">{{ session('info') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Error Message -->
                @if ($errors->has('email') && request()->routeIs('welcome'))
                    <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm font-medium text-red-800">{{ $errors->first('email') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Main Content -->
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 lg:p-10 border border-primary-200 shadow-lg" x-data="{ submitted: false }" data-aos="fade-up">
                    <!-- Header -->
                    <div class="text-center mb-6 sm:mb-8">
                        <div class="bg-gradient-to-br from-primary-600 to-primary-700 rounded-lg w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class="fas fa-envelope-open-text text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                            Restez informé
                        </h2>
                        <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-2xl mx-auto px-4">
                            Recevez nos actualités et conseils exclusifs directement dans votre boîte mail
                        </p>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('newsletter.subscribe') }}" class="space-y-6" @submit="submitted = true">
                        @csrf
                        <input type="hidden" name="source" value="homepage">
                        
                        <div>
                            <label for="newsletter_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse email <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <input 
                                    type="email" 
                                    id="newsletter_email"
                                    name="email" 
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="Entrez votre adresse email"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
                                >
                                <button 
                                    type="submit"
                                    :disabled="submitted"
                                    class="px-8 py-3 bg-primary-600 text-white text-lg font-semibold rounded-lg hover:bg-primary-700 transition shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap"
                                >
                                    <span class="flex items-center justify-center" x-show="!submitted">
                                        S'abonner
                                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </span>
                                    <span class="flex items-center justify-center" x-show="submitted">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Abonnement...
                                    </span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-center pt-2">
                            <p class="text-xs text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Nous respectons votre vie privée. Désinscription à tout moment.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-12 sm:py-16 lg:py-20 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 relative overflow-hidden">
            <!-- Animated background elements -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full animate-float"></div>
                <div class="absolute bottom-10 right-10 w-24 h-24 bg-white rounded-full animate-float" style="animation-delay: 1s;"></div>
                <div class="absolute top-1/2 left-1/2 w-20 h-20 bg-white rounded-full animate-float" style="animation-delay: 2s;"></div>
            </div>
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-3 sm:mb-4" data-aos="fade-up">
                    <i class="fas fa-rocket mr-2"></i>
                    Prêt à gagner du temps ?
                </h2>
                <p class="text-base sm:text-lg lg:text-xl text-primary-100 mb-6 sm:mb-8 px-4" data-aos="fade-up" data-aos-delay="100">
                    Rejoignez des centaines de professionnels qui automatisent déjà leurs messages vocaux
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-white text-primary-600 text-base sm:text-lg font-semibold rounded-lg hover:bg-gray-50 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-rocket mr-2"></i>
                    Commencer gratuitement
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 py-8 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                    <!-- Logo et description -->
                    <div class="min-w-0">
                        <div class="mb-4">
                            <h3 class="text-white text-lg font-bold">Voicy Assistant</h3>
                        </div>
                        <p class="text-sm text-gray-400">
                            Automatisez vos messages vocaux WhatsApp avec l'intelligence artificielle.
                        </p>
                    </div>
                    
                    <!-- Produit -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Produit</h4>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a href="#features" class="text-gray-400 hover:text-white transition-colors duration-200">Fonctionnalités</a>
                            </li>
                            <li>
                                <a href="#pricing" class="text-gray-400 hover:text-white transition-colors duration-200">Tarifs</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Support</h4>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Documentation</a>
                            </li>
                            <li>
                                <a href="{{ route('contact.show') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Contact</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Légal -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Légal</h4>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">CGU</a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Confidentialité</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                    <p>&copy; {{ date('Y') }} Voicy Assistant. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
