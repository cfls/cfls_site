<x-layout>
<x-slot name="title">Inscription — Visual Vernacular</x-slot>
<style>
  body { background-color: #1a1714; font-family: 'DM Sans', sans-serif; margin: 0; padding: 0; }

  .banner-hero { width: 100%; height: 480px; position: relative; overflow: hidden; }
  .banner-hero img {
    width: 100%; height: 100%; object-fit: cover; object-position: center 20%;
    display: block; filter: saturate(0.75) contrast(1.05); transition: transform 8s ease;
  }
  .banner-hero:hover img { transform: scale(1.03); }
  .banner-hero::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(160deg,
      rgba(26,23,20,0.05) 0%, rgba(26,23,20,0.2) 30%,
      rgba(26,23,20,0.75) 70%, rgba(26,23,20,1) 100%);
  }
  .banner-side-line { position: absolute; top: 2rem; left: 2rem; z-index: 3; }
  .banner-title { position: absolute; bottom: 0; left: 0; right: 0; z-index: 2; padding: 2.5rem 2rem; text-align: center; }

  .grain::before {
    content: ''; position: fixed; inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
    pointer-events: none; z-index: 0; opacity: 0.4;
  }

  .card-check input[type="radio"],
  .card-check input[type="checkbox"] { display: none; }
  .card-check input[type="radio"]:checked + label,
  .card-check input[type="checkbox"]:checked + label {
    background-color: #c8893a; border-color: #c8893a; color: #1a1714;
  }
  .card-check input[type="radio"]:checked + label .check-dot,
  .card-check input[type="checkbox"]:checked + label .check-dot { background-color: #1a1714; border-color: #1a1714; }
  .card-check input[type="radio"]:checked + label .check-dot::after,
  .card-check input[type="checkbox"]:checked + label .check-dot::after { display: block; }

  .check-dot {
    width: 18px; height: 18px; border: 2px solid #c8893a;
    border-radius: 50%; flex-shrink: 0; position: relative; transition: all 0.2s;
  }
  .check-dot::after {
    content: ''; display: none; width: 8px; height: 8px; background: #f5f0e8;
    border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
  }
  .check-square {
    width: 18px; height: 18px; border: 2px solid #c8893a;
    border-radius: 3px; flex-shrink: 0; position: relative; transition: all 0.2s;
  }
  .check-square::after {
    content: ''; display: none; width: 10px; height: 6px;
    border-left: 2px solid #f5f0e8; border-bottom: 2px solid #f5f0e8;
    transform: rotate(-45deg) translate(-50%,-30%); position: absolute; top: 50%; left: 50%;
  }
  .card-check input[type="checkbox"]:checked + label .check-square { background-color: #1a1714; border-color: #1a1714; }
  .card-check input[type="checkbox"]:checked + label .check-square::after { display: block; }

  input[type="text"], input[type="email"] {
    background: transparent; border-bottom: 1.5px solid #6b7c6e;
    outline: none; transition: border-color 0.2s; width: 100%; padding: 10px 0;
    color: #f5f0e8; font-family: 'DM Sans', sans-serif; font-size: 1rem;
  }
  input[type="text"]:focus, input[type="email"]:focus { border-color: #c8893a; }
  input[type="text"]::placeholder, input[type="email"]::placeholder { color: #6b7c6e; }
  input.is-invalid { border-color: #e05c5c !important; }

  /* Error message under fields */
  .error-msg {
    color: #e05c5c;
    font-size: 0.72rem;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .error-msg::before {
    content: '⚠';
    font-size: 0.65rem;
  }

  /* Red border on invalid radio/checkbox cards */
  .card-check.has-error label {
    border-color: rgba(224,92,92,0.5) !important;
  }

  .stagger-1 { animation: fadeUp 0.6s ease 0.1s both; }
  .stagger-2 { animation: fadeUp 0.6s ease 0.2s both; }
  .stagger-3 { animation: fadeUp 0.6s ease 0.3s both; }
  .stagger-4 { animation: fadeUp 0.6s ease 0.4s both; }
  .stagger-5 { animation: fadeUp 0.6s ease 0.5s both; }
  .stagger-6 { animation: fadeUp 0.6s ease 0.6s both; }

  @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
</style>

<body class="grain min-h-screen relative">

  <!-- Background decorative elements -->
  <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full" style="background:radial-gradient(circle,rgba(200,137,58,0.08) 0%,transparent 70%);"></div>
    <div class="absolute -bottom-32 -left-32 w-80 h-80 rounded-full" style="background:radial-gradient(circle,rgba(176,74,47,0.06) 0%,transparent 70%);"></div>
  </div>

  <!-- Banner -->
  <div class="banner-hero">
    <img src="{{ asset('img/vv_banner.png') }}"
         alt="Expression corporelle — Stage Visual Vernacular"
         onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1604881991720-f91add269bed?w=1600&q=85';" />
   
  </div>

  <div class="relative z-10 flex flex-col items-center px-4 py-12" id="resultat">

    {{-- ── Success message ── --}}
    @if (session('success'))
    <div class="w-full max-w-lg mb-8 px-6 py-5 rounded-sm text-center stagger-1"
         style="background:rgba(200,137,58,0.08);border:1px solid rgba(200,137,58,0.3);">
      <svg class="mx-auto mb-3" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c8893a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
      <p class="font-sans text-sm" style="color:#c8893a;font-weight:500;">Inscription reçue !</p>
      <p class="font-sans text-xs mt-1" style="color:#9aaa9c;">Vous recevrez une confirmation par e-mail avec les détails pratiques.</p>
    </div>
    @endif

    {{-- ── Duplicate message ── --}}
    @if (session('duplicate'))
    <div class="w-full max-w-lg mb-8 px-6 py-4 rounded-sm stagger-1"
         style="background:rgba(224,92,92,0.08);border:1px solid rgba(224,92,92,0.3);">
      <p class="font-sans text-sm" style="color:#e05c5c;font-weight:500;">⚠️ Cette adresse e-mail est déjà inscrite au stage.</p>
    </div>
    @endif

    {{-- ── Global validation error summary ── --}}
    @if ($errors->any())
    <div class="w-full max-w-lg mb-8 px-6 py-4 rounded-sm stagger-1"
         style="background:rgba(224,92,92,0.07);border:1px solid rgba(224,92,92,0.25);">
      <p class="font-sans text-xs" style="color:#e05c5c;font-weight:500;">
        ⚠️ Veuillez corriger les erreurs ci-dessous avant de soumettre.
      </p>
    </div>
    @endif

    <!-- Subtitle -->
    <div class="w-full max-w-lg mb-10 text-center stagger-1">
      <p class="font-sans text-sm leading-relaxed" style="color:#9aaa9c;font-weight:300;">
        Stage de découverte et d'initiation — 3 jours pour explorer<br />
        un langage visuel universel et poétique.
      </p>
    </div>

    <!-- Info strip -->
    <div class="w-full max-w-lg mb-10 stagger-2">
      <div class="grid grid-cols-3 gap-0 border border-white border-opacity-10 rounded-sm overflow-hidden">
        <div class="p-4 border-r border-white border-opacity-10">
          <p class="text-xs tracking-widest uppercase mb-1.5" style="color:#c8893a;">Quand</p>
          <p class="font-sans text-xs leading-snug" style="color:#f5f0e8;">6 – 8 mai 2026<br/><span style="color:#9aaa9c;">9h30 – 16h00</span></p>
        </div>
        <div class="p-4 border-r border-white border-opacity-10">
          <p class="text-xs tracking-widest uppercase mb-1.5" style="color:#c8893a;">Où</p>
          <p class="font-sans text-xs leading-snug" style="color:#f5f0e8;">École IRHOV<br/><span style="color:#9aaa9c;">Rue Monulphe 80, Liège</span></p>
        </div>
        <div class="p-4">
          <p class="text-xs tracking-widest uppercase mb-1.5" style="color:#c8893a;">Prix</p>
          <p class="font-sans text-xs leading-snug" style="color:#f5f0e8;">100 €<br/><span style="color:#9aaa9c;">75 € élèves IRHOV</span></p>
        </div>
      </div>
    </div>

    <!-- Form — no HTML required attributes, validation handled by Laravel -->
    <form class="w-full max-w-lg space-y-8"
          method="POST"
          action="{{ route('inscription.visual_vernacular.store') }}">
      @csrf

      <!-- Nom & Prénom -->
      <div class="grid grid-cols-2 gap-6 stagger-3">
        <div>
          <label class="block text-xs tracking-widest uppercase mb-3" style="color:#c8893a;font-weight:500;">
            Nom <span style="color:#e05c5c;">*</span>
          </label>
          <input type="text" name="nom"
                 placeholder="Dupont"
                 value="{{ old('nom') }}"
                 class="{{ $errors->has('nom') ? 'is-invalid' : '' }}" />
          @error('nom')
            <span class="error-msg">{{ $message }}</span>
          @enderror
        </div>
        <div>
          <label class="block text-xs tracking-widest uppercase mb-3" style="color:#c8893a;font-weight:500;">
            Prénom <span style="color:#e05c5c;">*</span>
          </label>
          <input type="text" name="prenom"
                 placeholder="Marie"
                 value="{{ old('prenom') }}"
                 class="{{ $errors->has('prenom') ? 'is-invalid' : '' }}" />
          @error('prenom')
            <span class="error-msg">{{ $message }}</span>
          @enderror
        </div>
      </div>

      <!-- Email -->
      <div class="stagger-4">
        <label class="block text-xs tracking-widest uppercase mb-3" style="color:#c8893a;font-weight:500;">
          Adresse e-mail <span style="color:#e05c5c;">*</span>
        </label>
        <input type="email" name="email"
               placeholder="marie.dupont@email.com"
               value="{{ old('email') }}"
               class="{{ $errors->has('email') ? 'is-invalid' : '' }}" />
        @error('email')
          <span class="error-msg">{{ $message }}</span>
        @enderror
      </div>

      <!-- Profil -->
      <div class="stagger-5">
        <label class="block text-xs tracking-widest uppercase mb-4" style="color:#c8893a;font-weight:500;">
          Profil <span style="color:#e05c5c;">*</span>
        </label>
        <div class="grid grid-cols-2 gap-3">
          <div class="card-check {{ $errors->has('profil') ? 'has-error' : '' }}">
            <input type="radio" name="profil" id="adulte" value="adulte"
                   {{ old('profil') === 'adulte' ? 'checked' : '' }} />
            <label for="adulte" class="flex items-center gap-3 cursor-pointer px-4 py-4 rounded-sm border transition-all duration-200"
                   style="border-color:rgba(107,124,110,0.4);color:#f5f0e8;">
              <span class="check-dot"></span>
              <span class="font-sans text-sm" style="font-weight:500;">Adulte</span>
            </label>
          </div>
          <div class="card-check {{ $errors->has('profil') ? 'has-error' : '' }}">
            <input type="radio" name="profil" id="ado" value="adolescent"
                   {{ old('profil') === 'adolescent' ? 'checked' : '' }} />
            <label for="ado" class="flex items-center gap-3 cursor-pointer px-4 py-4 rounded-sm border transition-all duration-200"
                   style="border-color:rgba(107,124,110,0.4);color:#f5f0e8;">
              <span class="check-dot"></span>
              <span class="font-sans text-sm" style="font-weight:500;">Adolescent</span>
            </label>
          </div>
        </div>
        @error('profil')
          <span class="error-msg mt-2">{{ $message }}</span>
        @enderror
      </div>

      <!-- Élève IRHOV -->
      <div class="stagger-6">
        <div class="card-check">
          <input type="checkbox" name="irhov" id="irhov" value="oui"
                 {{ old('irhov') === 'oui' ? 'checked' : '' }}
                 onchange="toggleIrhovNote(this)" />
          <label for="irhov" class="flex items-start gap-3 cursor-pointer px-5 py-4 rounded-sm border transition-all duration-200 w-full"
                 style="border-color:rgba(107,124,110,0.4);color:#f5f0e8;">
            <span class="check-square mt-0.5"></span>
            <div>
              <p class="font-sans text-sm" style="font-weight:500;">Je suis élève à l'IRHOV</p>
              <p class="font-sans text-xs mt-0.5" style="color:#9aaa9c;">Tarif réduit : 75 € — une preuve à fournir</p>
            </div>
          </label>
        </div>
        <div id="irhov-note" class="{{ old('irhov') === 'oui' ? '' : 'hidden' }} mt-3 px-4 py-3 rounded-sm"
             style="background:rgba(200,137,58,0.08);border-left:2px solid #c8893a;">
          <p class="font-sans text-xs leading-relaxed" style="color:#c8893a;">
            📎 Merci de fournir une preuve de votre statut d'élève IRHOV (carte étudiante, certificat de scolarité) lors de l'inscription ou au début du stage.
          </p>
        </div>
      </div>

      <!-- Submit -->
      <div class="pt-4 stagger-6">
        <button type="submit"
          class="w-full py-4 font-sans text-sm tracking-widest uppercase rounded-sm transition-all duration-300 relative overflow-hidden group"
          style="background:#c8893a;color:#1a1714;font-weight:600;">
          <span class="relative z-10">S'inscrire au stage</span>
          <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background:#b0763a;"></div>
        </button>
        <p class="text-center text-xs mt-4" style="color:#6b7c6e;font-weight:300;">
          En vous inscrivant, vous acceptez d'être recontacté·e pour confirmation et paiement.
        </p>
      </div>

    </form>

    <!-- Footer -->
    <div class="mt-12 text-center stagger-6">
      <p class="font-sans text-xs" style="color:#4a5a4c;font-weight:300;">
        École IRHOV · Rue Monulphe 80 · 4000 Liège
      </p>
    </div>

  </div>

  <script>
    function toggleIrhovNote(checkbox) {
      document.getElementById('irhov-note').classList.toggle('hidden', !checkbox.checked);
    }
  </script>

</x-layout>