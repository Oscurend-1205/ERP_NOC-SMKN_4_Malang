<!-- Floating Accessibility Button (Fullscreen Toggle) -->
<button id="fullscreenToggleBtn" class="fixed bottom-6 right-6 z-9999 flex items-center justify-center w-10 h-10 rounded-full bg-[#005bbf] text-white shadow-lg hover:bg-[#004494] active:scale-95 transition-all duration-200 cursor-pointer focus:outline-none focus:ring-4 focus:ring-blue-300" aria-label="Toggle Fullscreen" title="Toggle Fullscreen">
    <span class="material-symbols-outlined text-[20px]">fullscreen</span>
</button>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('fullscreenToggleBtn');
        if (btn) {
            const icon = btn.querySelector('.material-symbols-outlined');
            
            // Check if fullscreen was enabled in localStorage
            const isFullscreen = localStorage.getItem('fullscreenEnabled') === 'true';

            function updateUI(fullscreen) {
                if (fullscreen) {
                    if (icon) icon.textContent = 'fullscreen_exit';
                    btn.title = 'Exit Fullscreen';
                } else {
                    if (icon) icon.textContent = 'fullscreen';
                    btn.title = 'Enter Fullscreen';
                }
            }

            function toggleFullscreen() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen().then(() => {
                        localStorage.setItem('fullscreenEnabled', 'true');
                        updateUI(true);
                    }).catch(err => {
                        console.error(`Error attempting to enable fullscreen: ${err.message}`);
                    });
                } else {
                    document.exitFullscreen().then(() => {
                        localStorage.setItem('fullscreenEnabled', 'false');
                        updateUI(false);
                    });
                }
            }

            // Attempt to auto-fullscreen if it was enabled
            // Note: Most browsers require user interaction to enter fullscreen.
            // We'll try it once, but it might be blocked.
            if (isFullscreen && !document.fullscreenElement) {
                // We'll add a one-time click listener to the document to enter fullscreen
                // on the first user interaction if it was supposed to be fullscreen.
                const autoFullscreen = () => {
                    document.documentElement.requestFullscreen().then(() => {
                        updateUI(true);
                        document.removeEventListener('click', autoFullscreen);
                    }).catch(() => {
                        // Keep trying on next click if failed (maybe not needed)
                    });
                };
                document.addEventListener('click', autoFullscreen);
            }

            btn.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent the document click listener from firing
                toggleFullscreen();
            });

            // Listen for fullscreen change events (e.g. if exited via Escape key)
            document.addEventListener('fullscreenchange', () => {
                if (document.fullscreenElement) {
                    localStorage.setItem('fullscreenEnabled', 'true');
                    updateUI(true);
                } else {
                    localStorage.setItem('fullscreenEnabled', 'false');
                    updateUI(false);
                }
            });

            // Initial UI update
            updateUI(document.fullscreenElement || isFullscreen);
        }
    });
</script>
