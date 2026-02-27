<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bryan Dacera Documentation</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&family=JetBrains+Mono&display=swap" rel="stylesheet" />

  <style>
    :root { --primary: #10b981; --bg: #020617; --panel: rgba(255,255,255,0.03); --border: rgba(255,255,255,0.10); }
    body { font-family: 'Space Grotesk', sans-serif; background-color: var(--bg); color: white; cursor: none; }

    .mono { font-family: 'JetBrains Mono', monospace; }
    #canvas-container { position: fixed; inset: 0; z-index: -1; pointer-events: none; }

    .scanline {
      position: fixed; inset: 0;
      background:
        linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%),
        linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
      z-index: 100;
      background-size: 100% 2px, 3px 100%;
      pointer-events: none;
    }

    .custom-cursor {
      width: 20px; height: 20px; background: var(--primary); border-radius: 50%;
      position: fixed; pointer-events: none; z-index: 999;
      mix-blend-mode: difference; transition: transform 0.1s ease;
    }

    /* ALWAYS VISIBLE — no opacity tricks */
    .phase-section, .step-card, .phase-head, .cmd-block { opacity: 1 !important; transform: none !important; }

    .step-card {
      background: rgba(255, 255, 255, 0.02);
      border: 1px solid rgba(255, 255, 255, 0.06);
      border-radius: 1.5rem;
      padding: 2rem;
      transition: all 0.25s ease;
    }
    .step-card:hover {
      border-color: rgba(16,185,129,0.55);
      background: rgba(16, 185, 129, 0.03);
      transform: translateY(-6px) !important;
    }

    code { color: var(--primary); font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; line-height: 1.75; }

    .cmd-block {
      background: rgba(0,0,0,0.45);
      padding: 1rem 1rem 0.9rem;
      border-radius: 0.85rem;
      border: 1px solid rgba(255,255,255,0.08);
      border-left: 2px solid var(--primary);
      margin-top: 1rem;
      position: relative;
      overflow: hidden;
    }

    .cmd-toolbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.75rem;
      margin-bottom: 0.65rem;
    }
    .cmd-title {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.75rem;
      letter-spacing: 0.16em;
      text-transform: uppercase;
      color: rgba(226, 232, 240, 0.85);
    }
    .btn {
      cursor: pointer;
      border: 1px solid rgba(255,255,255,0.16);
      background: rgba(255,255,255,0.04);
      padding: 0.4rem 0.7rem;
      border-radius: 999px;
      font-size: 0.72rem;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      transition: all 0.2s ease;
      user-select: none;
    }
    .btn:hover { background: rgba(255,255,255,0.08); border-color: rgba(16,185,129,0.45); }
    .btn:active { transform: translateY(1px); }

    .explain { margin-top: 0.85rem; }
    .explain p { font-size: 0.92rem; line-height: 1.75; color: rgba(148, 163, 184, 1); }
    .explain b { color: rgba(226, 232, 240, 1); font-weight: 700; }

    .tag {
      display: inline-block; padding: 0.25rem 0.7rem; border-radius: 999px;
      border: 1px solid rgba(255,255,255,0.14);
      background: rgba(255,255,255,0.03);
      font-size: 0.7rem; letter-spacing: 0.14em; text-transform: uppercase;
    }
    .phase-label { color: rgba(52, 211, 153, 0.98); text-shadow: 0 0 18px rgba(16,185,129,0.18); }

    .warn { border-left-color: rgba(239, 68, 68, 1) !important; }
    .good { border-left-color: rgba(16,185,129,1) !important; }

    /* Sticky sidebar */
    .sidebar {
      position: sticky;
      top: 1.25rem;
      max-height: calc(100vh - 2.5rem);
      overflow: auto;
      scrollbar-width: thin;
    }
    .sidebar::-webkit-scrollbar { width: 8px; }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 999px; }

    .toc-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.75rem;
      padding: 0.65rem 0.75rem;
      border-radius: 0.9rem;
      border: 1px solid rgba(255,255,255,0.08);
      background: rgba(255,255,255,0.02);
      transition: all 0.2s ease;
    }
    .toc-item:hover { border-color: rgba(16,185,129,0.45); background: rgba(16,185,129,0.03); }
    .toc-left { display: flex; align-items: center; gap: 0.6rem; }
    .toc-dot { width: 8px; height: 8px; border-radius: 999px; background: rgba(255,255,255,0.22); }
    .toc-item.done .toc-dot { background: rgba(16,185,129,1); box-shadow: 0 0 18px rgba(16,185,129,0.35); }
    .toc-label { font-size: 0.82rem; color: rgba(226,232,240,0.9); }
    .toc-phase { font-size: 0.72rem; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(148,163,184,1); }
    .toc-item.done { border-color: rgba(16,185,129,0.55); }

    /* Checklist */
    .checkline {
      display: flex;
      align-items: flex-start;
      gap: 0.7rem;
      padding: 0.6rem 0.75rem;
      border-radius: 0.9rem;
      border: 1px solid rgba(255,255,255,0.08);
      background: rgba(255,255,255,0.02);
      transition: all 0.2s ease;
    }
    .checkline:hover { border-color: rgba(16,185,129,0.45); background: rgba(16,185,129,0.03); }
    .checkline input[type="checkbox"] {
      appearance: none;
      width: 18px; height: 18px;
      border-radius: 6px;
      border: 1px solid rgba(255,255,255,0.22);
      background: rgba(255,255,255,0.02);
      margin-top: 2px;
      position: relative;
      flex: none;
      cursor: pointer;
    }
    .checkline input[type="checkbox"]:checked {
      border-color: rgba(16,185,129,0.85);
      background: rgba(16,185,129,0.18);
      box-shadow: 0 0 16px rgba(16,185,129,0.22);
    }
    .checkline input[type="checkbox"]:checked::after {
      content: "✓";
      position: absolute;
      inset: 0;
      display: grid;
      place-items: center;
      font-size: 12px;
      color: rgba(16,185,129,1);
      font-weight: 800;
    }
    .checktext { font-size: 0.92rem; line-height: 1.55; color: rgba(226,232,240,0.9); }
    .checksub { font-size: 0.83rem; color: rgba(148,163,184,1); margin-top: 0.25rem; line-height: 1.55; }
    .muted { color: rgba(148,163,184,1); }

    /* Toast */
    .toast {
      position: fixed;
      right: 1.25rem;
      bottom: 1.25rem;
      z-index: 2000;
      padding: 0.75rem 1rem;
      border-radius: 1rem;
      border: 1px solid rgba(255,255,255,0.14);
      background: rgba(2, 6, 23, 0.75);
      backdrop-filter: blur(14px);
      display: none;
      align-items: center;
      gap: 0.75rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.35);
    }
    .toast.show { display: flex; }
    .toast-dot { width: 10px; height: 10px; border-radius: 999px; background: rgba(16,185,129,1); box-shadow: 0 0 20px rgba(16,185,129,0.35); }
    .toast-text { font-size: 0.88rem; color: rgba(226,232,240,0.92); }
  </style>
