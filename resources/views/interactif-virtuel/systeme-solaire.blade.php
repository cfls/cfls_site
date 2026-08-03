<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO básico -->
    <title>Système solaire — Fiches en LSFB</title>
    <meta name="description" content="Explorez le système solaire avec des fiches et vidéos en LSFB pour chaque planète.">
    <link rel="canonical" href="{{ url('/systeme-solaire') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo_cfls.png') }}">

    <!-- Open Graph (Facebook, LinkedIn, etc.) -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Système solaire — Fiches en LSFB">
    <meta property="og:description" content="Explorez le système solaire avec des fiches et vidéos en LSFB pour chaque planète.">
    <meta property="og:image" content="{{ asset('img/interactives/sisteme-solaire.png') }}">
    <meta property="og:url" content="{{ url('/systeme-solaire') }}">
    <meta property="og:locale" content="fr_BE">
    <meta property="og:site_name" content="LSFB Interactif">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Système solaire — Fiches en LSFB">
    <meta name="twitter:description" content="Explorez le système solaire avec des fiches et vidéos en LSFB pour chaque planète.">
    <meta name="twitter:image" content="{{ asset('img/interactives/sisteme-solaire.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">



    @verbatim
        <style>
            :root{
                --bg: #0A0E1F;
                --bg-panel: #12172E;
                --bg-panel-alt: #171d3a;
                --accent-gold: #F5B942;
                --accent-gold-dim: rgba(245,185,66,0.16);
                --text-primary: #EDEEF5;
                --text-secondary: #8A90B3;
                --line: rgba(138,144,179,0.22);
                --focus: #6FE3C4;
                --font-display: 'Space Grotesk', sans-serif;
                --font-mono: 'IBM Plex Mono', monospace;
            }

            * { box-sizing: border-box; }

            html, body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                background: var(--bg);
                color: var(--text-primary);
                font-family: var(--font-display);
                overflow: hidden;
            }

            #app { position: relative; width: 100vw; height: 100vh; }
            #scene-canvas { position: absolute; inset: 0; display: block; }

            a, button { font-family: inherit; }

            :focus-visible {
                outline: 2px solid var(--focus);
                outline-offset: 3px;
                border-radius: 4px;
            }

            /* ---------- Header ---------- */
            header.hud {
                position: absolute;
                top: 0; left: 0; right: 0;
                padding: 28px clamp(20px, 4vw, 48px) 0;
                pointer-events: none;
                z-index: 5;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 24px;
            }
            header.hud .hud-text { max-width: 420px; }
            header.hud .eyebrow {
                font-family: var(--font-mono);
                font-size: 11px;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                color: var(--accent-gold);
                margin: 0 0 6px;
            }
            header.hud h1 {
                font-size: clamp(20px, 2.4vw, 30px);
                font-weight: 600;
                margin: 0 0 8px;
                letter-spacing: -0.01em;
            }
            header.hud p.instructions {
                font-size: 13px;
                color: var(--text-secondary);
                margin: 0;
                line-height: 1.5;
            }

            header.hud .hud-brand {
                display: flex;
                align-items: flex-start;
                gap: 16px;
                pointer-events: auto;
            }
            header.hud .hud-logo {
                width: 44px;
                height: 44px;
                object-fit: contain;
                flex: none;
                border-radius: 8px;
                background: rgba(255,255,255,0.04);
                padding: 4px;
            }
            header.hud .hud-text { max-width: 420px; }

            /* ---------- Boutique CTA ---------- */
            .boutique-link {
                pointer-events: auto;
                flex: none;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 18px;
                background: var(--accent-gold-dim);
                border: 1px solid var(--accent-gold);
                border-radius: 999px;
                color: var(--accent-gold);
                font-family: var(--font-mono);
                font-size: 12px;
                letter-spacing: 0.02em;
                text-decoration: none;
                white-space: nowrap;
                transition: background 0.15s ease, transform 0.15s ease;
            }
            .boutique-link:hover, .boutique-link:focus-visible {
                background: rgba(245,185,66,0.28);
                transform: translateY(-1px);
            }
            .boutique-link svg { width: 14px; height: 14px; flex: none; }

            /* ---------- Planet labels (buttons projected in 3D space) ---------- */
            .planet-label {
                position: absolute;
                transform: translate(-50%, -50%);
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 6px 12px 6px 8px;
                background: rgba(18, 23, 46, 0.55);
                backdrop-filter: blur(6px);
                border: 1px solid var(--line);
                border-radius: 999px;
                color: var(--text-primary);
                font-family: var(--font-mono);
                font-size: 11px;
                letter-spacing: 0.03em;
                cursor: pointer;
                white-space: nowrap;
                transition: border-color 0.15s ease, background 0.15s ease, transform 0.15s ease;
                z-index: 4;
            }
            .planet-label .dot {
                width: 8px; height: 8px;
                border-radius: 50%;
                flex: none;
            }
            .planet-label:hover, .planet-label:focus-visible {
                border-color: var(--accent-gold);
                background: rgba(18, 23, 46, 0.85);
            }
            .planet-label.dim { opacity: 0.45; }
            .planet-label[hidden] { display: none; }

            /* ---------- Observation dial (signature nav element) ---------- */
            nav.dial {
                position: absolute;
                left: clamp(16px, 3vw, 40px);
                bottom: clamp(16px, 3vw, 40px);
                z-index: 6;
                width: 266px;
            }
            nav.dial .dial-label {
                font-family: var(--font-mono);
                font-size: 10px;
                letter-spacing: 0.16em;
                text-transform: uppercase;
                color: var(--text-secondary);
                margin: 0 0 10px 2px;
            }
            nav.dial ol {
                list-style: none;
                margin: 0;
                padding: 10px 12px;
                display: grid;
                grid-template-columns: repeat(11, 1fr);
                gap: 4px;
                background: var(--bg-panel);
                border: 1px solid var(--line);
                border-radius: 14px;
            }
            nav.dial li { display: contents; }
            nav.dial button {
                appearance: none;
                border: none;
                background: transparent;
                padding: 6px 2px;
                cursor: pointer;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 5px;
                border-radius: 8px;
            }
            nav.dial button .dial-dot {
                width: 10px; height: 10px;
                border-radius: 50%;
                box-shadow: 0 0 0 3px rgba(255,255,255,0.04);
            }
            nav.dial button .dial-num {
                font-family: var(--font-mono);
                font-size: 9px;
                color: var(--text-secondary);
            }
            nav.dial button:hover, nav.dial button:focus-visible {
                background: var(--bg-panel-alt);
            }
            nav.dial button[aria-pressed="true"] .dial-dot {
                box-shadow: 0 0 0 3px var(--accent-gold-dim), 0 0 8px var(--accent-gold);
            }
            nav.dial button[aria-pressed="true"] .dial-num { color: var(--accent-gold); }

            /* ---------- Modal ---------- */
            .modal-overlay {
                position: absolute;
                inset: 0;
                background: transparent;
                z-index: 10;
                display: flex;
                justify-content: flex-end;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.25s ease;
            }
            .modal-overlay.open { opacity: 1; pointer-events: auto; }

            .modal {
                width: min(480px, 92vw);
                height: 100%;
                background: var(--bg-panel);
                border-left: 1px solid var(--line);
                display: flex;
                flex-direction: column;
                transform: translateX(24px);
                opacity: 0;
                transition: transform 0.32s cubic-bezier(.2,.8,.2,1), opacity 0.32s ease;
                overflow-y: auto;
            }
            .modal-overlay.open .modal { transform: translateX(0); opacity: 1; }

            .modal-close {
                position: sticky;
                top: 18px;
                margin: 18px 18px 0 auto;
                width: 36px; height: 36px;
                border-radius: 50%;
                border: 1px solid var(--line);
                background: var(--bg-panel-alt);
                color: var(--text-primary);
                font-size: 15px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                flex: none;
            }
            .modal-close:hover { border-color: var(--accent-gold); color: var(--accent-gold); }

            .modal-header {
                padding: 4px 32px 20px;
                border-bottom: 1px solid var(--line);
            }
            .modal-eyebrow {
                display: block;
                font-family: var(--font-mono);
                font-size: 11px;
                letter-spacing: 0.16em;
                text-transform: uppercase;
                color: var(--accent-gold);
                margin-bottom: 8px;
            }
            .modal-header h2 {
                font-size: 30px;
                margin: 0;
                font-weight: 600;
            }

            .modal-body { padding: 24px 32px 40px; }

            .video-wrap {
                background: #000;
                border-radius: 12px;
                overflow: hidden;
                border: 1px solid var(--line);
                aspect-ratio: 16 / 9;
                position: relative;
            }
            .video-wrap video {
                width: 100%; height: 100%;
                display: block;
                object-fit: cover;
                background: #000;
            }
            .video-placeholder {
                position: absolute; inset: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 10px;
                padding: 24px;
                text-align: center;
                background: linear-gradient(160deg, #171d3a, #0A0E1F);
                color: var(--text-secondary);
                font-size: 13px;
                line-height: 1.5;
            }
            .video-placeholder strong { color: var(--text-primary); font-weight: 600; }
            .video-placeholder code {
                font-family: var(--font-mono);
                font-size: 11px;
                background: rgba(255,255,255,0.06);
                padding: 2px 6px;
                border-radius: 4px;
            }

            .video-caption {
                margin-top: 10px;
                font-family: var(--font-mono);
                font-size: 11px;
                color: var(--text-secondary);
                letter-spacing: 0.02em;
            }

            .modal-description {
                margin: 24px 0 0;
                font-size: 15px;
                line-height: 1.65;
                color: var(--text-primary);
            }

            .modal-facts {
                margin: 24px 0 0;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1px;
                background: var(--line);
                border: 1px solid var(--line);
                border-radius: 10px;
                overflow: hidden;
            }
            .modal-facts .fact {
                background: var(--bg-panel-alt);
                padding: 14px 16px;
            }
            .modal-facts dt {
                font-family: var(--font-mono);
                font-size: 10px;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                color: var(--text-secondary);
                margin: 0 0 4px;
            }
            .modal-facts dd {
                margin: 0;
                font-size: 14px;
                font-weight: 500;
            }

            /* ---------- Modal boutique CTA ---------- */
            .modal-boutique {
                margin-top: 28px;
                padding: 18px 20px;
                border: 1px solid var(--line);
                border-radius: 12px;
                background: var(--bg-panel-alt);
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
            }
            .modal-boutique p {
                margin: 0;
                font-size: 13px;
                color: var(--text-secondary);
                line-height: 1.5;
            }
            .modal-boutique a {
                flex: none;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 9px 16px;
                background: var(--accent-gold);
                color: #0A0E1F;
                font-weight: 600;
                font-size: 13px;
                border-radius: 8px;
                text-decoration: none;
                white-space: nowrap;
                transition: opacity 0.15s ease;
            }
            .modal-boutique a:hover, .modal-boutique a:focus-visible { opacity: 0.85; }

            /* ---------- Reduced motion ---------- */
            @media (prefers-reduced-motion: reduce) {
                .modal, .modal-overlay, .planet-label, nav.dial button { transition: none !important; }
            }

            @media (max-width: 640px) {
                header.hud .hud-brand { gap: 10px; }
                header.hud .hud-logo { width: 34px; height: 34px; }
                nav.dial { width: calc(100vw - 32px); }
                nav.dial ol { grid-template-columns: repeat(11, 1fr); padding: 8px 6px; }
                header.hud { flex-direction: column; }
                header.hud p.instructions { max-width: 260px; }
                .boutique-link { pointer-events: auto; }
                .modal-boutique { flex-direction: column; align-items: flex-start; }
                .modal-boutique a { width: 100%; justify-content: center; }
            }
        </style>
    @endverbatim
</head>
<body>
<div id="app">
    <canvas id="scene-canvas" aria-hidden="true"></canvas>

    <header class="hud">
        <div class="hud-brand">
            <img src="{{ asset('img/logo_cfls.png') }}" alt="CFLS — Centre Francophone de la Langue des Signes" class="hud-logo h-*">
            <div class="hud-text">
                <p class="eyebrow">Exploration accessible</p>
                <h1>Le système solaire</h1>
                <p class="instructions">Cliquez sur une planète — dans la scène ou dans le cadran ci-dessous — pour ouvrir sa fiche avec une vidéo en LSFB et sa description.</p>
            </div>
        </div>
        <a class="boutique-link" href="https://cfls.be/boutique/astronomie" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            Boutique astronomie
        </a>
    </header>

    <div id="labels-layer"></div>

    <nav class="dial" aria-label="Navigation rapide des planètes">
        <p class="dial-label">Cadran d'observation</p>
        <ol id="dial-list"></ol>
    </nav>

    <div class="modal-overlay" id="modal-overlay">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" id="modal">
            <button class="modal-close" id="modal-close" aria-label="Fermer la fiche de la planète">✕</button>
            <div class="modal-header">
                <span class="modal-eyebrow" id="modal-eyebrow"></span>
                <h2 id="modal-title"></h2>
            </div>
            <div class="modal-body">
                <div class="video-wrap">
                    <video id="modal-video" controls playsinline preload="none"></video>
                    <div class="video-placeholder" id="video-placeholder">
                        <strong>Vidéo LSFB non disponible</strong>
                        <span>Aucune URL Cloudinary renseignée pour <code id="video-path-hint"></code>.<br>Ajoutez-la dans <code>VIDEO_URLS</code> en haut du script.</span>
                    </div>
                </div>
                <p class="video-caption">Vidéo en Langue des Signes Francophone de Belgique (LSFB)</p>
                <p class="modal-description" id="modal-description"></p>
                <dl class="modal-facts" id="modal-facts"></dl>

                <div class="modal-boutique">
                    <p>Envie d'aller plus loin ? Retrouvez du matériel et des ressources d'astronomie dans notre boutique.</p>
                    <a href="https://cfls.be/boutique/astronomie" target="_blank" rel="noopener">Voir la boutique →</a>
                </div>
            </div>
        </div>
    </div>
</div>

@verbatim
    <script>
        (function(){
            "use strict";
            var CDN_URLS = [
                'https://cdnjs.cloudflare.com/ajax/libs/three.js/r158/three.min.js',
                'https://cdn.jsdelivr.net/npm/three@0.158.0/build/three.min.js',
                'https://unpkg.com/three@0.158.0/build/three.min.js'
            ];

            function loadScript(src, onload, onerror) {
                var s = document.createElement('script');
                s.src = src;
                s.onload = onload;
                s.onerror = onerror;
                document.head.appendChild(s);
            }

            function tryLoadThree(i) {
                if (i >= CDN_URLS.length) { showLoadError(); return; }
                loadScript(CDN_URLS[i], function(){
                    if (window.THREE) { window.initSolarSystem(); }
                    else { tryLoadThree(i + 1); }
                }, function(){ tryLoadThree(i + 1); });
            }

            function showLoadError() {
                var box = document.createElement('div');
                box.setAttribute('role', 'alert');
                box.style.cssText = 'position:fixed;inset:0;display:flex;align-items:center;justify-content:center;' +
                    'background:#0A0E1F;color:#EDEEF5;font-family:sans-serif;padding:32px;text-align:center;z-index:9999;';
                box.innerHTML = '<div style="max-width:480px;">' +
                    '<h1 style="font-size:20px;margin-bottom:12px;">Impossible de charger la bibliothèque 3D</h1>' +
                    '<p style="color:#8A90B3;line-height:1.6;font-size:14px;">Ce fichier a besoin d\'accéder à Internet ' +
                    '(cdnjs.cloudflare.com, jsdelivr.net ou unpkg.com) au premier chargement. ' +
                    'Vérifiez votre connexion, désactivez un éventuel bloqueur de scripts / antivirus pour ce fichier, ' +
                    'puis rechargez la page.</p></div>';
                document.body.appendChild(box);
            }

            tryLoadThree(0);
        })();
    </script>
    <script>
        window.initSolarSystem = function(){
            "use strict";

            var VIDEO_URLS = {
                sol: '',
                mercure: 'https://res.cloudinary.com/dmhdsjmzf/video/upload/v1777399401/Saveur_2_mains_gsaspj.mp4',
                venus: '',
                terre: '',
                mars: '',
                jupiter: '',
                saturne: '',
                uranus: '',
                neptune: '',
                pluton: '',
                lune: ''
            };

            function buildVideoUrl(planet) {
                return VIDEO_URLS[planet.key] || '';
            }

            function buildPosterUrl(planet) {
                var url = VIDEO_URLS[planet.key];
                if (!url) return '';
                return url.replace('/upload/', '/upload/so_0,f_auto,q_auto/').replace(/\.mp4($|\?)/, '.jpg$1');
            }

            var MOON_DATA = {
                key: 'lune', name: 'Lune', color: 0xC9C9C9, size: 0.55,
                eyebrowOverride: 'Satellite naturel de la Terre',
                description: "La Lune est l'unique satellite naturel de la Terre. Elle stabilise l'inclinaison de notre planète et provoque les marées grâce à son attraction gravitationnelle.",
                facts: { "Distance à la Terre": "384 400 km", "Diamètre": "3 474 km", "Période orbitale": "≈ 27,3 jours" }
            };

            var SUN_DATA = {
                key: 'sol', name: 'Soleil', color: 0xF5B942, size: 6.4,
                eyebrowOverride: 'Étoile du système',
                description: "Le Soleil est une étoile naine jaune qui concentre à elle seule plus de 99% de la masse du système solaire. Sa fusion nucléaire libère l'énergie qui rend la vie possible sur Terre, à environ 8 minutes-lumière de distance.",
                facts: { "Type": "Naine jaune (G2V)", "Diamètre": "1 391 000 km", "Température de surface": "≈ 5 500 °C", "Âge": "≈ 4,6 milliards d'années" }
            };

            var PLANETS = [
                {
                    key: 'mercure', name: 'Mercure', color: 0xA9A296,
                    orbitRadius: 18, size: 1.2, speed: 0.0082, tilt: 0.0,
                    description: "Mercure est la planète la plus proche du Soleil et la plus petite du système solaire. Sans atmosphère pour retenir la chaleur, elle passe de plus de 400°C le jour à moins de -180°C la nuit.",
                    facts: { "Distance au Soleil": "57,9 millions km", "Diamètre": "4 879 km", "Durée d'une année": "88 jours" }
                },
                {
                    key: 'venus', name: 'Vénus', color: 0xE7C697,
                    orbitRadius: 25, size: 1.8, speed: 0.0062, tilt: 0.0,
                    description: "Vénus est la planète la plus chaude du système solaire à cause d'un effet de serre extrême. Elle tourne sur elle-même dans le sens inverse des autres planètes.",
                    facts: { "Distance au Soleil": "108,2 millions km", "Diamètre": "12 104 km", "Durée d'une année": "225 jours" }
                },
                {
                    key: 'terre', name: 'Terre', color: 0x3F7EBB,
                    orbitRadius: 32, size: 1.9, speed: 0.0050, tilt: 0.41,
                    description: "Notre planète est la seule connue à abriter la vie. Environ 71% de sa surface est recouverte d'eau, et son unique satellite naturel, la Lune, stabilise son inclinaison.",
                    facts: { "Distance au Soleil": "149,6 millions km", "Diamètre": "12 742 km", "Durée d'une année": "365 jours" }
                },
                {
                    key: 'mars', name: 'Mars', color: 0xC1553A,
                    orbitRadius: 40, size: 1.4, speed: 0.0040, tilt: 0.44,
                    description: "Surnommée la planète rouge à cause de l'oxyde de fer présent dans son sol, Mars abrite Olympus Mons, le plus grand volcan connu du système solaire.",
                    facts: { "Distance au Soleil": "227,9 millions km", "Diamètre": "6 779 km", "Durée d'une année": "687 jours" }
                },
                {
                    key: 'jupiter', name: 'Jupiter', color: 0xD9A066,
                    orbitRadius: 55, size: 5.5, speed: 0.0022, tilt: 0.05,
                    description: "Jupiter est la plus grande planète du système solaire, une géante gazeuse dotée d'au moins 95 lunes connues et d'une tempête géante, la Grande Tache rouge, qui dure depuis des siècles.",
                    facts: { "Distance au Soleil": "778,5 millions km", "Diamètre": "139 820 km", "Durée d'une année": "≈ 12 ans" }
                },
                {
                    key: 'saturne', name: 'Saturne', color: 0xE8CB8B,
                    orbitRadius: 70, size: 4.8, speed: 0.0016, tilt: 0.47, hasRing: true,
                    description: "Célèbre pour ses anneaux spectaculaires composés de glace et de roche, Saturne est si peu dense qu'elle flotterait sur l'eau si un océan assez grand existait.",
                    facts: { "Distance au Soleil": "1,43 milliard km", "Diamètre": "116 460 km", "Durée d'une année": "≈ 29 ans" }
                },
                {
                    key: 'uranus', name: 'Uranus', color: 0x8FD1D9,
                    orbitRadius: 85, size: 3.2, speed: 0.0011, tilt: 1.71, hasRing: true,
                    description: "Uranus est inclinée à un angle extrême, si bien qu'elle semble rouler sur son orbite plutôt que tourner. Sa couleur bleu-vert vient du méthane présent dans son atmosphère.",
                    facts: { "Distance au Soleil": "2,87 milliards km", "Diamètre": "50 724 km", "Durée d'une année": "≈ 84 ans" }
                },
                {
                    key: 'neptune', name: 'Neptune', color: 0x4A63C4,
                    orbitRadius: 98, size: 3.1, speed: 0.0009, tilt: 0.49,
                    description: "Neptune est la planète la plus éloignée du Soleil et la plus venteuse : des vents y dépassent 2000 km/h. Sa couleur bleu intense est également due au méthane.",
                    facts: { "Distance au Soleil": "4,50 milliards km", "Diamètre": "49 244 km", "Durée d'une année": "≈ 165 ans" }
                },
                {
                    key: 'pluton', name: 'Pluton', color: 0xB7A99A,
                    orbitRadius: 110, size: 0.9, speed: 0.0007, tilt: 0.30,
                    description: "Reclassée planète naine en 2006, Pluton évolue dans la ceinture de Kuiper. Elle possède cinq lunes connues, dont Charon, presque aussi grande qu'elle.",
                    facts: { "Distance au Soleil": "5,91 milliards km", "Diamètre": "2 377 km", "Durée d'une année": "≈ 248 ans" }
                }
            ];

            var canvas = document.getElementById('scene-canvas');
            var renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: false });
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            renderer.setSize(window.innerWidth, window.innerHeight);

            var scene = new THREE.Scene();
            scene.background = new THREE.Color(0x0A0E1F);

            var camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 2000);
            var OVERVIEW_POS = new THREE.Vector3(0, 55, 145);
            var OVERVIEW_LOOK = new THREE.Vector3(0, 0, 0);
            camera.position.copy(OVERVIEW_POS);
            camera.lookAt(OVERVIEW_LOOK);

            scene.add(new THREE.AmbientLight(0x33395c, 1.1));
            var sunLight = new THREE.PointLight(0xfff4d6, 3.2, 0, 0.6);
            sunLight.position.set(0, 0, 0);
            scene.add(sunLight);

            (function buildStarfield(){
                var count = 2200;
                var positions = new Float32Array(count * 3);
                for (var i = 0; i < count; i++) {
                    var r = 400 + Math.random() * 900;
                    var theta = Math.random() * Math.PI * 2;
                    var phi = Math.acos((Math.random() * 2) - 1);
                    positions[i*3]   = r * Math.sin(phi) * Math.cos(theta);
                    positions[i*3+1] = r * Math.cos(phi);
                    positions[i*3+2] = r * Math.sin(phi) * Math.sin(theta);
                }
                var geo = new THREE.BufferGeometry();
                geo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
                var mat = new THREE.PointsMaterial({ color: 0xffffff, size: 1.1, sizeAttenuation: true, transparent: true, opacity: 0.85 });
                scene.add(new THREE.Points(geo, mat));
            })();

            var sunGroup = new THREE.Group();
            var sunMesh = new THREE.Mesh(
                new THREE.SphereGeometry(6.4, 48, 48),
                new THREE.MeshBasicMaterial({ color: 0xffd27a })
            );
            sunMesh.userData.planetKey = 'sol';
            sunGroup.add(sunMesh);
            [8.4, 10.6, 13.2].forEach(function(r, idx){
                var halo = new THREE.Mesh(
                    new THREE.SphereGeometry(r, 32, 32),
                    new THREE.MeshBasicMaterial({ color: 0xF5B942, transparent: true, opacity: 0.10 - idx*0.02, side: THREE.BackSide })
                );
                sunGroup.add(halo);
            });
            scene.add(sunGroup);

            var sunHitArea = new THREE.Mesh(
                new THREE.SphereGeometry(15, 12, 12),
                new THREE.MeshBasicMaterial({ visible: false })
            );
            sunHitArea.userData.planetKey = 'sol';
            sunGroup.add(sunHitArea);

            SUN_DATA.mesh = sunMesh;
            SUN_DATA.clickTargets = [sunMesh, sunHitArea];

            var ALL_BODIES = [SUN_DATA].concat(PLANETS);

            function buildOrbitLine(radius) {
                var segments = 128;
                var points = [];
                for (var i = 0; i <= segments; i++) {
                    var a = (i / segments) * Math.PI * 2;
                    points.push(new THREE.Vector3(Math.cos(a) * radius, 0, Math.sin(a) * radius));
                }
                var geo = new THREE.BufferGeometry().setFromPoints(points);
                var mat = new THREE.LineBasicMaterial({ color: 0x3A4270, transparent: true, opacity: 0.5 });
                return new THREE.LineLoop(geo, mat);
            }

            PLANETS.forEach(function(p){
                scene.add(buildOrbitLine(p.orbitRadius));

                var pivot = new THREE.Group();
                scene.add(pivot);

                var mesh = new THREE.Mesh(
                    new THREE.SphereGeometry(p.size, 32, 32),
                    new THREE.MeshStandardMaterial({ color: p.color, roughness: 0.85, metalness: 0.05 })
                );
                mesh.position.set(p.orbitRadius, 0, 0);
                mesh.rotation.z = p.tilt;
                mesh.userData.planetKey = p.key;
                pivot.add(mesh);

                if (p.hasRing) {
                    var ring = new THREE.Mesh(
                        new THREE.RingGeometry(p.size * 1.5, p.size * 2.3, 64),
                        new THREE.MeshBasicMaterial({ color: p.color, transparent: true, opacity: 0.45, side: THREE.DoubleSide })
                    );
                    ring.rotation.x = Math.PI / 2 - p.tilt;
                    ring.position.copy(mesh.position);
                    pivot.add(ring);
                }

                var hitArea = new THREE.Mesh(
                    new THREE.SphereGeometry(Math.max(p.size * 2.2, 2.6), 12, 12),
                    new THREE.MeshBasicMaterial({ visible: false })
                );
                hitArea.position.copy(mesh.position);
                hitArea.userData.planetKey = p.key;
                pivot.add(hitArea);

                p.pivot = pivot;
                p.mesh = mesh;
                p.angle = Math.random() * Math.PI * 2;
                pivot.rotation.y = p.angle;
                p.clickTargets = [mesh, hitArea];

                if (p.key === 'terre') {
                    var moonOrbitRadius = 3.4;

                    var moonOrbitLine = buildOrbitLine(moonOrbitRadius);
                    moonOrbitLine.position.copy(mesh.position);
                    pivot.add(moonOrbitLine);

                    var moonPivot = new THREE.Group();
                    moonPivot.position.copy(mesh.position);
                    pivot.add(moonPivot);

                    var moonMesh = new THREE.Mesh(
                        new THREE.SphereGeometry(MOON_DATA.size, 24, 24),
                        new THREE.MeshStandardMaterial({ color: MOON_DATA.color, roughness: 0.95, metalness: 0.02 })
                    );
                    moonMesh.position.set(moonOrbitRadius, 0, 0);
                    moonMesh.userData.planetKey = 'lune';
                    moonPivot.add(moonMesh);

                    var moonHitArea = new THREE.Mesh(
                        new THREE.SphereGeometry(Math.max(MOON_DATA.size * 2.6, 1.4), 10, 10),
                        new THREE.MeshBasicMaterial({ visible: false })
                    );
                    moonHitArea.position.copy(moonMesh.position);
                    moonHitArea.userData.planetKey = 'lune';
                    moonPivot.add(moonHitArea);

                    MOON_DATA.pivot = moonPivot;
                    MOON_DATA.mesh = moonMesh;
                    MOON_DATA.angle = Math.random() * Math.PI * 2;
                    moonPivot.rotation.y = MOON_DATA.angle;
                    MOON_DATA.speed = 0.018;
                    MOON_DATA.clickTargets = [moonMesh, moonHitArea];
                    ALL_BODIES.push(MOON_DATA);
                }
            });

            var isPaused = false;
            var activePlanet = null;
            var camMode = 'overview';
            var camCurrentPos = camera.position.clone();
            var camCurrentLook = OVERVIEW_LOOK.clone();
            var camTargetPos = OVERVIEW_POS.clone();
            var camTargetLook = OVERVIEW_LOOK.clone();

            function worldPositionOf(planet) {
                var v = new THREE.Vector3();
                planet.mesh.getWorldPosition(v);
                return v;
            }

            function setCameraTargetForPlanet(planet) {
                var pos = worldPositionOf(planet);
                var dir = pos.clone().normalize();
                if (dir.lengthSq() === 0) dir.set(0, 0, 1);
                var dist = Math.max(planet.size * 7, 8);
                camTargetPos = pos.clone().add(dir.multiplyScalar(dist)).add(new THREE.Vector3(0, planet.size * 1.6 + 1.5, 0));
                camTargetLook = pos.clone();
            }

            function setCameraTargetOverview() {
                camTargetPos = OVERVIEW_POS.clone();
                camTargetLook = OVERVIEW_LOOK.clone();
            }

            var labelsLayer = document.getElementById('labels-layer');
            var labelEls = {};
            ALL_BODIES.forEach(function(p){
                var btn = document.createElement('button');
                btn.className = 'planet-label';
                btn.type = 'button';
                btn.setAttribute('aria-label', 'Voir la fiche de ' + p.name);
                btn.innerHTML = '<span class="dot" style="background:#' + p.color.toString(16).padStart(6,'0') + '"></span><span>' + p.name + '</span>';
                btn.addEventListener('click', function(){ selectPlanet(p.key); });
                if (p.key === 'lune') { btn.style.zIndex = '5'; }
                labelsLayer.appendChild(btn);
                labelEls[p.key] = btn;
            });

            function updateLabels() {
                ALL_BODIES.forEach(function(p){
                    var pos = worldPositionOf(p).clone();
                    pos.project(camera);
                    var el = labelEls[p.key];
                    if (pos.z > 1) { el.hidden = true; return; }
                    el.hidden = false;
                    var x = (pos.x * 0.5 + 0.5) * window.innerWidth;
                    var y = (-pos.y * 0.5 + 0.5) * window.innerHeight;
                    el.style.left = x + 'px';
                    var verticalOffset = p.size * 4 + 14;
                    el.style.top = (p.key === 'lune' ? (y + verticalOffset) : (y - verticalOffset)) + 'px';
                    el.classList.toggle('dim', camMode === 'zoom' && activePlanet !== p.key);
                });
            }

            var dialList = document.getElementById('dial-list');
            var dialButtons = {};
            ALL_BODIES.forEach(function(p, i){
                var li = document.createElement('li');
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.setAttribute('aria-pressed', 'false');
                btn.setAttribute('aria-label', p.name);
                btn.title = p.name;
                var numLabel = p.key === 'sol' ? '☉' : (p.key === 'lune' ? '☾' : i);
                btn.innerHTML = '<span class="dial-dot" style="background:#' + p.color.toString(16).padStart(6,'0') + '"></span><span class="dial-num">' + numLabel + '</span>';
                btn.addEventListener('click', function(){ selectPlanet(p.key); });
                li.appendChild(btn);
                dialList.appendChild(li);
                dialButtons[p.key] = btn;
            });

            function refreshDialState() {
                Object.keys(dialButtons).forEach(function(key){
                    dialButtons[key].setAttribute('aria-pressed', key === activePlanet ? 'true' : 'false');
                });
            }

            var overlay = document.getElementById('modal-overlay');
            var modalTitle = document.getElementById('modal-title');
            var modalEyebrow = document.getElementById('modal-eyebrow');
            var modalDescription = document.getElementById('modal-description');
            var modalFacts = document.getElementById('modal-facts');
            var modalVideo = document.getElementById('modal-video');
            var videoPlaceholder = document.getElementById('video-placeholder');
            var videoPathHint = document.getElementById('video-path-hint');
            var modalCloseBtn = document.getElementById('modal-close');
            var lastFocusedEl = null;

            function findPlanet(key) {
                for (var i = 0; i < ALL_BODIES.length; i++) if (ALL_BODIES[i].key === key) return ALL_BODIES[i];
                return null;
            }

            function openModalFor(planet) {
                modalEyebrow.textContent = planet.eyebrowOverride || ('Planète ' + (PLANETS.indexOf(planet) + 1) + ' / 9');
                modalTitle.textContent = planet.name;
                modalDescription.textContent = planet.description;

                modalFacts.innerHTML = '';
                Object.keys(planet.facts).forEach(function(k){
                    var wrap = document.createElement('div');
                    wrap.className = 'fact';
                    var dt = document.createElement('dt'); dt.textContent = k;
                    var dd = document.createElement('dd'); dd.textContent = planet.facts[k];
                    wrap.appendChild(dt); wrap.appendChild(dd);
                    modalFacts.appendChild(wrap);
                });

                var videoUrl = buildVideoUrl(planet);
                videoPathHint.textContent = 'VIDEO_URLS.' + planet.key;
                if (videoUrl) {
                    modalVideo.style.display = 'none';
                    videoPlaceholder.style.display = 'flex';
                    modalVideo.poster = buildPosterUrl(planet);
                    modalVideo.src = videoUrl;
                    modalVideo.load();
                } else {
                    modalVideo.removeAttribute('src');
                    modalVideo.style.display = 'none';
                    videoPlaceholder.style.display = 'flex';
                }

                lastFocusedEl = document.activeElement;
                overlay.classList.add('open');
                overlay.removeAttribute('hidden');
                modalCloseBtn.focus();

                document.addEventListener('keydown', onModalKeydown);
            }

            function closeModal() {
                overlay.classList.remove('open');
                modalVideo.pause();
                document.removeEventListener('keydown', onModalKeydown);
                if (lastFocusedEl && typeof lastFocusedEl.focus === 'function') lastFocusedEl.focus();
            }

            function onModalKeydown(e) {
                if (e.key === 'Escape') { deselectPlanet(); return; }
                if (e.key !== 'Tab') return;
                var focusables = overlay.querySelectorAll('button, [href], input, video, [tabindex]:not([tabindex="-1"])');
                if (!focusables.length) return;
                var first = focusables[0], last = focusables[focusables.length - 1];
                if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            }

            modalVideo.addEventListener('loadeddata', function(){
                modalVideo.style.display = 'block';
                videoPlaceholder.style.display = 'none';
            });
            modalVideo.addEventListener('error', function(){
                modalVideo.style.display = 'none';
                videoPlaceholder.style.display = 'flex';
            });

            modalCloseBtn.addEventListener('click', deselectPlanet);
            overlay.addEventListener('click', function(e){ if (e.target === overlay) deselectPlanet(); });

            function selectPlanet(key) {
                var planet = findPlanet(key);
                if (!planet) return;
                activePlanet = key;
                isPaused = true;
                camMode = 'zoom';
                setCameraTargetForPlanet(planet);
                refreshDialState();
                openModalFor(planet);
            }

            function deselectPlanet() {
                activePlanet = null;
                isPaused = false;
                camMode = 'overview';
                setCameraTargetOverview();
                refreshDialState();
                closeModal();
            }

            var raycaster = new THREE.Raycaster();
            var pointerNdc = new THREE.Vector2();
            canvas.addEventListener('click', function(e){
                pointerNdc.x = (e.clientX / window.innerWidth) * 2 - 1;
                pointerNdc.y = -(e.clientY / window.innerHeight) * 2 + 1;
                raycaster.setFromCamera(pointerNdc, camera);
                var allTargets = [];
                ALL_BODIES.forEach(function(p){ allTargets = allTargets.concat(p.clickTargets); });
                var hits = raycaster.intersectObjects(allTargets, false);
                if (hits.length) {
                    var key = hits[0].object.userData.planetKey;
                    if (key) selectPlanet(key);
                }
            });

            function animate() {
                requestAnimationFrame(animate);

                if (!isPaused) {
                    PLANETS.forEach(function(p){
                        p.angle += p.speed;
                        p.pivot.rotation.y = p.angle;
                        p.mesh.rotation.y += 0.01;
                    });
                    MOON_DATA.angle += MOON_DATA.speed;
                    MOON_DATA.pivot.rotation.y = MOON_DATA.angle;
                }
                sunMesh.rotation.y += 0.0015;

                camCurrentPos.lerp(camTargetPos, 0.055);
                camCurrentLook.lerp(camTargetLook, 0.065);
                camera.position.copy(camCurrentPos);
                camera.lookAt(camCurrentLook);

                updateLabels();
                renderer.render(scene, camera);
            }
            animate();

            window.addEventListener('resize', function(){
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
            });

        };
    </script>
@endverbatim
</body>
</html>