<x-layout>
    <x-slot name="title">Inscription — Journée Immersive</x-slot>



    <style>


        .page-wrapper {
            max-width: 720px;
            margin: 0 auto;
            padding: 0 0 60px;
        }

        /* ── BANNER HERO ── */
        .hero-banner {
            width: 100%;
            height: auto;
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

        /* ── FORMULES / TARIFS ── */
        .formules {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media (max-width: 520px) {
            .formules { grid-template-columns: 1fr; }
        }

        .formule-option {
            position: relative;
            display: block;
            padding: 18px 16px;
            border: 1px solid #d8cfc4;
            border-radius: 4px;
            background: #fff;
            cursor: pointer;
            text-transform: none;
            letter-spacing: normal;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
        }

        .formule-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .formule-option:has(input:checked) {
            border-color: #c9a96e;
            background: #f9f3ea;
            box-shadow: 0 0 0 3px rgba(201,169,110,0.15);
        }

        .formule-prix {
            display: block;
            font-size: 30px;
            font-weight: 300;
            color: #c9a96e;
            line-height: 1.1;
        }

        .formule-label {
            display: block;
            margin-top: 6px;
            font-size: 15px;
            font-weight: 500;
            color: #5a4020;
        }

        .total-line {
            text-align: right;
            font-family: 'Assistant', sans-serif;
            font-size: 17px;
            color: #7a6040;
            margin: -8px 0 20px;
        }

        .total-line strong {
            font-size: 22px;
            color: #5a4020;
        }

        .paiement-box {
            border: 1px dashed #c9a96e;
            border-radius: 4px;
            background: #fdfaf5;
            padding: 16px 18px;
            margin: 0 0 26px;
            font-family: 'Assistant', sans-serif;
            font-size: 15px;
            color: #7a6040;
            line-height: 1.7;
            text-align: center;
        }

        .paiement-box .iban {
            display: block;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #5a4020;
            user-select: all;
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

        /* ══════════════ DARK MODE ══════════════ */
        .dark .hero-caption {
            color: #e3d3b5;
        }

        .dark .hero-caption strong {
            color: #f7ebd3;
        }

        .dark .section-title {
            color: #e6c684;
        }

        .dark .divider {
            background: #e6c684;
        }

        .dark label {
            color: #ecd9b4;
        }

        .dark input[type="text"],
        .dark input[type="email"] {
            background: #1e293b;
            border-color: #4b5768;
            color: #f1f5f9;
        }

        .dark input::placeholder {
            color: #94a3b8;
        }

        .dark input:focus {
            border-color: #e6c684;
            box-shadow: 0 0 0 3px rgba(230,198,132,0.25);
        }

        .dark .counter-btn {
            background: #1e293b;
            border-color: #e6c684;
            color: #e6c684;
        }

        .dark .counter-btn:hover {
            background: #334155;
        }

        .dark .counter-number {
            color: #e6c684;
        }

        .dark .formule-option {
            background: #1e293b;
            border-color: #4b5768;
        }

        .dark .formule-option:has(input:checked) {
            border-color: #e6c684;
            background: #33302a;
            box-shadow: 0 0 0 3px rgba(230,198,132,0.25);
        }

        .dark .formule-prix {
            color: #e6c684;
        }

        .dark .formule-label {
            color: #f1e6d0;
        }

        .dark .total-line {
            color: #e3d3b5;
        }

        .dark .total-line strong {
            color: #f7ebd3;
        }

        .dark .paiement-box {
            background: #1e293b;
            border-color: #e6c684;
            color: #e3d3b5;
        }

        .dark .paiement-box .iban {
            color: #f7ebd3;
        }

        .dark .btn-submit {
            background: #e6c684;
            color: #2b2113;
        }

        .dark .btn-submit:hover {
            background: #f0d79e;
        }

        .dark .error-msg {
            color: #f87171;
        }

        .dark .merci-section h2 {
            color: #e6c684;
        }

        .dark .merci-section p {
            color: #cbd5e1;
        }
    </style>

    <div class="page-wrapper">

        {{-- ══ BANNER HERO ══ --}}
        <img
                src="{{ asset('img/special/journee_immersive.jpg') }}"
                alt="Journée Immersive"
                class="hero-banner mt-5"
        />

        <div class="intro-block">

            <p class="hero-caption">
                📅 <strong>Samedi 14 novembre 2026</strong><br>
                📍 <strong>Rue au Bois 365B, 1150 Bruxelles</strong>
            </p>

            <div class="divider"></div>

            <p class="section-title">Au programme</p>
            <p class="hero-caption">
                <strong>Journée :</strong> ateliers d'apprentissage, activités et sensibilisations<br>
                <strong>Soirée :</strong> repas et animations / jeux
            </p>

            <div class="divider"></div>

            <p class="section-title">Tarifs</p>
            <p class="hero-caption">
                @foreach (\App\Models\Inscription::FORMULES_IMMERSIVE as $f)
                    <strong>{{ $f['prix'] }} €</strong> — {{ $f['label'] }}<br>
                @endforeach
            </p>

            <div class="divider"></div>

            <p class="section-title">Inscription</p>
            <p class="hero-caption">Réservez dès maintenant votre place pour la <strong>Journée Immersive</strong> !</p>

            <div class="divider"></div>
        </div>

        <div id="resultat" class="form-block">

            @if (session('success'))

                <div class="merci-section">
                    <h2>Merci !</h2>
                    <p>
                        Votre inscription a bien été enregistrée.<br>
                        Nous avons hâte de vous accueillir à la Journée Immersive.
                    </p>
                    @if (session('prix'))
                        <div class="paiement-box">
                            Montant à régler : <strong>{{ session('prix') }} €</strong><br>
                            par virement sur le compte
                            <span class="iban">{{ \App\Models\Inscription::IBAN_IMMERSIVE }}</span>
                            Communication : « Journée Immersive — votre nom »
                        </div>
                    @endif
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

                <form method="POST" action="{{ route('inscription.journee_immersive.store') }}">
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

                    {{-- Formule --}}
                    <div class="form-group">
                        <label>Formule</label>
                        <div class="formules">
                            @foreach (\App\Models\Inscription::FORMULES_IMMERSIVE as $key => $f)
                                <label class="formule-option">
                                    <input
                                            type="radio"
                                            name="formule"
                                            value="{{ $key }}"
                                            data-prix="{{ $f['prix'] }}"
                                            onchange="updateTotal()"
                                            @checked(old('formule', 'journee_soiree') === $key)
                                    />
                                    <span class="formule-prix">{{ $f['prix'] }} €</span>
                                    <span class="formule-label">{{ $f['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('formule')
                        <p class="error-msg">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    <p class="total-line">Total : <strong id="total-display">75 €</strong></p>

                    <div class="paiement-box">
                        Paiement par virement sur le compte
                        <span class="iban">{{ \App\Models\Inscription::IBAN_IMMERSIVE }}</span>
                        Communication : « Journée Immersive — votre nom »
                    </div>

                    <button type="submit" class="btn-submit">
                        Confirmer mon inscription →
                    </button>

                </form>

            @endif

        </div>
    </div>

    <script>
        let count = {{ (int) old('personnes', 1) }};

        function updateIcons() {
            const display = document.getElementById('count-display');
            if (!display) return;
            display.textContent = count;
            document.getElementById('personnes-input').value = count;
            document.getElementById('person-icons').textContent = '👤'.repeat(count);
            updateTotal();
        }

        function updateTotal() {
            const checked = document.querySelector('input[name="formule"]:checked');
            const total = document.getElementById('total-display');
            if (!checked || !total) return;
            total.textContent = (parseInt(checked.dataset.prix, 10) * count) + ' €';
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