</head>

<body class="antialiased overflow-x-hidden">
  <div class="scanline"></div>
  <div class="custom-cursor" id="cursor"></div>
  <div id="canvas-container"></div>

  <!-- Toast -->
  <div class="toast" id="toast">
    <div class="toast-dot"></div>
    <div class="toast-text" id="toastText">Copied to clipboard.</div>
  </div>

<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10">
    
<!-- Top Nav -->
    <nav class="flex justify-between items-center mb-16">
      <div class="flex items-center gap-4">
        <div class="h-12 w-12 bg-emerald-500 rounded-2xl flex items-center justify-center font-bold text-black text-2xl shadow-[0_0_20px_rgba(16,185,129,0.5)]">L</div>
        <div class="leading-none">
          <span class="font-extrabold tracking-tighter text-2xl block uppercase">Bryan Dacera</span>
          <span class="text-[10px] mono text-emerald-500/80 tracking-widest uppercase italic">Ubuntu 24.04 LTS Newbie</span>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <a href="#full-process" class="mono text-xs border border-white/20 px-5 py-3 rounded-full hover:bg-white/10 transition-all">
          Access System Manual →
        </a>
        <button id="resetChecklist" class="btn mono">Reset Checklist</button>
      </div>
    </nav>

    <!-- Hero -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-14">
      <div class="space-y-7">
        <div class="inline-block px-4 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-widest">
          Ubuntu 24.04 LTS + Nginx + PHP + MySQL + Laravel
        </div>

        <h1 class="text-6xl sm:text-7xl lg:text-8xl font-bold tracking-tighter leading-[0.88] text-white">
          The Full <span class="text-emerald-500 italic block mt-2">Process.</span>
        </h1>

        <p class="text-lg sm:text-xl text-slate-400 max-w-xl leading-relaxed">
          <!-- End-to-end deployment manual for Laravel on DigitalOcean: Ubuntu 24.04 LTS, Nginx, PHP-FPM, MySQL, Composer, and hardened access workflow. -->
            A comprehensive, step-by-step guide to developing a Laravel Project on DigitalOcean with Ubuntu 24.04 LTS, Nginx, PHP, MySQL, and best practices for security and performance.
        </p>

        <div class="flex flex-wrap gap-3">
         <span class="tag mono">LAMP Stack</span>
        <span class="tag mono">Nginx Runtime</span>
        <span class="tag mono">PHP-FPM Engine</span>
        <span class="tag mono">MySQL Backend</span>
        <span class="tag mono">Cloud Provisioned</span>
        <span class="tag mono">Production Hardened</span>
        </div>
      </div>

      <div class="bg-black/40 backdrop-blur-2xl border border-white/10 p-7 sm:p-8 rounded-[2rem] shadow-2xl relative">
        <div class="flex gap-2 mb-6">
          <div class="w-3 h-3 rounded-full bg-red-500"></div>
          <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
          <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
        </div>
        <div class="mono text-sm space-y-2 text-emerald-500/90">
          <p class="text-white">// SYSTEM DIAGNOSTICS</p>
          <p>> OS: Ubuntu 24.04 LTS (Noble)</p>
          <p>> Web: Nginx</p>
          <p>> PHP: 8.3.x (FPM)</p>
          <p>> DB: MySQL 8.0.x</p>
          <p>> Composer: 2.x</p>
          <p class="pt-4 text-emerald-400 font-bold">> STATUS: DOCS + TOOLING READY</p>
        </div>
      </div>
    </div>

    <!-- Layout: Sidebar + Content -->
        <div id="full-process" class="grid grid-cols-1 lg:grid-cols-[440px_minmax(0,1fr)] gap-12 pb-24">


      <!-- Sticky Sidebar TOC -->
      <aside class="sidebar">
        <div class="bg-black/35 border border-white/10 rounded-[1.6rem] p-6 backdrop-blur-xl shadow-2xl">
          <div class="flex items-center justify-between mb-4">
            <div>
              <div class="mono text-[11px] tracking-[0.4em] uppercase text-emerald-400 font-bold">System Manual</div>
              <div class="text-2xl font-bold tracking-tight">Table of Contents</div>
            </div>
            <span class="tag mono">Phase 00–11</span>
          </div>

          <div class="space-y-2" id="toc">
            <!-- JS will enhance done state -->
            <a class="toc-item" data-phase="p00" href="#p00">
              <div class="toc-left">
                <div class="toc-dot"></div>
                <div>
                  <div class="toc-phase mono">Phase 00</div>
                  <div class="toc-label">Cloud Firewall</div>
                </div>
              </div>
              <div class="mono muted text-xs" id="toc-p00">0%</div>
            </a>

            <a class="toc-item" data-phase="p01" href="#p01">
              <div class="toc-left">
                <div class="toc-dot"></div>
                <div>
                  <div class="toc-phase mono">Phase 01</div>
                  <div class="toc-label">Server Hardening</div>
                </div>
              </div>
              <div class="mono muted text-xs" id="toc-p01">0%</div>
            </a>

            <a class="toc-item" data-phase="p02" href="#p02">
              <div class="toc-left">
                <div class="toc-dot"></div>
                <div>
                  <div class="toc-phase mono">Phase 02</div>
                  <div class="toc-label">Core Services</div>
                </div>
              </div>
              <div class="mono muted text-xs" id="toc-p02">0%</div>
            </a>

            <a class="toc-item" data-phase="p03" href="#p03">
              <div class="toc-left">
                <div class="toc-dot"></div>
                <div>
                  <div class="toc-phase mono">Phase 03</div>
                  <div class="toc-label">Composer</div>
                </div>
              </div>
              <div class="mono muted text-xs" id="toc-p03">0%</div>
            </a>

            <a class="toc-item" data-phase="p04" href="#p04">
              <div class="toc-left">
                <div class="toc-dot"></div>
                <div>
                  <div class="toc-phase mono">Phase 04</div>
                  <div class="toc-label">Database Setup</div>
                </div>
              </div>
              <div class="mono muted text-xs" id="toc-p04">0%</div>
            </a>

            <a class="toc-item" data-phase="p05" href="#p05">
              <div class="toc-left">
                <div class="toc-dot"></div>
                <div>
                  <div class="toc-phase mono">Phase 05</div>
                  <div class="toc-label">Deploy Laravel</div>
                </div>
              </div>
              <div class="mono muted text-xs" id="toc-p05">0%</div>
            </a>

            <a class="toc-item" data-phase="p06" href="#p06">
              <div class="toc-left">
                <div class="toc-dot"></div>
                <div>
                  <div class="toc-phase mono">Phase 06</div>
                  <div class="toc-label">Nginx Config</div>
                </div>
              </div>
              <div class="mono muted text-xs" id="toc-p06">0%</div>
            </a>

            <a class="toc-item" data-phase="p07" href="#p07">
              <div class="toc-left">
                <div class="toc-dot"></div>
                <div>
                  <div class="toc-phase mono">Phase 07</div>
                  <div class="toc-label">HTTPS</div>
                </div>
              </div>
              <div class="mono muted text-xs" id="toc-p07">0%</div>
            </a>

            <a class="toc-item" data-phase="p08" href="#p08">
              <div class="toc-left">
                <div class="toc-dot"></div>
                <div>
                  <div class="toc-phase mono">Phase 08</div>
                  <div class="toc-label">Laravel Optimize</div>
                </div>
              </div>
              <div class="mono muted text-xs" id="toc-p08">0%</div>
            </a>

            <a class="toc-item" data-phase="p09" href="#p09">
              <div class="toc-left">
                <div class="toc-dot"></div>
                <div>
                  <div class="toc-phase mono">Phase 09</div>
                  <div class="toc-label">Cron Scheduler</div>
                </div>
              </div>
              <div class="mono muted text-xs" id="toc-p09">0%</div>
            </a>

            <a class="toc-item" data-phase="p10" href="#p10">
              <div class="toc-left">
                <div class="toc-dot"></div>
                <div>
                  <div class="toc-phase mono">Phase 10</div>
                  <div class="toc-label">Queues</div>
                </div>
              </div>
              <div class="mono muted text-xs" id="toc-p10">0%</div>
            </a>

            <a class="toc-item" data-phase="p11" href="#p11">
              <div class="toc-left">
                <div class="toc-dot"></div>
                <div>
                  <div class="toc-phase mono">Phase 11</div>
                  <div class="toc-label">Verify + Debug</div>
                </div>
              </div>
              <div class="mono muted text-xs" id="toc-p11">0%</div>
            </a>
          </div>

          <div class="mt-5 p-4 rounded-[1.2rem] border border-white/10 bg-white/5">
            <div class="mono text-[11px] tracking-[0.35em] uppercase text-emerald-400 font-bold mb-2">Checklist Mode</div>
            <div class="text-sm text-slate-300 leading-relaxed">
              Tick each item as you complete it. Progress is saved in your browser.
            </div>
          </div>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="space-y-16">

        <!-- ==================== Phase 00 ==================== -->
        <section id="p00" class="phase-section">
          <div class="mb-6 phase-head">
            <span class="mono phase-label text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 00</span>
            <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">DigitalOcean Perimeter (Cloud Firewall)</h2>
            <p class="text-slate-400 mt-3 max-w-3xl">
              Use DigitalOcean’s Cloud Firewall to restrict inbound traffic before it hits the droplet.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="step-card">
              <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold">Inbound Rules</h3>
                <span class="tag mono">network</span>
              </div>

              <div class="cmd-block" data-cmdblock="p00-inbound">
                <div class="cmd-toolbar">
                  <div class="cmd-title mono">Commands / Rules</div>
                  <button class="btn mono copy-btn" data-copy-target="p00-inbound">Copy</button>
                </div>
                <pre class="mono whitespace-pre-wrap leading-relaxed"><code>SSH 22/tcp     → allow YOUR IP only
