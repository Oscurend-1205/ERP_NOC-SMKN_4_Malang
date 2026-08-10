<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Traceroute v1.0</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        "colors": {
                "on-tertiary-fixed": "#1a1c1c",
                "on-primary-fixed": "#002020",
                "tertiary-fixed": "#e2e2e2",
                "tertiary": "#ffffff",
                "surface-bright": "#393939",
                "surface-dim": "#131313",
                "outline-variant": "#3a4a49",
                "on-surface": "#e2e2e2",
                "surface": "#131313",
                "error-container": "#93000a",
                "primary-fixed": "#00fbfb",
                "on-error": "#690005",
                "secondary-container": "#474746",
                "on-secondary-container": "#b7b5b4",
                "tertiary-container": "#e2e2e2",
                "surface-tint": "#00dddd",
                "inverse-on-surface": "#303030",
                "primary-container": "#00fbfb",
                "surface-container-lowest": "#0e0e0e",
                "on-background": "#e2e2e2",
                "inverse-surface": "#e2e2e2",
                "on-secondary": "#313030",
                "surface-container-low": "#1b1b1b",
                "on-tertiary": "#2f3131",
                "secondary": "#c8c6c5",
                "surface-container-highest": "#353535",
                "primary-fixed-dim": "#00dddd",
                "inverse-primary": "#006a6a",
                "secondary-fixed": "#e5e2e1",
                "on-primary-container": "#007070",
                "on-primary": "#003737",
                "on-primary-fixed-variant": "#004f4f",
                "surface-variant": "#353535",
                "on-tertiary-fixed-variant": "#454747",
                "error": "#ffb4ab",
                "on-error-container": "#ffdad6",
                "primary": "#ffffff",
                "background": "#131313",
                "tertiary-fixed-dim": "#c6c6c7",
                "on-surface-variant": "#b9cac9",
                "on-secondary-fixed": "#1c1b1b",
                "surface-container": "#1f1f1f",
                "outline": "#839493",
                "on-tertiary-container": "#636565",
                "secondary-fixed-dim": "#c8c6c5",
                "on-secondary-fixed-variant": "#474746",
                "surface-container-high": "#2a2a2a"
        },
        "borderRadius": {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "full": "9999px"
        },
        "spacing": {
                "container-padding": "24px",
                "row-height-sm": "32px",
                "row-height-md": "48px",
                "gutter": "16px",
                "unit": "4px"
        },
        "fontFamily": {
                "display-lg": [
                        "JetBrains Mono"
                ],
                "body-lg": [
                        "JetBrains Mono"
                ],
                "body-sm": [
                        "JetBrains Mono"
                ],
                "code-label": [
                        "JetBrains Mono"
                ],
                "headline-md": [
                        "JetBrains Mono"
                ]
        },
        "fontSize": {
                "display-lg-mobile": [
                        "24px",
                        {
                                "lineHeight": "32px",
                                "letterSpacing": "-0.02em",
                                "fontWeight": "700"
                        }
                ],
                "display-lg": [
                        "32px",
                        {
                                "lineHeight": "40px",
                                "letterSpacing": "-0.02em",
                                "fontWeight": "700"
                        }
                ],
                "body-lg": [
                        "16px",
                        {
                                "lineHeight": "24px",
                                "letterSpacing": "0em",
                                "fontWeight": "400"
                        }
                ],
                "body-sm": [
                        "13px",
                        {
                                "lineHeight": "20px",
                                "letterSpacing": "0.02em",
                                "fontWeight": "400"
                        }
                ],
                "code-label": [
                        "11px",
                        {
                                "lineHeight": "16px",
                                "letterSpacing": "0.08em",
                                "fontWeight": "500"
                        }
                ],
                "headline-md": [
                        "20px",
                        {
                                "lineHeight": "28px",
                                "letterSpacing": "0em",
                                "fontWeight": "600"
                        }
                ]
        }
},
    },
  }
</script>
<style>
  body {
    background-color: #000000; /* Deep black overriding config background for this specific intent */
    color: #00ffff; /* Electric cyan overriding generic text colors */
    font-family: 'JetBrains Mono', monospace;
  }
  
  /* Input placeholder styling to match theme */
  ::placeholder {
    color: #00ffff;
    opacity: 0.5;
  }
  
  /* Custom blinking cursor effect for inputs */
  .terminal-cursor {
    animation: blink 1s step-end infinite;
  }
  @keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
  }
  
  /* Minimalist scrollbar */
  ::-webkit-scrollbar {
    width: 4px;
  }
  ::-webkit-scrollbar-track {
    background: #000000;
  }
  ::-webkit-scrollbar-thumb {
    background: #00ffff;
  }
