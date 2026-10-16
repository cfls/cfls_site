<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Révision du cadrage · {{ $grupos[$grupo] }}</title>
<style>
  :root {
    --bg: #f6f5f2; --panel: #fff; --ink: #1d1d1b; --muted: #6d6a64; --line: #e3e0da;
    --accent: #2f5d50; --warn: #b4532a; --ok: #3d7a4a;
  }
  * { box-sizing: border-box; }
  body { margin: 0; font: 15px/1.45 system-ui, -apple-system, "Segoe UI", sans-serif; background: var(--bg); color: var(--ink); }
  header { position: sticky; top: 0; z-index: 5; background: var(--panel); border-bottom: 1px solid var(--line); padding: 14px 20px; }
  h1 { margin: 0 0 10px; font-size: 18px; font-weight: 600; }
  h1 small { color: var(--muted); font-weight: 400; }
  .tabs { display: flex; flex-wrap: wrap; gap: 6px; }
  .tabs a { padding: 6px 12px; border-radius: 999px; border: 1px solid var(--line); text-decoration: none; color: var(--ink); white-space: nowrap; }
  .tabs a.activo { background: var(--accent); border-color: var(--accent); color: #fff; }
  .tabs a span { opacity: .7; margin-left: 4px; }
  .filtros { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; align-items: center; }
  .filtros input, .filtros select { padding: 6px 10px; border: 1px solid var(--line); border-radius: 6px; font: inherit; background: #fff; }
  .filtros .export { margin-left: auto; color: var(--accent); }
  .status { margin: 14px 20px 0; padding: 10px 14px; background: #e8f1ec; border-radius: 6px; }
  main { padding: 20px; }
  .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
  figure { margin: 0; background: var(--panel); border: 1px solid var(--line); border-radius: 10px; overflow: hidden; display: flex; flex-direction: column; }
  .thumb { position: relative; aspect-ratio: 5/4; background: #222; cursor: pointer; }
  .thumb img { width: 100%; height: 100%; object-fit: contain; display: block; }
  /* línea central de referencia */
  .thumb::after { content: ""; position: absolute; top: 0; bottom: 0; left: 50%; border-left: 1px dashed rgba(255,255,255,.55); }
  .badge { position: absolute; top: 8px; right: 8px; background: rgba(0,0,0,.7); color: #fff; font-size: 12px; padding: 2px 8px; border-radius: 999px; z-index: 1; }
  .badge.rev { left: 8px; right: auto; background: var(--ok); }
  figcaption { padding: 10px 12px 4px; font-size: 13px; overflow-wrap: anywhere; }
  figcaption .meta { color: var(--muted); font-size: 12px; }
  form.acciones { display: flex; flex-wrap: wrap; gap: 6px; padding: 8px 12px 12px; margin-top: auto; }
  form.acciones button { flex: 1; padding: 6px 8px; border: 1px solid var(--line); background: #fff; border-radius: 6px; cursor: pointer; font-size: 13px; }
  form.acciones button.ok:hover { background: var(--ok); color: #fff; border-color: var(--ok); }
  form.acciones button.mal:hover { background: var(--warn); color: #fff; border-color: var(--warn); }
  form.acciones select { flex: 1 0 100%; padding: 5px; border: 1px solid var(--line); border-radius: 6px; font-size: 13px; }
  .vacio { padding: 60px; text-align: center; color: var(--muted); }
  nav.pag { margin-top: 24px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
  nav.pag .pag-btn { padding: 7px 18px; border: 1px solid #2f5d50; border-radius: 6px; background: #e6f0ee; color: #2f5d50; text-decoration: none; font-size: 14px; font-weight: 500; }
  nav.pag .pag-btn:hover { background: #2f5d50; color: #fff; }
  nav.pag .pag-btn[aria-disabled] { opacity: .35; pointer-events: none; }
  dialog { border: 0; padding: 0; border-radius: 10px; width: min(960px, 94vw); background: #000; }
  dialog::backdrop { background: rgba(0,0,0,.7); }
  dialog video { width: 100%; display: block; aspect-ratio: 5/4; object-fit: contain; background: #000; }
  dialog button { position: absolute; top: 8px; right: 8px; z-index: 2; background: #fff; border: 0; border-radius: 999px; width: 32px; height: 32px; cursor: pointer; font-size: 18px; }
  @media (max-width: 600px) { header, main { padding: 12px 16px; } .filtros .export { margin-left: 0; } }
</style>
</head>
<body>
<header>
  <h1>Révision du cadrage <small>· {{ number_format($total) }} vidéos analysées</small></h1>

  <div class="tabs">
    @foreach ($grupos as $clave => $nombre)
      <a href="{{ route('videos.index', ['grupo' => $clave]) }}" @class(['activo' => $clave === $grupo])>
        {{ $nombre }}<span>{{ number_format($totales[$clave] ?? 0) }}</span>
      </a>
    @endforeach
  </div>

  <form class="filtros" method="GET">
    <input type="hidden" name="grupo" value="{{ $grupo }}">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher par titre, slug…">
    <select name="theme_id" onchange="this.form.submit()">
      <option value="">Tous les thèmes</option>
      @foreach ($temas as $t)
        <option value="{{ $t->id }}" @selected(request('theme_id') == $t->id)>{{ $t->title }}</option>
      @endforeach
    </select>
    <select name="syllabu_id" onchange="this.form.submit()">
      <option value="">Tous les syllabus</option>
      @foreach ($syllabus as $s)
        <option value="{{ $s->id }}" @selected(request('syllabu_id') == $s->id)>{{ $s->title }}</option>
      @endforeach
    </select>
    <select name="revisado" onchange="this.form.submit()">
      <option value="">Tous</option>
      <option value="0" @selected(request('revisado') === '0')>Non révisés</option>
      <option value="1" @selected(request('revisado') === '1')>Révisés manuellement</option>
    </select>
    <select name="orden" onchange="this.form.submit()">
      <option value="desviacion" @selected(request('orden', 'desviacion') === 'desviacion')>Plus décentrés d'abord</option>
      <option value="nombre" @selected(request('orden') === 'nombre')>Par nom</option>
      <option value="reciente" @selected(request('orden') === 'reciente')>Plus récents</option>
    </select>
    <a class="export" href="{{ route('videos.exporter', ['grupo' => $grupo]) }}">Télécharger le CSV de ce groupe</a>
  </form>
</header>

@if (session('status'))
  <div class="status">{{ session('status') }}</div>
@endif

<main>
  @if ($videos->isEmpty())
    <p class="vacio">Aucune vidéo dans ce groupe.</p>
  @else
    <div class="grid">
      @foreach ($videos as $v)
        <figure>
          <div class="thumb" data-video="{{ $v->videoUrl() }}">
            @if ($v->revisado)<span class="badge rev">Révisé</span>@endif
            @if (! is_null($v->desviacion))
              <span class="badge">{{ round($v->desviacion * 100) }} %</span>
            @endif
            <img loading="lazy" src="{{ $v->thumbnail() }}" alt="{{ $v->public_id }}">
          </div>
          <figcaption>
            <strong>{{ $v->videoTheme?->title ?? $v->public_id }}</strong>
            @if ($v->videoTheme?->themes?->title)
              <div class="meta">{{ $v->videoTheme->themes->title }}</div>
            @endif
            <div class="meta">
              Personne dans {{ $v->frames_con_persona }}/{{ $v->frames_total }} images
              @if ($v->duracion) · {{ gmdate('i:s', (int) $v->duracion) }} @endif
            </div>
          </figcaption>
          <form class="acciones" method="POST" action="{{ route('videos.marquer', $v) }}">
            @csrf
            @if ($grupo !== 'ok')
              <button class="ok" name="grupo" value="ok">Correct</button>
            @endif
            @if ($grupo !== 'descentrado')
              <button class="mal" name="grupo" value="descentrado">Décentré</button>
            @endif
            <select name="grupo" onchange="this.form.submit()">
              <option value="">Déplacer vers…</option>
              @foreach ($grupos as $clave => $nombre)
                @if ($clave !== $grupo)<option value="{{ $clave }}">{{ $nombre }}</option>@endif
              @endforeach
            </select>
          </form>
        </figure>
      @endforeach
    </div>

    <nav class="pag">
      @if ($videos->previousPageUrl())
        <a class="pag-btn" href="{{ $videos->previousPageUrl() }}">← Précédent</a>
      @else
        <span class="pag-btn" aria-disabled>← Précédent</span>
      @endif

      <small>Page {{ $videos->currentPage() }} sur {{ $videos->lastPage() }}</small>

      @if ($videos->nextPageUrl())
        <a class="pag-btn" href="{{ $videos->nextPageUrl() }}">Suivant →</a>
      @else
        <span class="pag-btn" aria-disabled>Suivant →</span>
      @endif
    </nav>
  @endif
</main>

<dialog id="player">
  <button type="button" onclick="cerrar()" aria-label="Fermer">×</button>
  <video controls playsinline></video>
</dialog>

<script>
  const dlg = document.getElementById('player');
  const vid = dlg.querySelector('video');
  document.querySelectorAll('.thumb').forEach(t => t.addEventListener('click', () => {
    vid.src = t.dataset.video; dlg.showModal(); vid.play();
  }));
  function cerrar() { vid.pause(); vid.removeAttribute('src'); dlg.close(); }
  dlg.addEventListener('close', () => { vid.pause(); vid.removeAttribute('src'); });
  dlg.addEventListener('click', e => { if (e.target === dlg) cerrar(); });
</script>
</body>
</html>
