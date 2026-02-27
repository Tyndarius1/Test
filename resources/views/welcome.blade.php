<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaraDeploy-24 | Terminal Alpha</title>
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
    </style>
</head>
<body class="antialiased overflow-hidden">

    <div class="scanline"></div>
    <div class="custom-cursor" id="cursor"></div>
    <div id="canvas-container"></div>

    <div class="flex flex-col min-h-screen p-8 relative z-10">
        <nav class="flex justify-between items-center mb-12">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 bg-emerald-500 rounded-lg flex items-center justify-center font-bold text-black text-xl">L</div>
                <span class="font-bold tracking-tighter text-2xl uppercase italic">LaraDeploy-24</span>
            </div>
            <div class="mono text-[10px] text-emerald-500/60 uppercase tracking-[0.3em] bg-emerald-500/5 px-4 py-2 rounded-full border border-emerald-500/20">
                Connection: Secure // 128.0.0.1
            </div>
        </nav>

        <div class="flex-1 flex flex-col items-center justify-center">
            <div class="max-w-4xl w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <div class="bg-black/40 backdrop-blur-xl border border-white/10 p-6 rounded-2xl mono text-xs leading-relaxed overflow-hidden">
                    <div class="flex gap-2 mb-4 border-b border-white/5 pb-2">
                        <div class="w-2 h-2 rounded-full bg-red-500/50"></div>
                        <div class="w-2 h-2 rounded-full bg-yellow-500/50"></div>
                        <div class="w-2 h-2 rounded-full bg-emerald-500/50"></div>
                    </div>
                    <div id="terminal-content" class="text-emerald-500/80">
                        <p>> Initializing LaraDeploy-24...</p>
                        <p>> User: bryandacera detected.</p>
                        <p>> Environment: Ubuntu 24.04 LTS</p>
                        <p>> Stack: Nginx + PHP 8.3 + MySQL</p>
                        <p>> Deployment: Success.</p>
                    </div>
                    <div class="mt-4 animate-pulse text-white">_</div>
                </div>

                <div class="space-y-6">
                    <h2 class="text-6xl font-light tracking-tight leading-[0.9]">
                        Elevated <span class="font-bold text-emerald-500 block">Production.</span>
                    </h2>
                    <p class="text-slate-400 text-lg">
                        A seamless fusion of Laravel 11 architecture and high-performance server orchestration.
                    </p>
                    <div class="flex gap-4 pt-4">
                        <button class="bg-emerald-500 hover:bg-emerald-400 text-black px-8 py-4 rounded-full font-bold transition-all hover:scale-105 active:scale-95">
                            Launch Dashboard
                        </button>
                        <button class="border border-white/20 hover:bg-white/5 px-8 py-4 rounded-full font-bold transition-all">
                            View Logs
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <footer class="mt-auto grid grid-cols-2 md:grid-cols-4 gap-8 py-8 border-t border-white/10">
            <div class="space-y-1">
                <p class="text-[10px] text-slate-500 uppercase tracking-widest">CPU LOAD</p>
                <div class="h-1 bg-white/10 w-full rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 w-[24%] animate-pulse"></div>
                </div>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-slate-500 uppercase tracking-widest">Memory Usage</p>
                <p class="mono text-sm">412MB / 2048MB</p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] text-slate-500 uppercase tracking-widest">DB Status</p>
                <p class="mono text-sm text-emerald-400">ONLINE</p>
            </div>
            <div class="space-y-1 text-right">
                <p class="text-[10px] text-slate-500 uppercase tracking-widest">Last Update</p>
                <p class="mono text-sm italic">Just now</p>
            </div>
        </footer>
    </div>

    <script>
        // --- Custom Cursor ---
        const cursor = document.getElementById('cursor');
        window.addEventListener('mousemove', (e) => {
            gsap.to(cursor, { x: e.clientX, y: e.clientY, duration: 0.1 });
        });

        // --- Three.js Particle System ---
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth/window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ alpha: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('canvas-container').appendChild(renderer.domElement);

        const particlesGeometry = new THREE.BufferGeometry();
        const posArray = new Float32Array(5000 * 3);
        for(let i=0; i < 15000; i++) posArray[i] = (Math.random() - 0.5) * 10;
        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
        
        const material = new THREE.PointsMaterial({ size: 0.005, color: 0x10b981 });
        const particlesMesh = new THREE.Points(particlesGeometry, material);
        scene.add(particlesMesh);
        camera.position.z = 3;

        function animate() {
            requestAnimationFrame(animate);
            particlesMesh.rotation.y += 0.001;
            renderer.render(scene, camera);
        }
        animate();

        // --- GSAP Entrances ---
        gsap.from(".lg:grid-cols-2 > div", { opacity: 0, y: 50, duration: 1.5, stagger: 0.4, ease: "power4.out" });
    </script>
</body>
</html>