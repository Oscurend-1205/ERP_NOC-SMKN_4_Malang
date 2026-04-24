/**
 * Dashboard Logic & Configuration - ERP NOC SMKN 4 Malang
 * Material Design 3 Color Tokens & System Logic (Turbo Compatible)
 */

// Tailwind Configuration (MD3 Standard)
if (window.tailwind) {
    window.tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#005bbf",
                    "on-primary": "#ffffff",
                    "primary-container": "#1a73e8",
                    "on-primary-container": "#ffffff",
                    "secondary": "#006e2c",
                    "on-secondary": "#ffffff",
                    "secondary-container": "#86f898",
                    "on-secondary-container": "#00722f",
                    "tertiary": "#795900",
                    "on-tertiary": "#ffffff",
                    "tertiary-container": "#987000",
                    "on-tertiary-container": "#ffffff",
                    "error": "#ba1a1a",
                    "on-error": "#ffffff",
                    "error-container": "#ffdad6",
                    "on-error-container": "#93000a",
                    "background": "#f7f9ff",
                    "on-background": "#181c20",
                    "surface": "#f7f9ff",
                    "on-surface": "#181c20",
                    "surface-variant": "#dfe3e8",
                    "on-surface-variant": "#414754",
                    "outline": "#727785",
                    "outline-variant": "#c1c6d6",
                    "surface-container-low": "#f1f4fa",
                    "surface-container": "#ebeef4",
                    "surface-container-high": "#e5e8ee",
                    "surface-container-highest": "#dfe3e8",
                },
                spacing: {
                    lg: "24px",
                    xl: "32px",
                },
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                }
            },
        },
    };
}

// Realtime Clock Function
function updateRealtimeClock() {
    const clockElement = document.getElementById('realtime-clock-display');
    if (!clockElement) return;

    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    clockElement.textContent = `${hours}:${minutes}`;
}

// Variable to store interval to prevent double execution
let clockInterval = null;

function initApp() {
    updateRealtimeClock();
    if (clockInterval) clearInterval(clockInterval);
    clockInterval = setInterval(updateRealtimeClock, 1000);

    // Helper for Material Icons (Delayed to ensure DOM is ready)
    setTimeout(() => {
        document.querySelectorAll('.material-symbols-outlined').forEach(span => {
            if (!span.textContent.trim()) {
                const iconName = span.getAttribute('data-icon');
                if (iconName) span.textContent = iconName;
            }
        });
    }, 50);
}

// Initialize on Standard Load and Turbo Load
document.addEventListener('DOMContentLoaded', initApp);
document.addEventListener('turbo:load', initApp);
