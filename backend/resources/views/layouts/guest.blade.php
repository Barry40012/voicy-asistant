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
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/react-app.jsx'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- Three.js for 3D Animation -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
        
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
                color: #0ea5e9;
            }
            .field-container:focus-within .field-label {
                animation: labelFloat 1s ease-in-out infinite;
                color: #0ea5e9;
            }
            .field-input {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
            }
            .field-input:focus {
                transform: scale(1.02);
                box-shadow: 0 10px 30px rgba(14, 165, 233, 0.2), 0 0 0 4px rgba(14, 165, 233, 0.1);
            }
            .field-input:hover:not(:focus) {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(14, 165, 233, 0.15);
            }
            .field-icon {
                transition: all 0.3s ease;
            }
            .field-label {
                transition: all 0.3s ease;
            }
            .animate-shimmer {
                animation: shimmer 3s infinite;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-primary-50 via-white to-secondary-50 relative">
        <!-- 3D Character Animation Background -->
        <div id="login-3d-container" class="fixed inset-0 w-full h-full pointer-events-none" style="z-index: 0; opacity: 0.7;"></div>
        
        <!-- Animated particles background -->
        <div class="fixed inset-0 w-full h-full pointer-events-none overflow-hidden" style="z-index: 0;">
            <canvas id="particles-canvas" class="absolute inset-0 w-full h-full"></canvas>
        </div>
        <!-- Navigation -->
        <nav class="bg-white/95 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 sm:h-20 min-w-0">
                    <div class="flex items-center min-w-0 flex-shrink">
                        <a href="{{ route('welcome') }}" class="flex-shrink-0 min-w-0 group">
                            <x-app-logo class="h-8 sm:h-10 transition-transform duration-300 group-hover:scale-110" />
                        </a>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
                        @if (Route::currentRouteName() === 'login')
                            <span class="hidden sm:inline text-sm text-gray-600">Pas encore de compte ?</span>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 sm:px-6 py-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold text-sm sm:text-base rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 whitespace-nowrap">
                                <i class="fas fa-user-plus mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">S'inscrire</span>
                                <span class="sm:hidden">Inscription</span>
                            </a>
                        @else
                            <span class="hidden sm:inline text-sm text-gray-600">Déjà un compte ?</span>
                            <a href="{{ route('login') }}" class="inline-flex items-center px-4 sm:px-6 py-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold text-sm sm:text-base rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 whitespace-nowrap">
                                <i class="fas fa-sign-in-alt mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Se connecter</span>
                                <span class="sm:hidden">Connexion</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 sm:py-16 sm:px-0 relative z-10">
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
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl sm:rounded-3xl shadow-2xl border-2 border-primary-200/50 overflow-hidden relative" data-aos="zoom-in" data-aos-duration="800">
                    <!-- Decorative gradient overlay -->
                    <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-primary-500 via-secondary-500 to-primary-500"></div>
                    <div class="px-6 sm:px-8 md:px-10 py-6 sm:py-8 relative z-10">
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

        <script>
            // 3D Character Animation for Login Page
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.getElementById('login-3d-container');
                if (!container) return;

                // Scene setup
                const scene = new THREE.Scene();
                const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
                const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
                renderer.setSize(window.innerWidth, window.innerHeight);
                renderer.setPixelRatio(window.devicePixelRatio);
                container.appendChild(renderer.domElement);

                // Lighting
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
                scene.add(ambientLight);
                const directionalLight = new THREE.DirectionalLight(0x0ea5e9, 0.8);
                directionalLight.position.set(5, 5, 5);
                scene.add(directionalLight);
                const pointLight = new THREE.PointLight(0xa855f7, 0.5);
                pointLight.position.set(-5, 3, 5);
                scene.add(pointLight);

                // Create stylized 3D character (geometric shapes representing a person)
                const characterGroup = new THREE.Group();

                // Head (sphere)
                const headGeometry = new THREE.SphereGeometry(0.4, 16, 16);
                const headMaterial = new THREE.MeshPhongMaterial({ 
                    color: 0xffd5dc,
                    shininess: 100
                });
                const head = new THREE.Mesh(headGeometry, headMaterial);
                head.position.y = 1.2;
                characterGroup.add(head);

                // Body (cylinder)
                const bodyGeometry = new THREE.CylinderGeometry(0.3, 0.35, 0.8, 16);
                const bodyMaterial = new THREE.MeshPhongMaterial({ 
                    color: 0x0ea5e9,
                    shininess: 100
                });
                const body = new THREE.Mesh(bodyGeometry, bodyMaterial);
                body.position.y = 0.5;
                characterGroup.add(body);

                // Arms
                const armGeometry = new THREE.CylinderGeometry(0.08, 0.1, 0.6, 8);
                const armMaterial = new THREE.MeshPhongMaterial({ color: 0xffd5dc });
                
                const leftArm = new THREE.Mesh(armGeometry, armMaterial);
                leftArm.position.set(-0.5, 0.6, 0);
                leftArm.rotation.z = 0.3;
                characterGroup.add(leftArm);

                const rightArm = new THREE.Mesh(armGeometry, armMaterial);
                rightArm.position.set(0.5, 0.6, 0);
                rightArm.rotation.z = -0.3;
                characterGroup.add(rightArm);

                // Legs
                const legGeometry = new THREE.CylinderGeometry(0.1, 0.12, 0.7, 8);
                const legMaterial = new THREE.MeshPhongMaterial({ color: 0x3b82f6 });
                
                const leftLeg = new THREE.Mesh(legGeometry, legMaterial);
                leftLeg.position.set(-0.2, -0.5, 0);
                characterGroup.add(leftLeg);

                const rightLeg = new THREE.Mesh(legGeometry, legMaterial);
                rightLeg.position.set(0.2, -0.5, 0);
                characterGroup.add(rightLeg);

                // Device/Phone (representing connection)
                const deviceGeometry = new THREE.BoxGeometry(0.15, 0.25, 0.05);
                const deviceMaterial = new THREE.MeshPhongMaterial({ 
                    color: 0x1f2937,
                    emissive: 0x0ea5e9,
                    emissiveIntensity: 0.3
                });
                const device = new THREE.Mesh(deviceGeometry, deviceMaterial);
                device.position.set(0.6, 0.7, 0.1);
                device.rotation.z = -0.5;
                characterGroup.add(device);

                // Connection lines (animated)
                const lineMaterial = new THREE.LineBasicMaterial({ 
                    color: 0x10b981,
                    transparent: true,
                    opacity: 0.6
                });
                
                for (let i = 0; i < 5; i++) {
                    const points = [];
                    points.push(new THREE.Vector3(0.6, 0.7, 0.1));
                    points.push(new THREE.Vector3(2 + i * 0.3, 1.5 + Math.sin(i) * 0.3, 0));
                    const lineGeometry = new THREE.BufferGeometry().setFromPoints(points);
                    const line = new THREE.Line(lineGeometry, lineMaterial);
                    characterGroup.add(line);
                }

                scene.add(characterGroup);

                // Position camera - closer and better angle
                camera.position.set(2.5, 1.8, 3.5);
                camera.lookAt(0, 0.8, 0);

                // Add floating particles around character
                const particles = [];
                const particleGeometry = new THREE.SphereGeometry(0.02, 8, 8);
                const particleMaterial = new THREE.MeshBasicMaterial({ 
                    color: 0x0ea5e9,
                    transparent: true,
                    opacity: 0.6
                });
                
                for (let i = 0; i < 20; i++) {
                    const particle = new THREE.Mesh(particleGeometry, particleMaterial.clone());
                    particle.position.set(
                        (Math.random() - 0.5) * 4,
                        (Math.random() - 0.5) * 3 + 1,
                        (Math.random() - 0.5) * 4
                    );
                    particles.push(particle);
                    scene.add(particle);
                }

                // Animation loop
                let time = 0;
                function animate() {
                    requestAnimationFrame(animate);
                    time += 0.015;

                    // Character animation (more dynamic floating and rotation)
                    characterGroup.rotation.y = Math.sin(time * 0.3) * 0.2;
                    characterGroup.position.y = Math.sin(time * 0.8) * 0.15;
                    characterGroup.position.x = Math.cos(time * 0.5) * 0.1;
                    head.rotation.y = Math.sin(time * 1.5) * 0.15;
                    head.rotation.x = Math.sin(time * 1.2) * 0.1;
                    
                    // Arms animation (typing/connecting gesture - more pronounced)
                    leftArm.rotation.z = 0.3 + Math.sin(time * 4) * 0.15;
                    rightArm.rotation.z = -0.3 - Math.sin(time * 4) * 0.15;
                    leftArm.rotation.x = Math.sin(time * 2) * 0.1;
                    rightArm.rotation.x = -Math.sin(time * 2) * 0.1;
                    
                    // Body slight rotation
                    body.rotation.z = Math.sin(time * 0.8) * 0.05;
                    
                    // Legs walking animation
                    leftLeg.rotation.x = Math.sin(time * 3) * 0.2;
                    rightLeg.rotation.x = -Math.sin(time * 3) * 0.2;
                    
                    // Device glow animation (more visible)
                    device.material.emissiveIntensity = 0.4 + Math.sin(time * 5) * 0.3;
                    device.rotation.z = -0.5 + Math.sin(time * 2) * 0.1;
                    device.position.y = 0.7 + Math.sin(time * 3) * 0.05;

                    // Connection lines animation (more dynamic)
                    characterGroup.children.forEach((child, index) => {
                        if (child instanceof THREE.Line) {
                            child.material.opacity = 0.5 + Math.sin(time * 3 + index) * 0.4;
                            // Animate line points
                            if (child.geometry.attributes && child.geometry.attributes.position) {
                                const positions = child.geometry.attributes.position.array;
                                if (positions.length >= 6) {
                                    positions[3] = 2 + index * 0.3 + Math.sin(time * 2 + index) * 0.2;
                                    positions[4] = 1.5 + Math.sin(index) * 0.3 + Math.cos(time * 2 + index) * 0.2;
                                    child.geometry.attributes.position.needsUpdate = true;
                                }
                            }
                        }
                    });
                    
                    // Particles animation
                    particles.forEach((particle, index) => {
                        particle.position.y += Math.sin(time * 2 + index) * 0.01;
                        particle.position.x += Math.cos(time * 1.5 + index) * 0.01;
                        particle.rotation.y += 0.02;
                        particle.material.opacity = 0.4 + Math.sin(time * 3 + index) * 0.3;
                    });

                    // Camera slight movement for more dynamic feel
                    camera.position.x = 2.5 + Math.sin(time * 0.2) * 0.3;
                    camera.position.y = 1.8 + Math.cos(time * 0.3) * 0.2;
                    camera.lookAt(0, 0.8, 0);

                    renderer.render(scene, camera);
                }

                animate();

                // Handle window resize
                window.addEventListener('resize', () => {
                    camera.aspect = window.innerWidth / window.innerHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(window.innerWidth, window.innerHeight);
                });
            });

            // Success animation after login (confetti)
            @if (session('status') && str_contains(session('status'), 'connecté') || request()->routeIs('dashboard'))
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(function() {
                        confetti({
                            particleCount: 200,
                            spread: 90,
                            origin: { y: 0.5 },
                            colors: ['#0ea5e9', '#a855f7', '#10b981', '#3b82f6']
                        });
                    }, 300);
                });
            @endif

            // Particles background animation
            const particlesCanvas = document.getElementById('particles-canvas');
            if (particlesCanvas) {
                const ctx = particlesCanvas.getContext('2d');
                particlesCanvas.width = window.innerWidth;
                particlesCanvas.height = window.innerHeight;

                const bgParticles = [];
                const bgParticleCount = 40;
                const bgColors = ['#0ea5e9', '#a855f7', '#10b981', '#3b82f6', '#8b5cf6'];

                class BGParticle {
                    constructor() {
                        this.reset();
                    }
                    
                    reset() {
                        this.x = Math.random() * particlesCanvas.width;
                        this.y = Math.random() * particlesCanvas.height;
                        this.size = Math.random() * 3 + 1;
                        this.speedX = Math.random() * 0.5 - 0.25;
                        this.speedY = Math.random() * 0.5 - 0.25;
                        this.color = bgColors[Math.floor(Math.random() * bgColors.length)];
                        this.opacity = Math.random() * 0.4 + 0.2;
                    }
                    
                    update() {
                        this.x += this.speedX;
                        this.y += this.speedY;
                        
                        if (this.x > particlesCanvas.width) this.x = 0;
                        if (this.x < 0) this.x = particlesCanvas.width;
                        if (this.y > particlesCanvas.height) this.y = 0;
                        if (this.y < 0) this.y = particlesCanvas.height;
                    }
                    
                    draw() {
                        ctx.save();
                        ctx.globalAlpha = this.opacity;
                        ctx.fillStyle = this.color;
                        ctx.beginPath();
                        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                        ctx.fill();
                        ctx.restore();
                    }
                }
                
                for (let i = 0; i < bgParticleCount; i++) {
                    bgParticles.push(new BGParticle());
                }
                
                function animateParticles() {
                    ctx.clearRect(0, 0, particlesCanvas.width, particlesCanvas.height);
                    bgParticles.forEach(particle => {
                        particle.update();
                        particle.draw();
                    });
                    requestAnimationFrame(animateParticles);
                }
                
                animateParticles();
                
                window.addEventListener('resize', () => {
                    particlesCanvas.width = window.innerWidth;
                    particlesCanvas.height = window.innerHeight;
                });
            }

            // Auto-refresh page if CSRF token expires (prevent 419 errors)
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.tagName === 'FORM' && form.method.toUpperCase() === 'POST') {
                    const formStartTime = form.dataset.startTime || Date.now();
                    form.dataset.startTime = formStartTime;
                    
                    if (Date.now() - formStartTime > 2 * 60 * 60 * 1000) {
                        if (!confirm('Votre session a peut-être expiré. Voulez-vous actualiser la page avant de continuer ?')) {
                            e.preventDefault();
                            return false;
                        }
                    }
                }
            });
        </script>
    </body>
</html>