HTTP 80/tcp    → allow 0.0.0.0/0
HTTPS 443/tcp  → allow 0.0.0.0/0
MySQL 3306/tcp → keep CLOSED (or allow your IP only if required)</code></pre>
              </div>

              <div class="explain">
                <p><b>Why:</b> limiting SSH to your IP prevents brute-force attacks. Leaving MySQL closed protects your database from public scans.</p>
              </div>

              <div class="mt-5 space-y-3">
                <label class="checkline">
                  <input type="checkbox" data-check="p00-1" data-phase="p00" />
                  <div>
                    <div class="checktext">Cloud Firewall attached to droplet</div>
                    <div class="checksub">Rules applied in DigitalOcean Network tab.</div>
                  </div>
                </label>

                <label class="checkline">
                  <input type="checkbox" data-check="p00-2" data-phase="p00" />
                  <div>
                    <div class="checktext">SSH restricted to my IP</div>
                    <div class="checksub">Only trusted IPs can reach port 22.</div>
                  </div>
                </label>

                <label class="checkline">
                  <input type="checkbox" data-check="p00-3" data-phase="p00" />
                  <div>
                    <div class="checktext">MySQL port 3306 not publicly open</div>
                    <div class="checksub">Open only if you truly need remote DB access.</div>
                  </div>
                </label>
              </div>
            </div>

            <div class="step-card">
              <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold">Local vs Remote DB User</h3>
                <span class="tag mono">policy</span>
              </div>

              <div class="cmd-block warn" data-cmdblock="p00-dbpolicy">
                <div class="cmd-toolbar">
                  <div class="cmd-title mono">Guideline</div>
                  <button class="btn mono copy-btn" data-copy-target="p00-dbpolicy">Copy</button>
                </div>
                <pre class="mono whitespace-pre-wrap leading-relaxed"><code>Local-only user (recommended): 'app_user'@'localhost'
