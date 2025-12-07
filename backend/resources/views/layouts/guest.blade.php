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
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- Three.js for 3D Animation -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
        
        <!-- Canvas Confetti Library -->
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-primary-50 via-white to-secondary-50 relative">
        <!-- 3D Character Animation Background -->
        <div id="login-3d-container" class="fixed inset-0 w-full h-full pointer-events-none" style="z-index: 0; opacity: 0.6;"></div>
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

                // Position camera
                camera.position.set(3, 1.5, 4);
                camera.lookAt(0, 0.5, 0);

                // Animation loop
                let time = 0;
                function animate() {
                    requestAnimationFrame(animate);
                    time += 0.01;

                    // Character animation (gentle floating and rotation)
                    characterGroup.rotation.y = Math.sin(time * 0.5) * 0.1;
                    characterGroup.position.y = Math.sin(time) * 0.1;
                    head.rotation.y = Math.sin(time * 2) * 0.1;
                    
                    // Arms animation (typing/connecting gesture)
                    leftArm.rotation.z = 0.3 + Math.sin(time * 3) * 0.1;
                    rightArm.rotation.z = -0.3 - Math.sin(time * 3) * 0.1;
                    
                    // Device glow animation
                    device.material.emissiveIntensity = 0.3 + Math.sin(time * 4) * 0.2;

                    // Connection lines animation
                    characterGroup.children.forEach((child, index) => {
                        if (child instanceof THREE.Line) {
                            child.material.opacity = 0.4 + Math.sin(time * 2 + index) * 0.3;
                        }
                    });

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

            // Auto-refresh page if CSRF token expires (prevent 419 errors)
            // This will refresh the page if user tries to submit after session expired
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.tagName === 'FORM' && form.method.toUpperCase() === 'POST') {
                    // Check if form has been open for more than 2 hours (session might expire)
                    const formStartTime = form.dataset.startTime || Date.now();
                    form.dataset.startTime = formStartTime;
                    
                    if (Date.now() - formStartTime > 2 * 60 * 60 * 1000) {
                        // Form has been open for more than 2 hours, suggest refresh
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
