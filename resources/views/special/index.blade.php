<x-layout>
    <x-slot name="title">Inscription — Lancement Nouveau Produit</x-slot>



    <style>


        .page-wrapper {
            max-width: 720px;
            margin: 0 auto;
            padding: 0 0 60px;
        }

        /* ── BANNER HERO ── */
        .hero-banner {
            width: 100%;
            max-height: 380px;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        /* ── TEXTES INTRO ── */
        .intro-block {
            padding: 0 28px;
        }

        .hero-caption {
            text-align: center;
            margin: 24px 0 8px;
            font-size: 15px;
            font-weight: 400;
            letter-spacing: 0.05em;
            color: #7a6040;
            line-height: 1.7;
        }

        .hero-caption strong {
            font-weight: 700;
            color: #5a4020;
        }

        .section-title {
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #c9a96e;
            margin: 28px 0 6px;
        }

        .divider {
            width: 60px;
            height: 2px;
            background: #c9a96e;
            margin: 10px auto 28px;
            border-radius: 2px;
        }

        /* ── FORMULAIRE ── */
        .form-block {
            padding: 0 28px;
        }

        .form-group {
            margin-bottom: 26px;
        }

        label {
            display: block;
            font-family: 'Assistant', sans-serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #7a6040;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #d8cfc4;
            border-radius: 4px;
            background: #fff;
            font-family: 'Assistant', sans-serif;
            font-size: 17px;
            font-weight: 400;
            color: #2c2c2c;
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s;
            box-sizing: border-box;
        }

        input::placeholder {
            font-weight: 300;
            color: #b0a090;
        }

        input:focus {
            border-color: #c9a96e;
            box-shadow: 0 0 0 3px rgba(201,169,110,0.15);
        }

        /* ── COMPTEUR PERSONNES ── */
        .persons-counter {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .counter-btn {
            width: 40px;
            height: 40px;
            border: 2px solid #c9a96e;
            background: #fff;
            color: #9a7a4a;
            font-size: 22px;
            line-height: 1;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.2s;
        }

        .counter-btn:hover {
            background: #f9f3ea;
        }

        .counter-number {
            font-family: 'Assistant', sans-serif;
            font-size: 28px;
            font-weight: 300;
            color: #c9a96e;
            min-width: 32px;
            text-align: center;
        }

        .person-icons {
            font-size: 24px;
            letter-spacing: 4px;
            margin-top: 12px;
            min-height: 34px;
        }

        /* ── BOUTON ── */
        .btn-submit {
            width: 100%;
            padding: 17px;
            background: #c9a96e;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-family: 'Assistant', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.25s;
        }

        .btn-submit:hover {
            background: #b8944f;
        }

        /* ── ERREURS ── */
        .error-msg {
            font-family: 'Assistant', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #c0392b;
            margin-top: 6px;
        }

        /* ── MERCI / DUPLICATE ── */
        .merci-section {
            text-align: center;
            padding: 50px 28px 20px;
        }

        .merci-section h2 {
            font-family: 'Assistant', sans-serif;
            font-size: 46px;
            font-weight: 300;
            letter-spacing: 0.08em;
            color: #c9a96e;
            margin: 0 0 14px;
        }

        .merci-section p {
            font-family: 'Assistant', sans-serif;
            font-size: 17px;
            font-weight: 400;
            color: #666;
            line-height: 1.9;
            margin: 0 0 32px;
        }

        .merci-section video {
            max-width: 100%;
            border-radius: 4px;
        }
    </style>

    <div class="page-wrapper">

        {{-- ══ BANNER HERO ══ --}}
        <img
                src="{{ asset('img/syllabu3.png') }}"
                alt="Lancement nouveau produit"
                class="hero-banner mt-5"
        />

        <div class="intro-block">

            <p class="hero-caption">Au programme : présentation du <strong>syllabus UE.3</strong>, rencontres, échanges… et <strong>une belle surprise</strong> vous attend !</p>

            <div class="divider"></div>

            <p class="section-title">Inscription</p>
            <p class="hero-caption">Venez découvrir nos <strong>projets pour apprendre la Langue des Signes Francophone de Belgique</strong> de manière ludique !</p>

            <div class="divider"></div>
        </div>

        <div id="resultat" class="form-block">

            @if (session('success'))

                <div class="merci-section">
                    <h2>Merci !</h2>
                    <p>
                        Votre inscription a bien été enregistrée.<br>
                        Nous avons hâte de vous accueillir à l'événement.
                    </p>
                    <video src="https://res.cloudinary.com/dmhdsjmzf/video/upload/v1773140164/Merci_icgtsd.mp4" autoplay loop muted></video>
                </div>

            @elseif (session('duplicate'))

                <div class="merci-section">
                    <span style="font-size:52px">⚠️</span>
                    <h2 style="color:#c0392b">Déjà inscrit !</h2>
                    <p>Cette adresse e-mail est <strong>déjà enregistrée</strong>.<br>
                        Vous n'avez pas besoin de vous inscrire à nouveau.</p>
                </div>

            @else

                <form method="POST" action="{{ route('inscription.store') }}">
                    @csrf

                    {{-- Nom complet --}}
                    <div class="form-group">
                        <label for="nom">Nom complet</label>
                        <input
                                type="text"
                                id="nom"
                                name="nom"
                                value="{{ old('nom') }}"
                                placeholder="Votre nom et prénom"
                        />
                        @error('nom')
                        <p class="error-msg">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- E-mail --}}
                    <div class="form-group">
                        <label for="email">Adresse e-mail</label>
                        <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="votre@email.com"
                        />
                        @error('email')
                        <p class="error-msg">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nombre de personnes --}}
                    <div class="form-group">
                        <label>Nombre de personnes</label>
                        <div class="persons-counter">
                            <button type="button" class="counter-btn" onclick="changeCount(-1)">−</button>
                            <span class="counter-number" id="count-display">1</span>
                            <button type="button" class="counter-btn" onclick="changeCount(1)">+</button>
                        </div>
                        <input type="hidden" name="personnes" id="personnes-input" value="{{ old('personnes', 1) }}" />
                        <div class="person-icons" id="person-icons">👤</div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Confirmer mon inscription →
                    </button>

                </form>

            @endif

        </div>
    </div>

    <script>
        let count = {{ old('personnes', 1) }};

        function updateIcons() {
            document.getElementById('count-display').textContent = count;
            document.getElementById('personnes-input').value = count;
            document.getElementById('person-icons').textContent = '👤'.repeat(count);
        }

        function changeCount(delta) {
            count = Math.min(10, Math.max(1, count + delta));
            updateIcons();
        }

        updateIcons();

        window.addEventListener('load', function () {
            const el = document.getElementById('resultat');
            if (el && ({{ session('success') ? 'true' : 'false' }} || {{ session('duplicate') ? 'true' : 'false' }})) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    </script>

</x-layout>