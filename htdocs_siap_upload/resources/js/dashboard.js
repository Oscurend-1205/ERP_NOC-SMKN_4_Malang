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
                    "secondary": "#2563eb",
                    "on-secondary": "#ffffff",
                    "secondary-container": "#dbeafe",
                    "on-secondary-container": "#1e3a8a",
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
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    clockElement.textContent = `${hours}:${minutes}:${seconds}`;
}

// Operational Status Function
function updateOperationalStatus() {
    const statusElement = document.getElementById('operational-status');
    if (!statusElement) {
        console.log("Operational status element not found");
        return;
    }

    const now = new Date();
    const hour = now.getHours();
    console.log("Current hour for status:", hour);

    // Operational Hours: 06:00 - 15:00
    if (hour >= 6 && hour < 15) {
        statusElement.textContent = "open";
        statusElement.style.color = "#4ade80"; // Tailwind green-400
    } else {
        statusElement.textContent = "closed";
        statusElement.style.color = "#f87171"; // Tailwind red-400
    }
}

// Variable to store interval to prevent double execution
let clockInterval = null;

function initApp() {
    updateRealtimeClock();
    updateOperationalStatus();

    if (clockInterval) clearInterval(clockInterval);
    clockInterval = setInterval(updateRealtimeClock, 1000);

    // Update operational status every minute
    setInterval(updateOperationalStatus, 60000);

    // Phone Number Formatter
    const phoneInput = document.getElementById('borrower_phone_input');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove all non-digits
            let value = e.target.value.replace(/\D/g, '');
            // Add dash every 4 digits
            let formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1-');
            // Limit to 15 characters (e.g., 0812-3456-7890)
            if (formattedValue.length > 15) {
                formattedValue = formattedValue.substring(0, 15);
            }
            e.target.value = formattedValue;
        });
    }

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
