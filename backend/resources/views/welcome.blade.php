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
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ route('welcome') }}" class="flex items-center">
                            <x-app-logo class="h-8 sm:h-10" />
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
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

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-primary-50 via-white to-secondary-50 py-20 lg:py-32 overflow-hidden">
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
                <!-- Animated Social Media Icons - Moving around the entire section -->
                <!-- Left side icons -->
                <div class="absolute top-1/4 left-1/4 w-16 h-16 opacity-70 hover:opacity-100 transition-opacity z-0" style="animation: floatAround 20s ease-in-out infinite;">
                    <div class="w-full h-full bg-green-500 rounded-full flex items-center justify-center shadow-2xl hover:scale-125 transition-transform border-2 border-green-400">
                        <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute bottom-1/4 left-1/3 w-14 h-14 opacity-70 hover:opacity-100 transition-opacity z-0" style="animation: floatAround3 22s ease-in-out infinite;">
                    <div class="w-full h-full bg-blue-400 rounded-full flex items-center justify-center shadow-2xl hover:scale-125 transition-transform border-2 border-blue-300">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.9 4.9 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Right side icons -->
                <div class="absolute top-1/3 right-1/4 w-16 h-16 opacity-70 hover:opacity-100 transition-opacity z-0" style="animation: floatAround2 25s ease-in-out infinite;">
                    <div class="w-full h-full bg-blue-600 rounded-full flex items-center justify-center shadow-2xl hover:scale-125 transition-transform border-2 border-blue-500">
                        <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute bottom-1/3 right-1/3 w-15 h-15 opacity-70 hover:opacity-100 transition-opacity z-0" style="animation: floatAround4 18s ease-in-out infinite;">
                    <div class="w-full h-full bg-purple-600 rounded-full flex items-center justify-center shadow-2xl hover:scale-125 transition-transform border-2 border-purple-500">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.057-1.274-.07-1.649-.07-4.844 0-3.196.016-3.586.074-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.44 1.44-1.44.793-.001 1.44.645 1.44 1.44z"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Center/Middle icons (behind the text) -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-14 h-14 opacity-50 hover:opacity-100 transition-opacity z-0" style="animation: floatAround5 24s ease-in-out infinite;">
                    <div class="w-full h-full bg-green-400 rounded-full flex items-center justify-center shadow-2xl hover:scale-125 transition-transform border-2 border-green-300">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute top-2/5 left-2/5 w-13 h-13 opacity-50 hover:opacity-100 transition-opacity z-0" style="animation: floatAround6 21s ease-in-out infinite;">
                    <div class="w-full h-full bg-blue-500 rounded-full flex items-center justify-center shadow-2xl hover:scale-125 transition-transform border-2 border-blue-400">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute bottom-2/5 right-2/5 w-14 h-14 opacity-50 hover:opacity-100 transition-opacity z-0" style="animation: floatAround7 23s ease-in-out infinite;">
                    <div class="w-full h-full bg-purple-500 rounded-full flex items-center justify-center shadow-2xl hover:scale-125 transition-transform border-2 border-purple-400">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.057-1.274-.07-1.649-.07-4.844 0-3.196.016-3.586.074-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.44 1.44-1.44.793-.001 1.44.645 1.44 1.44z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute top-3/5 right-3/5 w-13 h-13 opacity-50 hover:opacity-100 transition-opacity z-0" style="animation: floatAround8 19s ease-in-out infinite;">
                    <div class="w-full h-full bg-green-300 rounded-full flex items-center justify-center shadow-2xl hover:scale-125 transition-transform border-2 border-green-200">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.9 4.9 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                        <span id="typewriter-text" class="text-primary-600"></span>
                        <span class="cursor-blink">|</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                        Voicy Assistant transcrit, résume et répond automatiquement à vos messages vocaux WhatsApp Business grâce à l'intelligence artificielle.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-primary-600 text-white text-lg font-semibold rounded-lg hover:bg-primary-700 transition shadow-lg hover:shadow-xl">
                            Essayer gratuitement
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="#features" class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 text-lg font-semibold rounded-lg border-2 border-gray-300 hover:border-primary-500 hover:text-primary-600 transition">
                            En savoir plus
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Fonctionnalités puissantes
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Tout ce dont vous avez besoin pour automatiser vos messages vocaux WhatsApp
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-8 border border-primary-200">
                        <div class="bg-primary-600 rounded-lg w-12 h-12 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Transcription automatique</h3>
                        <p class="text-gray-600">
                            Vos messages vocaux sont automatiquement transcrits avec une précision de 99% grâce à l'IA Whisper.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 rounded-xl p-8 border border-secondary-200">
                        <div class="bg-secondary-600 rounded-lg w-12 h-12 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Résumé intelligent</h3>
                        <p class="text-gray-600">
                            Chaque vocal est résumé en 3 lignes et les actions importantes sont automatiquement extraites.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-8 border border-green-200">
                        <div class="bg-green-600 rounded-lg w-12 h-12 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Réponses automatiques</h3>
                        <p class="text-gray-600">
                            Recevez des réponses professionnelles générées par IA, prêtes à être envoyées ou personnalisées.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it works -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Comment ça fonctionne ?
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        En 3 étapes simples, automatisez vos messages vocaux
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="bg-primary-600 text-white rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            1
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Connectez WhatsApp</h3>
                        <p class="text-gray-600">
                            Connectez votre compte WhatsApp Business en quelques clics via Meta Developers.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="bg-secondary-600 text-white rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            2
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Recevez des vocaux</h3>
                        <p class="text-gray-600">
                            Vos messages vocaux sont automatiquement capturés et traités en temps réel.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="bg-green-600 text-white rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            3
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Obtenez les réponses</h3>
                        <p class="text-gray-600">
                            Consultez les transcriptions, résumés et réponses suggérées dans votre dashboard.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Tarifs simples et transparents
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Choisissez le plan qui correspond à vos besoins
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
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
                    @foreach($plans as $plan)
                        <div class="bg-white border-2 rounded-xl p-8 {{ $plan->name === 'Starter' ? 'border-primary-500 shadow-lg scale-105' : 'border-gray-200' }}">
                            @if($plan->name === 'Starter')
                                <div class="bg-primary-600 text-white text-xs font-semibold px-3 py-1 rounded-full inline-block mb-4">
                                    POPULAIRE
                                </div>
                            @endif
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                            <div class="mb-4">
                                <span class="text-4xl font-bold text-gray-900">{{ number_format($plan->price_monthly, 0) }}€</span>
                                <span class="text-gray-600">/mois</span>
                            </div>
                            <p class="text-gray-600 mb-6">{{ $plan->description }}</p>
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ number_format($plan->allowed_audio_per_month) }} audios/mois</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Jusqu'à {{ $plan->allowed_audio_per_minute_length }} min/audio</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Transcription automatique</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Résumé intelligent</span>
                                </li>
                            </ul>
                            <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 {{ $plan->name === 'Starter' ? 'bg-primary-600 hover:bg-primary-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-900' }} rounded-lg font-semibold transition">
                                Commencer
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Comments Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Témoignages
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
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

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                        Contactez-nous
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Une question ? Une suggestion ? Notre équipe est là pour vous aider
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <!-- Email Card -->
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-8 border border-primary-200 text-center">
                        <div class="bg-primary-600 rounded-lg w-12 h-12 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Email</h3>
                        <p class="text-gray-600 mb-4">support@voicyassistant.com</p>
                        <a href="mailto:support@voicyassistant.com" class="text-primary-600 font-semibold hover:text-primary-700 transition-colors">
                            Envoyer un email
                        </a>
                    </div>

                    <!-- Response Time Card -->
                    <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 rounded-xl p-8 border border-secondary-200 text-center">
                        <div class="bg-secondary-600 rounded-lg w-12 h-12 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Réponse rapide</h3>
                        <p class="text-gray-600 mb-4">Sous 24-48h</p>
                        <p class="text-gray-500 text-sm">Nous nous engageons à répondre rapidement</p>
                    </div>

                    <!-- Support Card -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-8 border border-green-200 text-center">
                        <div class="bg-green-600 rounded-lg w-12 h-12 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Support 7j/7</h3>
                        <p class="text-gray-600 mb-4">Disponible en permanence</p>
                        <p class="text-gray-500 text-sm">Notre équipe est disponible pour vous aider</p>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="text-center mt-12">
                    <a 
                        href="{{ route('contact.show') }}" 
                        class="inline-flex items-center justify-center px-8 py-4 bg-primary-600 text-white text-lg font-semibold rounded-lg hover:bg-primary-700 transition shadow-lg hover:shadow-xl"
                    >
                        Accéder au formulaire de contact
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="py-20 bg-white">
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
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-8 border border-primary-200" x-data="{ submitted: false }">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="bg-primary-600 rounded-lg w-12 h-12 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                            Restez informé
                        </h2>
                        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
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
        <section class="py-20 bg-gradient-to-br from-primary-600 to-primary-700">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                    Prêt à gagner du temps ?
                </h2>
                <p class="text-xl text-primary-100 mb-8">
                    Rejoignez des centaines de professionnels qui automatisent déjà leurs messages vocaux
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary-600 text-lg font-semibold rounded-lg hover:bg-gray-50 transition shadow-lg hover:shadow-xl">
                    Commencer gratuitement
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
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
