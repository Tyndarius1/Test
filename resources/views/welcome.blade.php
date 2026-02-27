<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaraDeploy-24 | Production Master</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&family=JetBrains+Mono&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #10b981; --bg: #020617; }
        body { font-family: 'Space Grotesk', sans-serif; background-color: var(--bg); color: white; cursor: none; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        #canvas-container { position: fixed; inset: 0; z-index: -1; pointer-events: none; }
        .scanline { position: fixed; inset: 0; background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06)); z-index: 100; background-size: 100% 2px, 3px 100%; pointer-events: none; }
        .custom-cursor { width: 20px; height: 20px; background: var(--primary); border-radius: 50%; position: fixed; pointer-events: none; z-index: 999; mix-blend-mode: difference; transition: transform 0.1s ease; }
        .step-card { background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 1.5rem; padding: 2rem; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .step-card:hover { border-color: var(--primary); background: rgba(16, 185, 129, 0.03); transform: translateY(-10px); }
        code { color: var(--primary); font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; line-height: 1.6; }
        .cmd-block { background: rgba(0,0,0,0.4); padding: 1rem; border-radius: 0.75rem; border-left: 2px solid var(--primary); margin-top: 1rem; }
    </style>
</head>
<body class="antialiased overflow-x-hidden">

    <div class="scanline"></div>
    <div class="custom-cursor" id="cursor"></div>
    <div id="canvas-container"></div>

    <div class="max-w-7xl mx-auto px-8 py-12 relative z-10">
        <nav class="flex justify-between items-center mb-32">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-emerald-500 rounded-2xl flex items-center justify-center font-bold text-black text-2xl shadow-[0_0_20px_rgba(16,185,129,0.5)]">L</div>
                <div class="leading-none">
                    <span class="font-extrabold tracking-tighter text-2xl block uppercase">LaraDeploy</span>
                    <span class="text-[10px] mono text-emerald-500/80 tracking-widest uppercase italic">Ubuntu 24.04 Master</span>
                </div>
            </div>
            <a href="#full-process" class="mono text-xs border border-white/20 px-6 py-3 rounded-full hover:bg-white/10 transition-all">
                Access System Manual →
            </a>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center mb-64">
            <div class="space-y-8">
                <div class="inline-block px-4 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-widest">
                    Production Environment Verified
                </div>
                <h1 class="text-8xl font-bold tracking-tighter leading-[0.85] text-white">
                    The Full <span class="text-emerald-500 italic block mt-2">Process.</span>
                </h1>
                <p class="text-xl text-slate-400 max-w-md leading-relaxed">
                    A comprehensive walkthrough of the **Bryan I. Dacera** deployment architecture for high-performance Laravel systems.
                </p>
            </div>

            <div class="bg-black/40 backdrop-blur-2xl border border-white/10 p-8 rounded-[2rem] shadow-2xl relative">
                <div class="flex gap-2 mb-6">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                </div>
                <div class="mono text-sm space-y-2 text-emerald-500/90">
                    <p class="text-white">// SYSTEM DIAGNOSTICS</p>
                    <p>> OS: Ubuntu 24.04 LTS (Jammy)</p>
                    <p>> Web: Nginx v1.24.0</p>
                    <p>> PHP: v8.3.x (FPM)</p>
                    <p>> DB: MySQL 8.0.x</p>
                    <p>> Composer: v2.7.x</p>
                    <p class="pt-4 text-emerald-400 font-bold">> STATUS: FULLY PROVISIONED</p>
                </div>
            </div>
        </div>

        <div id="full-process" class="space-y-32 pb-32">
            
            <section>
                <div class="mb-12">
                    <span class="mono text-emerald-500 text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 01</span>
                    <h2 class="text-5xl font-bold tracking-tight">Server Hardening</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="step-card">
                        <h3 class="text-xl font-bold mb-4">Non-Root Identity</h3>
                        <p class="text-slate-400 text-sm leading-relaxed mb-4">Securing the system by creating a dedicated administrator user instead of using root.</p>
                        <div class="cmd-block">
                            <code>adduser bryandacera</code><br>
                            <code>usermod -aG sudo bryandacera</code>
                        </div>
                    </div>
                    <div class="step-card">
                        <h3 class="text-xl font-bold mb-4">Perimeter Defense</h3>
                        <p class="text-slate-400 text-sm leading-relaxed mb-4">Configuring UFW (Uncomplicated Firewall) to allow only web and secure traffic.</p>
                        <div class="cmd-block">
                            <code>ufw allow OpenSSH</code><br>
                            <code>ufw allow 'Nginx Full'</code><br>
                            <code>ufw enable</code>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="mb-12">
                    <span class="mono text-emerald-500 text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 02</span>
                    <h2 class="text-5xl font-bold tracking-tight">The Core Engine</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="step-card">
                        <h3 class="text-xl font-bold mb-4 italic text-emerald-500">Nginx</h3>
                        <p class="text-slate-400 text-sm mb-4">The high-performance proxy server handling all requests.</p>
                        <div class="cmd-block"><code>apt install nginx</code></div>
                    </div>
                    <div class="step-card">
                        <h3 class="text-xl font-bold mb-4 italic text-emerald-500">PHP-FPM</h3>
                        <p class="text-slate-400 text-sm mb-4">PHP 8.3 plus all required Laravel extensions.</p>
                        <div class="cmd-block"><code>apt install php-fpm php-mysql php-mbstring php-xml php-curl</code></div>
                    </div>
                    <div class="step-card">
                        <h3 class="text-xl font-bold mb-4 italic text-emerald-500">MySQL</h3>
                        <p class="text-slate-400 text-sm mb-4">Relational database for Marcelino's system data.</p>
                        <div class="cmd-block"><code>apt install mysql-server</code></div>
                    </div>
                </div>
            </section>

            <section>
                <div class="mb-12">
                    <span class="mono text-emerald-500 text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 03</span>
                    <h2 class="text-5xl font-bold tracking-tight">Git & App Provisioning</h2>
                </div>
                <div class="step-card w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <div>
                            <h3 class="text-2xl font-bold mb-6 italic underline decoration-emerald-500/50">Production Deployment</h3>
                            <p class="text-slate-400 mb-8">Cloning the repository from GitHub and setting up the environment variables.</p>
                            <ul class="space-y-4 text-sm text-slate-300">
                                <li class="flex items-center gap-3"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Git Clone to /var/www</li>
                                <li class="flex items-center gap-3"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Composer install --no-dev</li>
                                <li class="flex items-center gap-3"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Generate APP_KEY</li>
                            </ul>
                        </div>
                        <div class="cmd-block !bg-black/60 !p-8">
                            <code class="block text-white mb-2"># RUN THESE ON THE SERVER:</code>
                            <code>git clone https://github.com/user/LaraDeploy-24.git</code><br>
                            <code>cd LaraDeploy-24 && composer install</code><br>
                            <code>cp .env.example .env && php artisan key:generate</code><br>
                            <code>sudo chown -R www-data:www-data storage bootstrap/cache</code>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <footer class="py-12 border-t border-white/10 flex flex-col md:flex-row justify-between gap-12">
            <div class="space-y-4 max-w-sm">
                <div class="flex items-center gap-2">
                    <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                    <span class="font-bold text-xs uppercase tracking-widest">System Operational</span>
                </div>
                <p class="text-slate-500 text-sm">Deployment documentation generated for the LaraDeploy-24 project. Optimized for Ubuntu 24.04 and Laravel 11.</p>
            </div>
            <div class="grid grid-cols-2 gap-12">
                <div class="space-y-2">
                    <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Primary Architect</p>
                    <p class="mono text-sm text-emerald-500">BRYAN I. DACERA</p>
                </div>
                <div class="space-y-2">
                    <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Deployment Year</p>
                    <p class="mono text-sm">2026</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Custom Cursor
        const cursor = document.getElementById('cursor');
        window.addEventListener('mousemove', (e) => {
            gsap.to(cursor, { x: e.clientX, y: e.clientY, duration: 0.1 });
        });

        // Background Particles
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth/window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ alpha: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('canvas-container').appendChild(renderer.domElement);

        const particlesGeometry = new THREE.BufferGeometry();
        const posArray = new Float32Array(3000 * 3);
        for(let i=0; i < 9000; i++) posArray[i] = (Math.random() - 0.5) * 10;
        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
        const material = new THREE.PointsMaterial({ size: 0.003, color: 0x10b981 });
        const particlesMesh = new THREE.Points(particlesGeometry, material);
        scene.add(particlesMesh);
        camera.position.z = 3;

        function animate() {
            requestAnimationFrame(animate);
            particlesMesh.rotation.y += 0.0003;
            renderer.render(scene, camera);
        }
        animate();

        // Reveal Animations
        gsap.from("h1", { opacity: 0, y: 100, duration: 1.5, ease: "power4.out" });
        gsap.from(".step-card", { 
            opacity: 0, 
            y: 40, 
            duration: 1, 
            stagger: 0.1, 
            ease: "power2.out",
            scrollTrigger: "#full-process" 
        });
    </script>
</body>
</html>