</style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>
<body class="min-h-screen flex flex-col antialiased selection:bg-primary-fixed selection:text-on-primary-fixed">
<!-- TopAppBar -->
<header class="w-full top-0 flex items-center px-container-padding h-row-height-md w-full bg-background dark:bg-background border-b border-outline-variant flat no shadows transition-colors duration-75 sticky z-10">
<div class="flex items-center gap-gutter w-full">
<span class="material-symbols-outlined text-primary-fixed dark:text-primary-fixed">terminal</span>
<h1 class="font-display-lg-mobile text-display-lg-mobile text-primary-fixed uppercase tracking-tighter w-full">TRACEROUTE v1.0</h1>
</div>
</header>
<!-- Main Canvas -->
<main class="flex-1 flex flex-col p-unit px-unit md:px-container-padding max-w-3xl mx-auto w-full pb-24 md:pb-8">
<!-- Input Section -->
<section class="mt-gutter mb-gutter px-unit">
<div class="flex flex-col gap-unit">
<label class="font-code-label text-code-label uppercase flex items-center gap-2" for="target-ip">
<span>&gt; TARGET IP:</span>
</label>
<div class="flex flex-col sm:flex-row gap-gutter">
<div class="relative flex-1 group">
<input class="w-full bg-transparent border-0 border-b border-primary-fixed text-[#00ffff] font-body-lg text-body-lg focus:ring-0 focus:border-primary-fixed p-0 py-2 rounded-none placeholder-primary-fixed/50" id="target-ip" placeholder="8.8.8.8" type="text" value="{{ $targetIp ?? '8.8.8.8' }}" readonly/>
<span class="absolute right-0 bottom-2 text-primary-fixed terminal-cursor opacity-0 group-focus-within:opacity-100">_</span>
</div>
<span class="font-code-label text-code-label uppercase px-gutter py-2 border border-primary-fixed/50 text-primary-fixed/70 whitespace-nowrap self-start sm:self-end mt-unit sm:mt-0">
            [ AUTO-TRACE ACTIVE ]
          </span>
</div>
</div>
</section>
<!-- Divider -->
<div class="px-unit mb-gutter">
<div class="text-[#00ffff] font-code-label text-code-label opacity-70 tracking-widest break-all whitespace-pre-wrap">
+--------------------------------------------------+
      </div>
</div>
<!-- Output Area -->
<section class="flex-1 flex flex-col px-unit overflow-y-auto">
<div class="text-[#00ffff] flex flex-col font-body-sm text-body-sm tracking-tight leading-relaxed pb-8">
<!-- Header -->
<div class="flex border-b border-[#1A1A1A] pb-unit mb-unit font-code-label text-code-label uppercase opacity-80">
<div class="w-8 shrink-0">#</div>
<div class="flex-1 min-w-[120px]">IP</div>
<div class="w-16 shrink-0 text-right pr-4">LATENCY</div>
<div class="flex-1 hidden sm:block truncate">LOC</div>
</div>
<div id="trace-output"></div>
<!-- Status Footer -->
<div id="trace-status" class="mt-gutter flex items-center gap-2 font-code-label text-code-label opacity-0">
<span class="animate-pulse">&gt;</span>
<span id="trace-status-text" class="uppercase"></span>
</div>
</div>
</section>
</main>
<!-- BottomNavBar (Mobile Only) -->
<nav class="fixed bottom-0 left-0 w-full flex justify-around items-center h-row-height-md bg-background dark:bg-background border-t border-outline-variant z-50 md:hidden flat no shadows">
<!-- LAN (Active) -->
<button class="flex flex-col items-center justify-center bg-primary-fixed text-on-primary-fixed p-unit w-full h-full scale-95 duration-100 hover:bg-primary-fixed-dim hover:text-on-primary-fixed">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">lan</span>
<span class="font-code-label text-code-label mt-1 hidden">LAN</span>
</button>
<!-- HISTORY -->
<button class="flex flex-col items-center justify-center text-primary-fixed p-unit w-full h-full scale-95 duration-100 hover:bg-primary-fixed-dim hover:text-on-primary-fixed">
<span class="material-symbols-outlined">history</span>
<span class="font-code-label text-code-label mt-1 hidden">History</span>
</button>
<!-- SETTINGS ETHERNET -->
<button class="flex flex-col items-center justify-center text-primary-fixed p-unit w-full h-full scale-95 duration-100 hover:bg-primary-fixed-dim hover:text-on-primary-fixed">
<span class="material-symbols-outlined">settings_ethernet</span>
<span class="font-code-label text-code-label mt-1 hidden">Network</span>
</button>
<!-- INFO -->
<button class="flex flex-col items-center justify-center text-primary-fixed p-unit w-full h-full scale-95 duration-100 hover:bg-primary-fixed-dim hover:text-on-primary-fixed">
<span class="material-symbols-outlined">info</span>
<span class="font-code-label text-code-label mt-1 hidden">Info</span>
</button>
</nav>
<!-- Desktop Sidebar (Hidden on Mobile) -->
<aside class="hidden md:flex fixed left-0 top-row-height-md bottom-0 w-[60px] border-r border-[#1A1A1A] flex-col items-center py-container-padding gap-8 bg-background z-10">
<button class="text-on-primary-fixed bg-primary-fixed p-2 rounded-none hover:bg-primary-fixed-dim hover:text-on-primary-fixed transition-colors">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">lan</span>
</button>
<button class="text-primary-fixed p-2 rounded-none hover:bg-primary-fixed-dim hover:text-on-primary-fixed transition-colors">
<span class="material-symbols-outlined">history</span>
</button>
<button class="text-primary-fixed p-2 rounded-none hover:bg-primary-fixed-dim hover:text-on-primary-fixed transition-colors">
<span class="material-symbols-outlined">settings_ethernet</span>
</button>
<div class="mt-auto pb-4">
<button class="text-primary-fixed p-2 rounded-none hover:bg-primary-fixed-dim hover:text-on-primary-fixed transition-colors">
<span class="material-symbols-outlined">info</span>
</button>
</div>
</aside>
<!-- Adjust main layout for desktop sidebar -->
<style>
    @media (min-width: 768px) {
      body {
        padding-left: 60px;
      }
      header {
        margin-left: -60px;
        width: calc(100% + 60px);
        padding-left: calc(60px + 24px);
      }
    }
  </style>
