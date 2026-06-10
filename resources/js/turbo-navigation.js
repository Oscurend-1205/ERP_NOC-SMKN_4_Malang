/**
 * Smooth Page Navigation (PJAX-style) - ERP NOC SMKN 4 Malang
 * 
 * Intercepts sidebar/navigation link clicks, fetches pages via AJAX,
 * and only swaps the content area — keeping sidebar & topbar intact.
 * Adds smooth CSS transitions, a top progress bar, and handles browser history.
 * 
 * No external dependencies required.
 */

(function() {
    'use strict';

    // ============================================
    // CONFIGURATION
    // ============================================
    const CONFIG = {
        // Selector for the content container to swap
        contentSelector: '#pjax-content',
        // Selector for the sidebar  
        sidebarSelector: '#mainSidebar',
        // Links that should use PJAX (Disabled globally by requiring data-pjax attribute)
        linkSelector: 'a[data-pjax="true"]',
        // Animation durations
        fadeOutDuration: 150,
        fadeInDuration: 300,
    };

    // ============================================
    // PROGRESS BAR
    // ============================================
    class ProgressBar {
        constructor() {
            this.element = null;
            this.fill = null;
            this._inject();
        }

        _inject() {
            // Inject CSS
            const style = document.createElement('style');
            style.textContent = `
                #pjax-progress {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 3px;
                    z-index: 99999;
                    pointer-events: none;
                    display: none;
                }
                #pjax-progress .pjax-progress-fill {
                    height: 100%;
                    width: 0%;
                    background: linear-gradient(90deg, #3B82F6, #60A5FA, #93C5FD);
                    border-radius: 0 2px 2px 0;
                    box-shadow: 0 0 10px rgba(59, 130, 246, 0.5), 0 0 5px rgba(59, 130, 246, 0.3);
                    transition: width 0.3s ease;
                }
                
                /* Page transition styles */
                #pjax-content {
                    transition: opacity ${CONFIG.fadeOutDuration}ms ease, transform ${CONFIG.fadeOutDuration}ms ease;
                }
                #pjax-content.pjax-fade-out {
                    opacity: 0.15;
                    transform: translateY(4px);
                }
                #pjax-content.pjax-fade-in {
                    animation: pjaxFadeIn ${CONFIG.fadeInDuration}ms ease forwards;
                }
                @keyframes pjaxFadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(10px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
            `;
            document.head.appendChild(style);

            // Create progress bar element
            this.element = document.createElement('div');
            this.element.id = 'pjax-progress';
            this.element.innerHTML = '<div class="pjax-progress-fill"></div>';
            document.body.prepend(this.element);
            this.fill = this.element.querySelector('.pjax-progress-fill');
        }

        start() {
            this.fill.style.transition = 'none';
            this.fill.style.width = '0%';
            this.fill.style.opacity = '1';
            this.element.style.display = 'block';

            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    this.fill.style.transition = 'width 12s cubic-bezier(0.1, 0.45, 0.25, 1)';
                    this.fill.style.width = '80%';
                });
            });
        }

        complete() {
            this.fill.style.transition = 'width 0.25s ease';
            this.fill.style.width = '100%';
            
            setTimeout(() => {
                this.fill.style.transition = 'opacity 0.35s ease';
                this.fill.style.opacity = '0';
                setTimeout(() => {
                    this.element.style.display = 'none';
                    this.fill.style.width = '0%';
                    this.fill.style.opacity = '1';
                }, 350);
            }, 250);
        }
    }

    // ============================================
    // PJAX NAVIGATION ENGINE
    // ============================================
    class PjaxNavigator {
        constructor() {
            this.progressBar = new ProgressBar();
            this.currentRequest = null;
            this.isNavigating = false;
            this._bindEvents();
            this._initialAnimation();
        }

        _initialAnimation() {
            const content = document.querySelector(CONFIG.contentSelector);
            if (content) {
                content.classList.add('pjax-fade-in');
                content.addEventListener('animationend', () => {
                    content.classList.remove('pjax-fade-in');
                }, { once: true });
            }
        }

        _bindEvents() {
            // Intercept clicks on navigation links
            document.addEventListener('click', (e) => {
                const link = e.target.closest(CONFIG.linkSelector);
                if (!link) return;
                
                const url = link.href;
                
                // Skip if it's a different origin
                if (new URL(url).origin !== window.location.origin) return;
                
                // Skip if it's a file download or special link
                if (link.hasAttribute('download')) return;
                
                // Skip form submit buttons
                if (link.closest('form')) return;

                // Skip logout and POST-action links
                if (url.includes('logout')) return;
                
                // Skip if modifier keys are pressed (open in new tab, etc.)
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                
                // Skip if current page (just a hash change or same URL)
                if (url === window.location.href) return;
                
                e.preventDefault();
                this.navigate(url, true);
            });

            // Handle browser back/forward buttons
            window.addEventListener('popstate', (e) => {
                if (e.state && e.state.pjax) {
                    this.navigate(window.location.href, false);
                } else {
                    // For non-pjax history entries, do a normal navigation
                    this.navigate(window.location.href, false);
                }
            });

            // Mark initial page state
            window.history.replaceState({ pjax: true, url: window.location.href }, '', window.location.href);
        }

        async navigate(url, pushState = true) {
            // Abort any ongoing request
            if (this.currentRequest) {
                this.currentRequest.abort();
            }

            if (this.isNavigating) return;
            this.isNavigating = true;

            const content = document.querySelector(CONFIG.contentSelector);
            if (!content) {
                // No PJAX content container found, do full page load
                window.location.href = url;
                return;
            }

            // Start progress bar and fade out content
            this.progressBar.start();
            content.classList.add('pjax-fade-out');

            try {
                const controller = new AbortController();
                this.currentRequest = controller;

                // Wait for fade out
                await this._delay(CONFIG.fadeOutDuration);

                // Fetch the new page
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-PJAX': 'true',
                    },
                    signal: controller.signal,
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                // Check for redirects (e.g., auth redirect to login)
                const finalUrl = response.url;
                if (new URL(finalUrl).pathname.includes('login')) {
                    window.location.href = finalUrl;
                    return;
                }

                const html = await response.text();
                
                // Parse the response HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Find the new content
                const newContent = doc.querySelector(CONFIG.contentSelector);
                
                if (!newContent) {
                    // Page doesn't have PJAX container, do full load
                    window.location.href = url;
                    return;
                }

                // Swap the content
                content.innerHTML = newContent.innerHTML;

                // Clear PJAX init guards so page scripts re-initialize
                delete content.dataset.itemsInitialized;
                delete content.dataset.pageInitialized;

                // Update page title
                const newTitle = doc.querySelector('title');
                if (newTitle) {
                    document.title = newTitle.textContent;
                }

                // Update meta CSRF token
                const newCsrf = doc.querySelector('meta[name="csrf-token"]');
                const oldCsrf = document.querySelector('meta[name="csrf-token"]');
                if (newCsrf && oldCsrf) {
                    oldCsrf.setAttribute('content', newCsrf.getAttribute('content'));
                }

                // Push to browser history
                if (pushState) {
                    window.history.pushState({ pjax: true, url: url }, '', url);
                }

                // Execute inline scripts from new content
                this._executeScripts(content);

                // Update sidebar active states
                this._updateSidebarActive();

                // Animate content in
                content.classList.remove('pjax-fade-out');
                content.classList.add('pjax-fade-in');
                content.addEventListener('animationend', () => {
                    content.classList.remove('pjax-fade-in');
                }, { once: true });

                // Complete progress bar
                this.progressBar.complete();

                // Scroll to top of content area
                const mainArea = content.closest('main') || content;
                mainArea.scrollTop = 0;

                // Dispatch custom event
                document.dispatchEvent(new CustomEvent('pjax:complete', { detail: { url } }));

            } catch (error) {
                if (error.name === 'AbortError') {
                    // Navigation was cancelled, ignore
                    return;
                }
                
                console.warn('[PJAX] Navigation failed, falling back to full page load:', error.message);
                this.progressBar.complete();
                window.location.href = url;
            } finally {
                this.isNavigating = false;
                this.currentRequest = null;
            }
        }

        _executeScripts(container) {
            // Find all script tags in the new content and execute them
            const scripts = container.querySelectorAll('script');
            scripts.forEach(oldScript => {
                if (oldScript.src) {
                    // External script — only load if not already loaded
                    if (!document.querySelector(`script[src="${oldScript.src}"]`)) {
                        const newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => {
                            newScript.setAttribute(attr.name, attr.value);
                        });
                        newScript.src = oldScript.src;
                        document.body.appendChild(newScript);
                    }
                } else {
                    // Inline script — execute directly via eval for PJAX reliability
                    try {
                        const code = oldScript.textContent;
                        if (code && code.trim()) {
                            (new Function(code))();
                        }
                    } catch(e) {
                        console.error('[PJAX] Script execution error:', e);
                    }
                }
            });

            // Re-initialize Lucide icons (loaded globally in layout)
            // Use requestAnimationFrame to ensure DOM is painted before icon init
            requestAnimationFrame(() => {
                if (typeof lucide !== 'undefined' && lucide.createIcons) {
                    try { lucide.createIcons(); } catch(e) {}
                }
            });
        }

        _updateSidebarActive() {
            const currentPath = window.location.pathname;
            const sidebar = document.querySelector(CONFIG.sidebarSelector);
            if (!sidebar) return;

            const navContainer = sidebar.querySelector('#sidebar-nav-container');
            if (!navContainer) return;

            // Get all nav links (not the sub-menu links or buttons)
            const mainLinks = navContainer.querySelectorAll(':scope > a');
            const subLinks = document.querySelectorAll('#sub-data-master a');

            // Clear all active states on main links
            mainLinks.forEach(link => {
                link.classList.remove('bg-[#3A3D5C]', 'font-medium');
                link.classList.add('text-gray-400', 'hover:bg-white/5', 'hover:text-white');
                link.classList.remove('text-white');
            });

            // Clear active states on sub-menu links
            subLinks.forEach(link => {
                link.classList.remove('text-white', 'font-bold');
                link.classList.add('text-gray-400');
            });

            // Reset Data Master button
            const btnDataMaster = document.getElementById('btn-data-master');
            if (btnDataMaster) {
                btnDataMaster.classList.remove('text-white', 'font-medium');
                btnDataMaster.classList.add('text-gray-400');
            }

            // Set new active state
            let foundInSubMenu = false;

            // Check sub-menu items first
            subLinks.forEach(link => {
                const linkPath = new URL(link.href, window.location.origin).pathname;
                if (currentPath === linkPath || (linkPath !== '/' && currentPath.startsWith(linkPath))) {
                    link.classList.remove('text-gray-400');
                    link.classList.add('text-white', 'font-bold');
                    foundInSubMenu = true;
                }
            });

            // If found in sub-menu, expand Data Master dropdown
            if (foundInSubMenu) {
                const subMenu = document.getElementById('sub-data-master');
                const icon = document.getElementById('icon-data-master');
                if (subMenu) {
                    subMenu.classList.remove('hidden');
                    subMenu.classList.add('flex');
                }
                if (icon) icon.style.transform = 'rotate(180deg)';
                if (btnDataMaster) {
                    btnDataMaster.classList.remove('text-gray-400');
                    btnDataMaster.classList.add('text-white', 'font-medium');
                }
            } else {
                // Close data master dropdown if not in sub-menu
                const subMenu = document.getElementById('sub-data-master');
                const icon = document.getElementById('icon-data-master');
                if (subMenu && !subMenu.classList.contains('hidden')) {
                    subMenu.classList.add('hidden');
                    subMenu.classList.remove('flex');
                }
                if (icon) icon.style.transform = 'rotate(0deg)';
            }

            // Check main links
            mainLinks.forEach(link => {
                const linkPath = new URL(link.href, window.location.origin).pathname;
                const isActive = (currentPath === linkPath) || 
                    (linkPath !== '/' && currentPath.startsWith(linkPath));
                
                if (isActive) {
                    link.classList.remove('text-gray-400', 'hover:bg-white/5', 'hover:text-white');
                    link.classList.add('bg-[#3A3D5C]', 'text-white', 'font-medium');
                }
            });
        }

        _delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
    }

    // ============================================
    // INITIALIZE
    // ============================================
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => new PjaxNavigator());
    } else {
        new PjaxNavigator();
    }

    console.log('[ERP NOC] ✨ Smooth navigation system active');

})();
