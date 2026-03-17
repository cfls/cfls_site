function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

function savePreferences(value) {
    const expires = new Date();
    expires.setFullYear(expires.getFullYear() + 1);
    document.cookie = `cookie_consent=${value}; expires=${expires.toUTCString()}; path=/; SameSite=Lax`;
    document.getElementById('accessibility-banner').style.display = 'none';

    if (value === "accepted") {
        enableGoogleAnalytics(); // activar GA inmediatamente
    }
}

function showCookieBanner() {
    const consent = getCookie("cookie_consent");
    if (!consent) {
        document.getElementById('accessibility-banner').style.display = 'block';
    }
}

// Normal: primera carga
document.addEventListener("DOMContentLoaded", showCookieBanner);

// Livewire: cada render
document.addEventListener("livewire:navigated", showCookieBanner);