Remote user (avoid):            'app_user'@'%'</code></pre>
              </div>

              <div class="explain">
                <p><b>Local-only</b> is safer and faster (Laravel and MySQL on the same droplet). A remote user enables connections from other machines if MySQL is exposed.</p>
              </div>

              <div class="mt-5 space-y-3">
                <label class="checkline">
                  <input type="checkbox" data-check="p00-4" data-phase="p00" />
                  <div>
                    <div class="checktext">I understand local vs remote MySQL users</div>
                    <div class="checksub">Local is <span class="mono">'user'@'localhost'</span>.</div>
                  </div>
                </label>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================== Phase 01 ==================== -->
        <section id="p01" class="phase-section">
          <div class="mb-6 phase-head">
            <span class="mono phase-label text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 01</span>
            <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Server Hardening & Identity</h2>
            <p class="text-slate-400 mt-3 max-w-3xl">
              Avoid daily root usage. Use a normal user and elevate with <span class="mono">sudo</span> only when needed.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="step-card">
              <h3 class="text-xl font-bold">Create Non-Root Sudo User</h3>

              <div class="cmd-block" data-cmdblock="p01-user">
                <div class="cmd-toolbar">
                  <div class="cmd-title mono">Commands</div>
                  <button class="btn mono copy-btn" data-copy-target="p01-user">Copy</button>
                </div>
                <pre class="mono whitespace-pre-wrap leading-relaxed"><code>adduser bryan
usermod -aG sudo bryan
su - bryan</code></pre>
              </div>

              <div class="explain">
                <p><b>adduser:</b> creates a new Linux user + home directory.</p>
                <p><b>usermod -aG sudo:</b> grants admin permissions via sudo.</p>
                <p><b>su - bryan:</b> switches user and loads their environment.</p>
                <p><b>Why:</b> reduces “one typo destroys the server” risk and improves accountability.</p>
              </div>

              <div class="mt-5 space-y-3">
                <label class="checkline">
                  <input type="checkbox" data-check="p01-1" data-phase="p01" />
                  <div>
                    <div class="checktext">Non-root sudo user created</div>
                    <div class="checksub">User added to sudo group.</div>
                  </div>
                </label>
                <label class="checkline">
                  <input type="checkbox" data-check="p01-2" data-phase="p01" />
                  <div>
                    <div class="checktext">I can run sudo commands</div>
                    <div class="checksub">Example: <span class="mono">sudo whoami</span> returns root.</div>
                  </div>
                </label>
              </div>
            </div>

            <div class="step-card">
              <h3 class="text-xl font-bold">Update OS + Install Basic Tools</h3>

              <div class="cmd-block" data-cmdblock="p01-tools">
                <div class="cmd-toolbar">
                  <div class="cmd-title mono">Commands</div>
                  <button class="btn mono copy-btn" data-copy-target="p01-tools">Copy</button>
                </div>
                <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo apt update
sudo apt upgrade -y
sudo apt install -y unzip curl git ca-certificates lsb-release software-properties-common</code></pre>
              </div>

              <div class="explain">
                <p><b>apt update:</b> refresh package index.</p>
                <p><b>apt upgrade -y:</b> installs updates (auto-confirm).</p>
                <p><b>unzip:</b> Composer uses zip archives frequently.</p>
                <p><b>curl:</b> downloads installers/scripts over HTTPS.</p>
                <p><b>git:</b> clone/pull your repository.</p>
                <p><b>ca-certificates:</b> prevents SSL verification errors.</p>
                <p><b>lsb-release:</b> identifies distro/codename for scripts.</p>
                <p><b>software-properties-common:</b> provides add-apt-repository.</p>
              </div>

              <div class="mt-5 space-y-3">
                <label class="checkline">
                  <input type="checkbox" data-check="p01-3" data-phase="p01" />
                  <div>
                    <div class="checktext">System updated</div>
                    <div class="checksub">Packages upgraded successfully.</div>
                  </div>
                </label>
                <label class="checkline">
                  <input type="checkbox" data-check="p01-4" data-phase="p01" />
                  <div>
                    <div class="checktext">Basic tools installed</div>
                    <div class="checksub">Git/curl/unzip/CA certs ready.</div>
                  </div>
                </label>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================== Phase 02 ==================== -->
        <section id="p02" class="phase-section">
          <div class="mb-6 phase-head">
            <span class="mono phase-label text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 02</span>
            <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Core Services (Nginx, MySQL, PHP-FPM)</h2>
            <p class="text-slate-400 mt-3 max-w-3xl">
              Install your web server, database server, and PHP runtime for Laravel.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div class="step-card">
              <h3 class="text-xl font-bold italic text-emerald-500">Nginx</h3>

              <div class="cmd-block" data-cmdblock="p02-nginx">
                <div class="cmd-toolbar">
                  <div class="cmd-title mono">Commands</div>
                  <button class="btn mono copy-btn" data-copy-target="p02-nginx">Copy</button>
                </div>
                <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo apt install -y nginx
sudo systemctl enable --now nginx
systemctl status nginx --no-pager</code></pre>
              </div>

              <div class="explain">
                <p><b>enable --now:</b> starts nginx now and on boot.</p>
                <p><b>status:</b> verifies it’s active (running).</p>
              </div>

              <div class="mt-5 space-y-3">
                <label class="checkline">
                  <input type="checkbox" data-check="p02-1" data-phase="p02" />
                  <div>
                    <div class="checktext">Nginx installed and running</div>
                    <div class="checksub">Visiting server IP shows nginx page (before app config).</div>
                  </div>
                </label>
              </div>
            </div>

            <div class="step-card">
              <h3 class="text-xl font-bold italic text-emerald-500">MySQL</h3>

              <div class="cmd-block" data-cmdblock="p02-mysql">
                <div class="cmd-toolbar">
                  <div class="cmd-title mono">Commands</div>
                  <button class="btn mono copy-btn" data-copy-target="p02-mysql">Copy</button>
                </div>
                <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo apt install -y mysql-server
sudo systemctl enable --now mysql
sudo mysql_secure_installation</code></pre>
              </div>

              <div class="explain">
                <p><b>mysql_secure_installation:</b> hardens MySQL (recommended).</p>
              </div>

              <div class="mt-5 space-y-3">
                <label class="checkline">
                  <input type="checkbox" data-check="p02-2" data-phase="p02" />
                  <div>
                    <div class="checktext">MySQL installed and secured</div>
                    <div class="checksub">Service enabled and running.</div>
                  </div>
                </label>
              </div>
            </div>

            <div class="step-card">
              <h3 class="text-xl font-bold italic text-emerald-500">PHP-FPM + Extensions</h3>

              <div class="cmd-block" data-cmdblock="p02-php">
                <div class="cmd-toolbar">
                  <div class="cmd-title mono">Commands</div>
                  <button class="btn mono copy-btn" data-copy-target="p02-php">Copy</button>
                </div>
                <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo apt install -y php-fpm php-mysql php-cli php-xml php-mbstring php-curl php-zip php-bcmath php-intl
