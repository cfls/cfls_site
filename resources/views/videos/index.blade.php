<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Vidéos Cloudinary</title>
<style>
  :root { --bg:#f6f5f2; --panel:#fff; --ink:#1d1d1b; --muted:#6d6a64; --line:#e3e0da; --accent:#2f5d50; }
  * { box-sizing:border-box; }
  body { margin:0; font:15px/1.45 system-ui,-apple-system,"Segoe UI",sans-serif; background:var(--bg); color:var(--ink); }
  header { position:sticky; top:0; z-index:5; background:var(--panel); border-bottom:1px solid var(--line); padding:14px 20px; display:flex; flex-wrap:wrap; gap:10px; align-items:center; }
  h1 { margin:0; font-size:18px; font-weight:600; flex:1; }
  h1 small { color:var(--muted); font-weight:400; }
  .filtros { display:flex; flex-wrap:wrap; gap:8px; align-items:center; }
  .filtros input, .filtros select { padding:6px 10px; border:1px solid var(--line); border-radius:6px; font:inherit; background:#fff; }
  .filtros button { padding:6px 14px; background:var(--accent); color:#fff; border:0; border-radius:6px; cursor:pointer; font:inherit; }
  main { padding:20px; }
  .grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:16px; }
  .card { background:var(--panel); border:1px solid var(--line); border-radius:10px; overflow:hidden; display:flex; flex-direction:column; }
  .thumb { position:relative; aspect-ratio:16/9; background:#111; cursor:pointer; }
  .thumb img { width:100%; height:100%; object-fit:cover; display:block; }
  .play-icon { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; opacity:0; transition:.2s; }
  .thumb:hover .play-icon { opacity:1; }
  .play-icon svg { width:48px; height:48px; fill:rgba(255,255,255,.9); drop-shadow(0 2px 4px rgba(0,0,0,.5)); }
  .badge-type { position:absolute; top:8px; left:8px; background:rgba(0,0,0,.65); color:#fff; font-size:11px; padding:2px 8px; border-radius:999px; }
  .info { padding:10px 12px 14px; }
  .info .title { font-size:13px; font-weight:600; word-break:break-word; }
  .info .meta { color:var(--muted); font-size:11px; margin-top:4px; }
  .vacio { padding:60px; text-align:center; color:var(--muted); }
  nav.pag { margin-top:24px; display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
  dialog { border:0; padding:0; border-radius:10px; width:min(960px,94vw); background:#000; }
  dialog::backdrop { background:rgba(0,0,0,.75); }
  dialog video { width:100%; display:block; }
  dialog .close-btn { position:absolute; top:8px; right:8px; background:#fff; border:0; border-radius:999px; width:32px; height:32px; cursor:pointer; font-size:18px; line-height:1; }
  @media(max-width:600px){ header,main{padding:12px 16px;} }
</style>
</head>
<body>

<header>
  <h1>Vidéos Cloudinary <small>· {{ number_format($total) }} actives</small></h1>

  <form class="filtros" method="GET">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher…">
    <select name="orden" onchange="this.form.submit()">
      <option value="title"   @selected(request('orden','title') === 'title')>A → Z</option>
      <option value="reciente" @selected(request('orden') === 'reciente')>Plus récents</option>
    </select>
    <button type="submit">Filtrer</button>
  </form>
</header>

<main>
  @if ($videos->isEmpty())
    <p class="vacio">Aucune vidéo active trouvée.</p>
  @else
    <div class="grid">
      @foreach ($videos as $video)
        @php
          $url   = $video->code_video ?? '';
          $thumb = $url ? preg_replace('#(/upload/)#', '$1so_50p,w_480,c_scale,q_auto,f_jpg/', $url) : '';
          $thumb = $thumb ? preg_replace('/\.\w+$/', '.jpg', $thumb) : '';
        @endphp
        <div class="card">
          <div class="thumb" data-video="{{ $url }}">
            @if ($thumb)
              <img loading="lazy" src="{{ $thumb }}" alt="{{ $video->title }}">
            @endif
            <div class="play-icon">
              <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </div>
            @if ($video->type && $video->type !== 'principal')
              <span class="badge-type">{{ $video->type }}</span>
            @endif
          </div>
          <div class="info">
            <div class="title">{{ $video->display_title }}</div>
            <div class="meta">ID: {{ $video->id }} &nbsp;·&nbsp; {{ $video->type }}</div>
          </div>
        </div>
      @endforeach
    </div>

    <nav class="pag">
      {{ $videos->links('pagination::simple-default') }}
      <small style="color:var(--muted)">Page {{ $videos->currentPage() }} / {{ $videos->lastPage() }}</small>
    </nav>
  @endif
</main>

<dialog id="player">
  <button class="close-btn" type="button" onclick="cerrar()">×</button>
  <video controls playsinline></video>
</dialog>

<script>
const dlg = document.getElementById('player');
const vid = dlg.querySelector('video');

document.querySelectorAll('.thumb[data-video]').forEach(t => {
  t.addEventListener('click', () => {
    if (!t.dataset.video) return;
    vid.src = t.dataset.video;
    dlg.showModal();
    vid.play();
  });
});

function cerrar() { vid.pause(); vid.removeAttribute('src'); dlg.close(); }
dlg.addEventListener('close', () => { vid.pause(); vid.removeAttribute('src'); });
dlg.addEventListener('click', e => { if (e.target === dlg) cerrar(); });
</script>
</body>
</html>