<script>
  window.onload = function () {
    const hops = @json($hops ?? []);
    const targetIp = @json($targetIp ?? '8.8.8.8');
    const outputEl = document.getElementById('trace-output');
    const statusEl = document.getElementById('trace-status');
    const statusTextEl = document.getElementById('trace-status-text');

    if (!outputEl || !hops.length) {
      return;
    }

    function buildHopRow(hop) {
      const isTarget = hop.ip === targetIp;
      const row = document.createElement('div');
      row.className = 'flex py-1 border-b border-[#1A1A1A] hover:bg-[#00ffff] hover:text-[#000000] group cursor-default opacity-0';
      row.innerHTML =
        '<div class="w-8 shrink-0 opacity-70 group-hover:opacity-100 hop-num"></div>' +
        '<div class="flex-1 min-w-[120px] hop-ip' + (isTarget ? ' font-bold' : '') + '"></div>' +
        '<div class="w-16 shrink-0 text-right pr-4 opacity-80 group-hover:opacity-100 hop-latency"></div>' +
        '<div class="flex-1 hidden sm:block truncate opacity-70 group-hover:opacity-100 hop-loc"></div>';
      return row;
    }

    function typeText(element, text, speed, callback) {
      let index = 0;
      function tick() {
        if (index < text.length) {
          element.textContent += text.charAt(index);
          index++;
          setTimeout(tick, speed);
        } else if (callback) {
          callback();
        }
      }
      tick();
    }

    function revealRow(row, hop, callback) {
      row.style.opacity = '1';
      const numEl = row.querySelector('.hop-num');
      const ipEl = row.querySelector('.hop-ip');
      const latEl = row.querySelector('.hop-latency');
      const locEl = row.querySelector('.hop-loc');

      typeText(numEl, String(hop.hop), 25, function () {
        typeText(ipEl, hop.ip, 18, function () {
          typeText(latEl, hop.latency, 20, function () {
            typeText(locEl, hop.location, 12, callback);
          });
        });
      });
    }

    function typeStatusMessage(message, callback) {
      statusEl.style.opacity = '1';
      statusTextEl.textContent = '';
      typeText(statusTextEl, message, 35, callback);
    }

    let rowIndex = 0;

    function renderNextHop() {
      if (rowIndex >= hops.length) {
        typeStatusMessage('Trace Complete.');
        return;
      }

      const hop = hops[rowIndex];
      const row = buildHopRow(hop);
      outputEl.appendChild(row);

      revealRow(row, hop, function () {
        rowIndex++;
        setTimeout(renderNextHop, 120);
      });
    }

    renderNextHop();
  };
</script>
</body></html>