php -v</code></pre>
              </div>

              <div class="explain">
                <p><b>php-fpm:</b> runs PHP for Nginx via FastCGI.</p>
                <p><b>extensions:</b> typical Laravel requirements (DB, strings, zip, intl).</p>
              </div>

              <div class="mt-5 space-y-3">
                <label class="checkline">
                  <input type="checkbox" data-check="p02-3" data-phase="p02" />
                  <div>
                    <div class="checktext">PHP installed (FPM) and verified</div>
                    <div class="checksub">PHP version shows 8.3.x.</div>
                  </div>
                </label>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================== Phase 03 ==================== -->
        <section id="p03" class="phase-section">
          <div class="mb-6 phase-head">
            <span class="mono phase-label text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 03</span>
            <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Composer Installation</h2>
            <p class="text-slate-400 mt-3 max-w-3xl">
              Composer installs Laravel dependencies from <span class="mono">composer.lock</span>.
            </p>
          </div>

          <div class="step-card">
            <div class="cmd-block" data-cmdblock="p03-composer">
              <div class="cmd-toolbar">
                <div class="cmd-title mono">Commands</div>
                <button class="btn mono copy-btn" data-copy-target="p03-composer">Copy</button>
              </div>
              <pre class="mono whitespace-pre-wrap leading-relaxed"><code>cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
composer --version</code></pre>
            </div>

            <div class="explain">
              <p><b>curl:</b> downloads the Composer installer.</p>
              <p><b>sudo php ...:</b> installs Composer globally.</p>
              <p><b>composer --version:</b> verifies installation.</p>
            </div>

            <div class="mt-5 space-y-3">
              <label class="checkline">
                <input type="checkbox" data-check="p03-1" data-phase="p03" />
                <div>
                  <div class="checktext">Composer installed and working</div>
                  <div class="checksub">Running <span class="mono">composer --version</span> returns a version.</div>
                </div>
              </label>
            </div>
          </div>
        </section>

        <!-- ==================== Phase 04 ==================== -->
        <section id="p04" class="phase-section">
          <div class="mb-6 phase-head">
            <span class="mono phase-label text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 04</span>
            <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Database Setup (Local-Only User)</h2>
            <p class="text-slate-400 mt-3 max-w-3xl">
              Create a database + user restricted to <span class="mono">@localhost</span>. This is not a remote user.
            </p>
          </div>

          <div class="step-card">
            <div class="cmd-block" data-cmdblock="p04-db">
              <div class="cmd-toolbar">
                <div class="cmd-title mono">SQL Commands</div>
                <button class="btn mono copy-btn" data-copy-target="p04-db">Copy</button>
              </div>
              <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo mysql

CREATE DATABASE your_db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'your_db_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON your_db_name.* TO 'your_db_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;</code></pre>
            </div>

            <div class="explain">
              <p><b>@localhost:</b> only allows connections from the same server (recommended for Laravel on one droplet).</p>
              <p><b>utf8mb4:</b> modern charset (emoji-safe).</p>
            </div>

            <div class="cmd-block warn" data-cmdblock="p04-remote">
              <div class="cmd-toolbar">
                <div class="cmd-title mono">Remote user (avoid)</div>
                <button class="btn mono copy-btn" data-copy-target="p04-remote">Copy</button>
              </div>
              <pre class="mono whitespace-pre-wrap leading-relaxed"><code>CREATE USER 'your_db_user'@'%' IDENTIFIED BY 'password';</code></pre>
            </div>

            <div class="explain">
              <p><b>Why avoid %:</b> it allows any host to connect if MySQL is reachable. Keep port 3306 closed unless necessary.</p>
            </div>

            <div class="mt-5 space-y-3">
              <label class="checkline">
                <input type="checkbox" data-check="p04-1" data-phase="p04" />
                <div>
                  <div class="checktext">Database and user created</div>
                  <div class="checksub">Local-only MySQL user is ready for Laravel.</div>
                </div>
              </label>
            </div>
          </div>
        </section>

        <!-- ==================== Phase 05 ==================== -->
        <section id="p05" class="phase-section">
          <div class="mb-6 phase-head">
            <span class="mono phase-label text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 05</span>
            <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Deploy Laravel (Git + Env + Permissions)</h2>
            <p class="text-slate-400 mt-3 max-w-3xl">
              Clone your project, install dependencies, configure <span class="mono">.env</span>, and fix permissions.
            </p>
          </div>

          <div class="step-card">
            <div class="cmd-block" data-cmdblock="p05-deploy">
              <div class="cmd-toolbar">
                <div class="cmd-title mono">Commands</div>
                <button class="btn mono copy-btn" data-copy-target="p05-deploy">Copy</button>
              </div>
              <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo mkdir -p /var/www
sudo chown -R $USER:$USER /var/www

cd /var/www
git clone https://github.com/USER/REPO.git app
cd app

composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate</code></pre>
            </div>

            <div class="explain">
              <p><b>chown /var/www:</b> lets your deploy user manage files without constant sudo.</p>
              <p><b>--no-dev:</b> production dependencies only.</p>
              <p><b>key:generate:</b> sets APP_KEY for encryption/sessions.</p>
            </div>

            <div class="cmd-block" data-cmdblock="p05-perms">
              <div class="cmd-toolbar">
                <div class="cmd-title mono">Runtime Permissions</div>
                <button class="btn mono copy-btn" data-copy-target="p05-perms">Copy</button>
              </div>
              <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

