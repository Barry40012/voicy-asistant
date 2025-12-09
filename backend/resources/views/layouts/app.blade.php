<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 20px rgba(14, 165, 233, 0.3); }
                50% { box-shadow: 0 0 40px rgba(14, 165, 233, 0.6); }
            }
            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            @keyframes scaleIn {
                from {
                    opacity: 0;
                    transform: scale(0.9);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }
            @keyframes rotate {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            @keyframes slideRight {
                from { transform: translateX(-100%); }
                to { transform: translateX(100%); }
            }
            @keyframes shimmer {
                0% { background-position: -1000px 0; }
                100% { background-position: 1000px 0; }
            }
            @keyframes shine {
                0% { transform: translateX(-100%) skewX(-20deg); }
                100% { transform: translateX(200%) skewX(-20deg); }
            }
            @keyframes sparkle {
                0%, 100% { 
                    opacity: 0;
                    transform: scale(0) translateY(0);
                }
                50% { 
                    opacity: 1;
                    transform: scale(1.5) translateY(-10px);
                }
            }
            .animate-sparkle {
                animation: sparkle 2s ease-in-out infinite;
            }
            .star-3d-gold {
                animation: starGlow 2s ease-in-out infinite;
                transform-style: preserve-3d;
            }
            @keyframes starGlow {
                0%, 100% {
                    transform: scale(1) rotateY(0deg) rotateZ(0deg);
                    opacity: 1;
                }
                25% {
                    transform: scale(1.1) rotateY(5deg) rotateZ(5deg);
                    opacity: 0.9;
                }
                50% {
                    transform: scale(1.15) rotateY(10deg) rotateZ(-5deg);
                    opacity: 1;
                }
                75% {
                    transform: scale(1.1) rotateY(-5deg) rotateZ(5deg);
                    opacity: 0.9;
                }
            }
            .animate-shimmer {
                background: linear-gradient(
                    90deg,
                    transparent,
                    rgba(255, 255, 255, 0.4),
                    transparent
                );
                background-size: 1000px 100%;
                animation: shimmer 3s infinite;
            }
            .animate-shine {
                animation: shine 2s ease-in-out infinite;
            }
            .animate-float {
                animation: float 3s ease-in-out infinite;
            }
            .animate-pulse-glow {
                animation: pulse-glow 2s ease-in-out infinite;
            }
            .animate-slide-in {
                animation: slideInUp 0.6s ease-out;
            }
            .animate-scale-in {
                animation: scaleIn 0.5s ease-out;
            }
            .card-hover-effect {
                transition: all 0.3s ease;
            }
            .card-hover-effect:hover {
                transform: translateY(-8px) scale(1.02);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            }
        </style>
    </head>
    <body class="font-sans antialiased" onload="AOS.init({ duration: 1000, once: true, offset: 100 })">
        <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-primary-50 relative overflow-hidden">
            <!-- Animated Background Particles -->
            <div class="fixed inset-0 overflow-hidden pointer-events-none" style="z-index: 0;">
                <canvas id="dashboard-particles" class="absolute inset-0 w-full h-full"></canvas>
            </div>
            <div class="relative z-10">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            </div>
        </div>

        <!-- Session Timeout Warning -->
        @auth
            <x-session-timeout />
        @endauth

        <!-- Cookie Consent Banner -->
        <x-cookie-banner />

        <script>
            // Animated particles background for dashboard
            document.addEventListener('DOMContentLoaded', function() {
                const canvas = document.getElementById('dashboard-particles');
                if (!canvas) return;
                
                const ctx = canvas.getContext('2d');
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                
                const particles = [];
                const particleCount = 20;
                const colors = ['#0ea5e9', '#a855f7', '#10b981', '#3b82f6', '#60a5fa'];
                
                class Particle {
                    constructor() {
                        this.reset();
                        this.y = Math.random() * canvas.height;
                    }
                    
                    reset() {
                        this.x = Math.random() * canvas.width;
                        this.y = -10;
                        this.size = Math.random() * 4 + 2;
                        this.speed = Math.random() * 1.5 + 0.5;
                        this.color = colors[Math.floor(Math.random() * colors.length)];
                        this.opacity = Math.random() * 0.4 + 0.2;
                        this.rotation = Math.random() * 360;
                        this.rotationSpeed = Math.random() * 1 - 0.5;
                    }
                    
                    update() {
                        this.y += this.speed;
                        this.rotation += this.rotationSpeed;
                        this.x += Math.sin(this.y * 0.01) * 0.3;
                        
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
        </script>
    </body>
</html>
