<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>User Access Log - TRACEROUTE v1.0</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "outline-variant": "#3a4a49",
                      "on-secondary-fixed": "#1c1b1b",
                      "on-surface": "#e2e2e2",
                      "outline": "#839493",
                      "on-secondary": "#313030",
                      "inverse-on-surface": "#303030",
                      "background": "#131313",
                      "surface-container-high": "#2a2a2a",
                      "on-tertiary": "#2f3131",
                      "secondary-fixed-dim": "#c8c6c5",
                      "on-background": "#e2e2e2",
                      "tertiary-fixed-dim": "#c6c6c7",
                      "surface-container-lowest": "#0e0e0e",
                      "on-primary": "#003737",
                      "on-error-container": "#ffdad6",
                      "tertiary": "#ffffff",
                      "primary-fixed": "#00fbfb",
                      "secondary-fixed": "#e5e2e1",
                      "on-primary-fixed": "#002020",
                      "on-primary-container": "#007070",
                      "error": "#ffb4ab",
                      "on-tertiary-fixed-variant": "#454747",
                      "inverse-surface": "#e2e2e2",
                      "error-container": "#93000a",
                      "on-tertiary-fixed": "#1a1c1c",
                      "surface-container-low": "#1b1b1b",
                      "surface-bright": "#393939",
                      "on-primary-fixed-variant": "#004f4f",
                      "primary-container": "#00fbfb",
                      "surface-container": "#1f1f1f",
                      "secondary-container": "#474746",
                      "primary": "#ffffff",
                      "tertiary-fixed": "#e2e2e2",
                      "on-surface-variant": "#b9cac9",
                      "on-error": "#690005",
                      "surface-dim": "#131313",
                      "primary-fixed-dim": "#00dddd",
                      "on-secondary-container": "#b7b5b4",
                      "surface-container-highest": "#353535",
                      "inverse-primary": "#006a6a",
                      "tertiary-container": "#e2e2e2",
                      "on-tertiary-container": "#636565",
                      "on-secondary-fixed-variant": "#474746",
                      "surface": "#131313",
                      "surface-variant": "#353535",
                      "surface-tint": "#00dddd",
                      "secondary": "#c8c6c5"
              },
              "borderRadius": {
                      "DEFAULT": "0.25rem",
                      "lg": "0.5rem",
                      "xl": "0.75rem",
                      "full": "9999px"
              },
              "spacing": {
                      "gutter": "16px",
                      "row-height-md": "48px",
                      "unit": "4px",
                      "row-height-sm": "32px",
                      "container-padding": "24px"
              },
              "fontFamily": {
                      "body-lg": ["JetBrains Mono"],
                      "code-label": ["JetBrains Mono"],
                      "body-sm": ["JetBrains Mono"],
                      "headline-md": ["JetBrains Mono"],
                      "display-lg": ["JetBrains Mono"]
              },
              "fontSize": {
                      "body-lg": ["16px", { "lineHeight": "24px", "letterSpacing": "0em", "fontWeight": "400" }],
                      "code-label": ["11px", { "lineHeight": "16px", "letterSpacing": "0.08em", "fontWeight": "500" }],
                      "body-sm": ["13px", { "lineHeight": "20px", "letterSpacing": "0.02em", "fontWeight": "400" }],
                      "headline-md": ["20px", { "lineHeight": "28px", "letterSpacing": "0em", "fontWeight": "600" }],
                      "display-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                      "display-lg-mobile": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.02em", "fontWeight": "700" }]
              }
            }
          }
        }
    </script>
<style>
        body {
            background-color: #131313;
            color: #00fbfb;
        }
        .ascii-divider {
            color: #1A1A1A;
            border-top: 1px solid #1A1A1A;
            width: 100%;
            margin: 8px 0;
        }
        .blinking-cursor {
            animation: blink 1s step-end infinite;
        }
        @keyframes blink {
            50% { opacity: 0; }
        }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>