php artisan storage:link
php artisan migrate --force</code></pre>
            </div>

            <div class="explain">
              <p><b>storage & bootstrap/cache:</b> must be writable by the web server user (<span class="mono">www-data</span>).</p>
              <p><b>storage:link:</b> creates public/storage symlink.</p>
              <p><b>migrate --force:</b> runs migrations in production mode.</p>
            </div>

            <div class="mt-5 space-y-3">
              <label class="checkline">
                <input type="checkbox" data-check="p05-1" data-phase="p05" />
                <div>
                  <div class="checktext">Repo cloned and dependencies installed</div>
                  <div class="checksub">Composer install completed successfully.</div>
                </div>
              </label>

              <label class="checkline">
                <input type="checkbox" data-check="p05-2" data-phase="p05" />
                <div>
                  <div class="checktext">.env configured + APP_KEY generated</div>
                  <div class="checksub">DB credentials match the MySQL user.</div>
                </div>
              </label>

              <label class="checkline">
                <input type="checkbox" data-check="p05-3" data-phase="p05" />
                <div>
                  <div class="checktext">Permissions fixed (www-data can write)</div>
                  <div class="checksub">No “Permission denied” on logs/cache.</div>
                </div>
              </label>

              <label class="checkline">
                <input type="checkbox" data-check="p05-4" data-phase="p05" />
                <div>
                  <div class="checktext">Migrations completed</div>
                  <div class="checksub">Tables created in database.</div>
                </div>
              </label>
            </div>
          </div>
        </section>

        <!-- ==================== Phase 06 ==================== -->
        <section id="p06" class="phase-section">
          <div class="mb-6 phase-head">
            <span class="mono phase-label text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 06</span>
            <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Nginx Server Block (Laravel)</h2>
            <p class="text-slate-400 mt-3 max-w-3xl">
              Point Nginx to <span class="mono">/public</span> and forward PHP requests to PHP-FPM.
            </p>
          </div>

          <div class="step-card">
            <div class="cmd-block" data-cmdblock="p06-edit">
              <div class="cmd-toolbar">
                <div class="cmd-title mono">Create Config</div>
                <button class="btn mono copy-btn" data-copy-target="p06-edit">Copy</button>
              </div>
              <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo nano /etc/nginx/sites-available/app</code></pre>
            </div>

            <div class="cmd-block" data-cmdblock="p06-conf">
              <div class="cmd-toolbar">
                <div class="cmd-title mono">Nginx Config</div>
                <button class="btn mono copy-btn" data-copy-target="p06-conf">Copy</button>
              </div>
              <pre class="mono whitespace-pre-wrap leading-relaxed"><code>server {
  listen 80;
  server_name YOUR_DOMAIN_OR_IP;

  root /var/www/app/public;
  index index.php index.html;

  location / {
    try_files $uri $uri/ /index.php?$query_string;
  }

  location ~ \.php$ {
    include snippets/fastcgi-php.conf;
    fastcgi_pass unix:/run/php/php8.3-fpm.sock;
  }

  location ~ /\.(?!well-known).* {
    deny all;
  }
}</code></pre>
            </div>

            <div class="cmd-block" data-cmdblock="p06-enable">
              <div class="cmd-toolbar">
                <div class="cmd-title mono">Enable Site</div>
                <button class="btn mono copy-btn" data-copy-target="p06-enable">Copy</button>
              </div>
              <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo ln -s /etc/nginx/sites-available/app /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx</code></pre>
            </div>

            <div class="explain">
              <p><b>root /public:</b> Laravel entrypoint is <span class="mono">public/index.php</span>.</p>
              <p><b>try_files:</b> routes pretty URLs to Laravel.</p>
              <p><b>fastcgi_pass:</b> must match your PHP-FPM socket.</p>
              <p><b>nginx -t:</b> prevents bad reload.</p>
            </div>

            <div class="mt-5 space-y-3">
              <label class="checkline">
                <input type="checkbox" data-check="p06-1" data-phase="p06" />
                <div>
                  <div class="checktext">Nginx server block created</div>
                  <div class="checksub">App points to /var/www/app/public.</div>
                </div>
              </label>
              <label class="checkline">
                <input type="checkbox" data-check="p06-2" data-phase="p06" />
                <div>
                  <div class="checktext">Nginx config tested and reloaded</div>
                  <div class="checksub">nginx -t passed, site is live.</div>
                </div>
              </label>
            </div>
          </div>
        </section>

        <!-- ==================== Phase 07 ==================== -->
        <section id="p07" class="phase-section">
          <div class="mb-6 phase-head">
            <span class="mono phase-label text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 07</span>
            <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">HTTPS (Let’s Encrypt)</h2>
            <p class="text-slate-400 mt-3 max-w-3xl">
              Recommended if you have a domain. If you’re IP-only, set this up after domain points to the server.
            </p>
          </div>

          <div class="step-card">
            <div class="cmd-block" data-cmdblock="p07-ssl">
              <div class="cmd-toolbar">
                <div class="cmd-title mono">Commands</div>
                <button class="btn mono copy-btn" data-copy-target="p07-ssl">Copy</button>
              </div>
              <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d YOUR_DOMAIN
sudo certbot renew --dry-run</code></pre>
            </div>

            <div class="explain">
              <p><b>certbot --nginx:</b> obtains SSL certificate and updates Nginx config.</p>
              <p><b>renew --dry-run:</b> verifies auto-renewal works.</p>
            </div>

            <div class="mt-5 space-y-3">
              <label class="checkline">
                <input type="checkbox" data-check="p07-1" data-phase="p07" />
                <div>
                  <div class="checktext">SSL certificate installed</div>
                  <div class="checksub">HTTPS loads without warnings.</div>
                </div>
              </label>
            </div>
          </div>
        </section>

        <!-- ==================== Phase 08 ==================== -->
        <section id="p08" class="phase-section">
          <div class="mb-6 phase-head">
            <span class="mono phase-label text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 08</span>
            <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Laravel Production Optimization</h2>
            <p class="text-slate-400 mt-3 max-w-3xl">
              Cache config/routes/views for performance after your <span class="mono">.env</span> is final.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="step-card">
              <h3 class="text-xl font-bold">Cache for Speed</h3>
              <div class="cmd-block" data-cmdblock="p08-cache">
                <div class="cmd-toolbar">
                  <div class="cmd-title mono">Commands</div>
                  <button class="btn mono copy-btn" data-copy-target="p08-cache">Copy</button>
                </div>
                <pre class="mono whitespace-pre-wrap leading-relaxed"><code>php artisan config:cache
php artisan route:cache
php artisan view:cache</code></pre>
              </div>
              <div class="explain">
                <p><b>Why:</b> reduces runtime overhead and improves response speed.</p>
              </div>

              <div class="mt-5 space-y-3">
                <label class="checkline">
                  <input type="checkbox" data-check="p08-1" data-phase="p08" />
                  <div>
                    <div class="checktext">Caches built successfully</div>
                    <div class="checksub">No errors during caching.</div>
                  </div>
                </label>
              </div>
            </div>

            <div class="step-card">
              <h3 class="text-xl font-bold">If Env Changes</h3>
              <div class="cmd-block warn" data-cmdblock="p08-clear">
                <div class="cmd-toolbar">
                  <div class="cmd-title mono">Commands</div>
                  <button class="btn mono copy-btn" data-copy-target="p08-clear">Copy</button>
                </div>
                <pre class="mono whitespace-pre-wrap leading-relaxed"><code>php artisan config:clear
php artisan cache:clear
php artisan config:cache</code></pre>
              </div>
              <div class="explain">
                <p><b>Why:</b> config cache can “freeze” old .env values until cleared.</p>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================== Phase 09 ==================== -->
        <section id="p09" class="phase-section">
          <div class="mb-6 phase-head">
            <span class="mono phase-label text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 09</span>
            <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Cron (Laravel Scheduler)</h2>
            <p class="text-slate-400 mt-3 max-w-3xl">
              Scheduler needs cron to run every minute; Laravel decides which tasks execute.
            </p>
          </div>

          <div class="step-card">
            <div class="cmd-block" data-cmdblock="p09-cron">
              <div class="cmd-toolbar">
                <div class="cmd-title mono">Commands</div>
                <button class="btn mono copy-btn" data-copy-target="p09-cron">Copy</button>
              </div>
              <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo crontab -u www-data -e

* * * * * cd /var/www/app && php artisan schedule:run >> /dev/null 2>&1</code></pre>
            </div>

            <div class="explain">
              <p><b>Why www-data:</b> matches web permissions, avoids write errors in runtime directories.</p>
            </div>

            <div class="mt-5 space-y-3">
              <label class="checkline">
                <input type="checkbox" data-check="p09-1" data-phase="p09" />
                <div>
                  <div class="checktext">Scheduler cron added</div>
                  <div class="checksub">schedule:run executes every minute.</div>
                </div>
              </label>
            </div>
          </div>
        </section>

        <!-- ==================== Phase 10 ==================== -->
        <section id="p10" class="phase-section">
          <div class="mb-6 phase-head">
            <span class="mono phase-label text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 10</span>
            <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Queues (Supervisor)</h2>
            <p class="text-slate-400 mt-3 max-w-3xl">
              If your app uses queues, Supervisor keeps workers alive and auto-restarts on failure.
            </p>
          </div>

          <div class="step-card">
            <div class="cmd-block" data-cmdblock="p10-install">
              <div class="cmd-toolbar">
                <div class="cmd-title mono">Install</div>
                <button class="btn mono copy-btn" data-copy-target="p10-install">Copy</button>
              </div>
              <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo apt install -y supervisor