<body class="font-body-lg text-body-lg flex flex-col min-h-screen">
<!-- TopAppBar -->
<header class="w-full top-0 border-b border-outline-variant bg-background text-primary-fixed transition-colors duration-75 flex items-center px-container-padding h-row-height-md">
<span class="material-symbols-outlined mr-2">terminal</span>
<h1 class="font-display-lg-mobile text-display-lg-mobile uppercase tracking-tighter">TRACEROUTE v1.0</h1>
</header>
<!-- Main Content -->
<main class="flex-grow p-container-padding pb-row-height-md overflow-y-auto">
<!-- Header / Context -->
<div class="mb-4">
<h2 class="font-headline-md text-headline-md mb-2">USER ACCESS LOG_ <span class="blinking-cursor">|</span></h2>
<div class="ascii-divider"></div>
</div>
<!-- Summary Counters -->
<div class="grid grid-cols-2 gap-4 mb-6">
<div class="border border-outline-variant p-2 flex flex-col">
<span class="font-code-label text-code-label text-on-surface-variant">ACTIVE SESSIONS</span>
<span class="font-display-lg text-display-lg text-primary-fixed">042</span>
</div>
<div class="border border-outline-variant p-2 flex flex-col">
<span class="font-code-label text-code-label text-on-surface-variant">TOTAL HITS</span>
<span class="font-display-lg text-display-lg text-primary-fixed">891</span>
</div>
</div>
<div class="ascii-divider mb-4"></div>
<!-- Log Stream -->
<div class="flex flex-col space-y-2 font-code-label text-code-label">
<!-- Log Entry 1 -->
<div class="flex flex-col border-b border-outline-variant pb-2">
<div class="flex justify-between items-center opacity-50 mb-1">
<span>[14:02:45]</span>
<span class="border border-primary-fixed px-1 text-[9px]">200 OK</span>
</div>
<div class="flex items-center space-x-2">
<span class="text-on-surface">192.168.1.5</span>
<span class="text-on-surface-variant">-&gt;</span>
<span class="text-primary-fixed bg-[#002020] px-1">/home</span>
</div>
</div>
<!-- Log Entry 2 -->
<div class="flex flex-col border-b border-outline-variant pb-2">
<div class="flex justify-between items-center opacity-50 mb-1">
<span>[14:02:48]</span>
<span class="border border-error px-1 text-[9px] text-error">403 FORBIDDEN</span>
</div>
<div class="flex items-center space-x-2">
<span class="text-on-surface">10.0.0.42</span>
<span class="text-on-surface-variant">-&gt;</span>
<span class="text-error bg-error-container px-1">/admin/config</span>
</div>
</div>
<!-- Log Entry 3 -->
<div class="flex flex-col border-b border-outline-variant pb-2">
<div class="flex justify-between items-center opacity-50 mb-1">
<span>[14:03:01]</span>
<span class="border border-primary-fixed px-1 text-[9px]">200 OK</span>
</div>
<div class="flex items-center space-x-2">
<span class="text-on-surface">172.16.254.1</span>
<span class="text-on-surface-variant">-&gt;</span>
<span class="text-primary-fixed bg-[#002020] px-1">/dashboard</span>
</div>
</div>
<!-- Log Entry 4 -->
<div class="flex flex-col border-b border-outline-variant pb-2">
<div class="flex justify-between items-center opacity-50 mb-1">
<span>[14:03:15]</span>
<span class="border border-primary-fixed px-1 text-[9px]">200 OK</span>
</div>
<div class="flex items-center space-x-2">
<span class="text-on-surface">192.168.1.5</span>
<span class="text-on-surface-variant">-&gt;</span>
<span class="text-primary-fixed bg-[#002020] px-1">/api/v1/status</span>
</div>
</div>
<!-- Log Entry 5 -->
<div class="flex flex-col border-b border-outline-variant pb-2">
<div class="flex justify-between items-center opacity-50 mb-1">
<span>[14:03:22]</span>
<span class="border border-outline px-1 text-[9px] text-outline">301 MOVED</span>
</div>
<div class="flex items-center space-x-2">
<span class="text-on-surface">8.8.8.8</span>
<span class="text-on-surface-variant">-&gt;</span>
<span class="text-outline px-1">/old-route</span>
</div>
</div>
</div>
</main>
<!-- BottomNavBar -->
<nav class="fixed bottom-0 left-0 w-full flex justify-around items-center h-row-height-md bg-background text-primary-fixed border-t border-outline-variant z-50 md:hidden">
<button class="flex flex-col items-center justify-center text-primary-fixed p-unit hover:bg-primary-fixed-dim hover:text-on-primary-fixed scale-95 duration-100 w-full h-full">
<span class="material-symbols-outlined">lan</span>
</button>
<button class="flex flex-col items-center justify-center bg-primary-fixed text-on-primary-fixed p-unit hover:bg-primary-fixed-dim hover:text-on-primary-fixed scale-95 duration-100 w-full h-full">
<span class="material-symbols-outlined">history</span>
</button>
<button class="flex flex-col items-center justify-center text-primary-fixed p-unit hover:bg-primary-fixed-dim hover:text-on-primary-fixed scale-95 duration-100 w-full h-full">
<span class="material-symbols-outlined">settings_ethernet</span>
</button>
<button class="flex flex-col items-center justify-center text-primary-fixed p-unit hover:bg-primary-fixed-dim hover:text-on-primary-fixed scale-95 duration-100 w-full h-full">
<span class="material-symbols-outlined">info</span>
</button>
</nav>
<!-- Desktop Sidebar (Hidden on mobile, showing context if scaled up) -->
<nav class="hidden md:flex fixed left-0 top-row-height-md h-full w-64 border-r border-outline-variant flex-col p-container-padding space-y-4">
<button class="flex items-center space-x-2 text-primary-fixed hover:bg-primary-fixed hover:text-on-primary-fixed p-2 transition-colors">
<span class="material-symbols-outlined">lan</span>
<span class="font-code-label text-code-label uppercase">[ NETWORK ]</span>
</button>
<button class="flex items-center space-x-2 bg-primary-fixed text-on-primary-fixed p-2 transition-colors">
<span class="material-symbols-outlined">history</span>
<span class="font-code-label text-code-label uppercase">[ ACCESS_LOG ]</span>
</button>
<button class="flex items-center space-x-2 text-primary-fixed hover:bg-primary-fixed hover:text-on-primary-fixed p-2 transition-colors">
<span class="material-symbols-outlined">settings_ethernet</span>
<span class="font-code-label text-code-label uppercase">[ ROUTES ]</span>
</button>
<button class="flex items-center space-x-2 text-primary-fixed hover:bg-primary-fixed hover:text-on-primary-fixed p-2 transition-colors">
<span class="material-symbols-outlined">info</span>
<span class="font-code-label text-code-label uppercase">[ SYS_INFO ]</span>
</button>
</nav>
</body></html>