sudo nano /etc/supervisor/conf.d/laravel-worker.conf</code></pre>
            </div>

            <div class="cmd-block" data-cmdblock="p10-conf">
              <div class="cmd-toolbar">
                <div class="cmd-title mono">Supervisor Config</div>
                <button class="btn mono copy-btn" data-copy-target="p10-conf">Copy</button>
              </div>
              <pre class="mono whitespace-pre-wrap leading-relaxed"><code>[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/app/artisan queue:work --sleep=3 --tries=3 --timeout=90
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/app/storage/logs/worker.log
stopwaitsecs=3600</code></pre>
            </div>

            <div class="cmd-block" data-cmdblock="p10-apply">
              <div class="cmd-toolbar">
                <div class="cmd-title mono">Apply</div>
                <button class="btn mono copy-btn" data-copy-target="p10-apply">Copy</button>
              </div>
              <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status</code></pre>
            </div>

            <div class="mt-5 space-y-3">
              <label class="checkline">
                <input type="checkbox" data-check="p10-1" data-phase="p10" />
                <div>
                  <div class="checktext">Queue worker configured and running</div>
                  <div class="checksub">supervisorctl status shows RUNNING.</div>
                </div>
              </label>
            </div>
          </div>
        </section>

        <!-- ==================== Phase 11 ==================== -->
        <section id="p11" class="phase-section">
          <div class="mb-6 phase-head">
            <span class="mono phase-label text-sm tracking-[0.5em] block mb-2 uppercase font-bold">Phase 11</span>
            <h2 class="text-4xl sm:text-5xl font-bold tracking-tight">Verification & Troubleshooting</h2>
            <p class="text-slate-400 mt-3 max-w-3xl">
              Quick checks for the most common deployment failures.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="step-card">
              <h3 class="text-xl font-bold">Service Status</h3>
              <div class="cmd-block" data-cmdblock="p11-status">
                <div class="cmd-toolbar">
                  <div class="cmd-title mono">Commands</div>
                  <button class="btn mono copy-btn" data-copy-target="p11-status">Copy</button>
                </div>
                <pre class="mono whitespace-pre-wrap leading-relaxed"><code>systemctl status nginx --no-pager
systemctl status mysql --no-pager
systemctl status php8.3-fpm --no-pager</code></pre>
              </div>
              <div class="explain">
                <p><b>Why:</b> confirms your core stack is running.</p>
              </div>
            </div>

            <div class="step-card">
              <h3 class="text-xl font-bold">Logs</h3>
              <div class="cmd-block" data-cmdblock="p11-logs">
                <div class="cmd-toolbar">
                  <div class="cmd-title mono">Commands</div>
                  <button class="btn mono copy-btn" data-copy-target="p11-logs">Copy</button>
                </div>
                <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo tail -n 80 /var/log/nginx/error.log
sudo tail -n 80 /var/log/nginx/access.log
tail -n 80 /var/www/app/storage/logs/laravel.log</code></pre>
              </div>
              <div class="explain">
                <p><b>Nginx logs:</b> request routing and server errors.</p>
                <p><b>Laravel log:</b> application exceptions.</p>
              </div>
            </div>

            <div class="step-card">
              <h3 class="text-xl font-bold">PHP-FPM Socket Check</h3>
              <div class="cmd-block" data-cmdblock="p11-socket">
                <div class="cmd-toolbar">
                  <div class="cmd-title mono">Command</div>
                  <button class="btn mono copy-btn" data-copy-target="p11-socket">Copy</button>
                </div>
                <pre class="mono whitespace-pre-wrap leading-relaxed"><code>ls /run/php/</code></pre>
              </div>
              <div class="explain">
                <p><b>Why:</b> ensure Nginx points to the correct socket (commonly <span class="mono">php8.3-fpm.sock</span>).</p>
              </div>
            </div>

            <div class="step-card">
              <h3 class="text-xl font-bold">Permissions Fix (Most Common)</h3>
              <div class="cmd-block warn" data-cmdblock="p11-perms">
                <div class="cmd-toolbar">
                  <div class="cmd-title mono">Commands</div>
                  <button class="btn mono copy-btn" data-copy-target="p11-perms">Copy</button>
                </div>
                <pre class="mono whitespace-pre-wrap leading-relaxed"><code>sudo chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache
sudo chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache</code></pre>
              </div>
              <div class="explain">
                <p><b>Why:</b> prevents “Permission denied” when Laravel writes logs/cache.</p>
              </div>
            </div>
          </div>

          <div class="step-card mt-6">
            <div class="flex items-center justify-between gap-4">
              <h3 class="text-xl font-bold">Final Checklist</h3>
              <span class="tag mono">wrap-up</span>
            </div>

            <div class="mt-5 space-y-3">
              <label class="checkline">
                <input type="checkbox" data-check="p11-1" data-phase="p11" />
                <div>
                  <div class="checktext">App loads in browser</div>
                  <div class="checksub">Homepage renders without 404/502.</div>
                </div>
              </label>

              <label class="checkline">
                <input type="checkbox" data-check="p11-2" data-phase="p11" />
                <div>
                  <div class="checktext">Database connected</div>
                  <div class="checksub">Migrations run and app reads data.</div>
                </div>
              </label>

              <label class="checkline">
                <input type="checkbox" data-check="p11-3" data-phase="p11" />
                <div>
                  <div class="checktext">HTTPS working (if domain)</div>
                  <div class="checksub">Certificate valid and auto-renew configured.</div>
                </div>
              </label>

              <label class="checkline">
                <input type="checkbox" data-check="p11-4" data-phase="p11" />
                <div>
                  <div class="checktext">Scheduler/Queues configured (if used)</div>
                  <div class="checksub">Cron and Supervisor running properly.</div>
                </div>
              </label>
            </div>
          </div>
        </section>

        <!-- Footer -->
        <footer class="py-10 border-t border-white/10 flex flex-col md:flex-row justify-between gap-10">
          <div class="space-y-4 max-w-sm">
            <div class="flex items-center gap-2">
              <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
              <span class="font-bold text-xs uppercase tracking-widest">System Operational</span>
            </div>
            <p class="text-slate-500 text-sm">
              Deployment manual embedded for LaraDeploy-24. Always-visible UI, copy-to-clipboard command blocks, and checklist progress saved in browser.
            </p>
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

      </main>
    </div>
  </div>

  <script>
    // ===== Cursor (no opacity changes anywhere) =====
    const cursor = document.getElementById('cursor');
    window.addEventListener('mousemove', (e) => {
      gsap.to(cursor, { x: e.clientX, y: e.clientY, duration: 0.08, overwrite: true });
    });

    // ===== ThreeJS Background Particles =====
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.getElementById('canvas-container').appendChild(renderer.domElement);

    const particlesGeometry = new THREE.BufferGeometry();
    const posArray = new Float32Array(3000 * 3);
    for (let i = 0; i < 9000; i++) posArray[i] = (Math.random() - 0.5) * 10;
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

    window.addEventListener("resize", () => {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    });

    // ===== Copy-to-clipboard for each cmd-block =====
    const toast = document.getElementById("toast");
    const toastText = document.getElementById("toastText");
    let toastTimer = null;

    function showToast(msg) {
      toastText.textContent = msg;
      toast.classList.add("show");
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => toast.classList.remove("show"), 1400);
    }

    function getTextFromCmdBlock(id) {
      const block = document.querySelector(`[data-cmdblock="${id}"]`);
      if (!block) return "";
      const codeEl = block.querySelector("code");
      return (codeEl ? codeEl.innerText : "").trim();
    }

    async function copyText(text) {
      try {
        await navigator.clipboard.writeText(text);
        showToast("Copied to clipboard.");
      } catch (e) {
        // fallback
        const ta = document.createElement("textarea");
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand("copy");
        ta.remove();
        showToast("Copied (fallback).");
      }
    }

    document.querySelectorAll(".copy-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const target = btn.getAttribute("data-copy-target");
        const text = getTextFromCmdBlock(target);
        if (!text) return showToast("Nothing to copy.");
        copyText(text);
      });
    });

    // ===== Checklist mode (saved in localStorage) =====
    const STORAGE_KEY = "laradeploy24_checklist_v1";

    function loadState() {
      try {
        return JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}");
      } catch {
        return {};
      }
    }
    function saveState(state) {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
    }

    function computePhaseProgress(phaseId, state) {
      const checks = Array.from(document.querySelectorAll(`input[type="checkbox"][data-phase="${phaseId}"]`));
      if (checks.length === 0) return { done: 0, total: 0, pct: 0 };
      const done = checks.filter(c => state[c.dataset.check] === true).length;
      const total = checks.length;
      const pct = Math.round((done / total) * 100);
      return { done, total, pct };
    }

    function updateTOCState(state) {
      const tocItems = document.querySelectorAll(".toc-item");
      tocItems.forEach(item => {
        const phase = item.getAttribute("data-phase");
        const progress = computePhaseProgress(phase, state);

        const pctEl = document.getElementById(`toc-${phase}`);
        if (pctEl) pctEl.textContent = progress.total ? `${progress.pct}%` : "—";

        if (progress.total && progress.pct === 100) item.classList.add("done");
        else item.classList.remove("done");
      });
    }

    function hydrateChecklist() {
      const state = loadState();

      document.querySelectorAll('input[type="checkbox"][data-check]').forEach(cb => {
        cb.checked = state[cb.dataset.check] === true;

        cb.addEventListener("change", () => {
          const newState = loadState();
          newState[cb.dataset.check] = cb.checked;
          saveState(newState);
          updateTOCState(newState);
        });
      });

      updateTOCState(state);
    }

    document.getElementById("resetChecklist").addEventListener("click", () => {
      localStorage.removeItem(STORAGE_KEY);
      document.querySelectorAll('input[type="checkbox"][data-check]').forEach(cb => cb.checked = false);
      updateTOCState({});
      showToast("Checklist reset.");
    });

    hydrateChecklist();

    // ===== Optional: smooth anchor scrolling (no scroll trigger) =====
    document.querySelectorAll('a[href^="#p"]').forEach(a => {
      a.addEventListener("click", (e) => {
        const id = a.getAttribute("href");
        const el = document.querySelector(id);
        if (!el) return;
        e.preventDefault();
        el.scrollIntoView({ behavior: "smooth", block: "start" });
      });
    });
  </script>
</body>
</html>