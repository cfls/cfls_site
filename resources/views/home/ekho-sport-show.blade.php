<x-layout>
    <x-slot name="title">{{ $subsection->title }} · Êkhô Sport</x-slot>

    @php
        $section = $subsection->section;
        $videos  = $subsection->videos;
        $first   = $videos->first();
    @endphp

    <div class="bg-gradient-to-b from-sky-50 to-white dark:from-gray-900 dark:to-gray-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

            {{-- Fil d'Ariane --}}
            <nav aria-label="Fil d'Ariane" class="flex flex-wrap items-center gap-2 pt-8 pb-5 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('ekho-sport') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Êkhô Sport</a>
                <span>›</span>
                <a href="{{ route('ekho-sport') }}#theme-{{ $section->id }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                    {{ $section->number ? $section->number . '. ' : '' }}{{ $section->title }}
                </a>
                <span>›</span>
                <span>{{ $subsection->title }}</span>
            </nav>

            {{-- Título --}}
            <div class="mb-6">
                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                    {{ $section->title }} · {{ $videos->count() }} {{ $videos->count() === 1 ? 'signe' : 'signes' }}
                </p>
                <h1 class="mt-1.5 text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight">
                    {{ $subsection->title }}
                </h1>
                @if ($subsection->content)
                    <div class="mt-3 max-w-3xl text-gray-600 dark:text-gray-300 leading-relaxed">
                        {!! nl2br(e($subsection->content)) !!}
                    </div>
                @endif
            </div>

            @if ($first)
                <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_340px] items-start">

                    {{-- Reproductor --}}
                    <main>
                        <div class="overflow-hidden rounded-2xl bg-indigo-950 shadow-2xl shadow-indigo-950/25">
                            <video id="ekho-video" controls playsinline preload="metadata"
                                   src="{{ $first->url }}" poster="{{ $first->thumbnail_url }}"
                                   class="block aspect-video w-full bg-indigo-950"></video>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                    Signe <span id="ekho-pos">1</span> / {{ $videos->count() }}
                                </p>
                                <h2 id="ekho-title" class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $first->title }}</h2>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <div class="inline-flex items-center rounded-xl bg-white dark:bg-gray-800 p-1 ring-1 ring-gray-200 dark:ring-gray-700" role="group" aria-label="Vitesse de lecture">
                                    <span class="px-2 text-sm text-gray-500 dark:text-gray-400">Vitesse</span>
                                    @foreach (['0.5', '0.75', '1'] as $speed)
                                        <button type="button" data-speed="{{ $speed }}"
                                                class="ekho-speed rounded-lg px-3 py-1.5 text-sm font-bold transition {{ $speed === '1' ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-gray-700' }}">
                                            {{ $speed }}×
                                        </button>
                                    @endforeach
                                </div>

                                <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 ring-1 ring-gray-200 dark:ring-gray-700">
                                    <input id="ekho-loop" type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500">
                                    En boucle
                                </label>

                                <button id="ekho-prev" type="button" aria-label="Signe précédent"
                                        class="rounded-xl bg-white dark:bg-gray-800 px-3 py-2 font-bold text-gray-700 dark:text-gray-200 ring-1 ring-gray-200 dark:ring-gray-700 hover:ring-indigo-500">←</button>
                                <button id="ekho-next" type="button" aria-label="Signe suivant"
                                        class="rounded-xl bg-indigo-600 px-3 py-2 font-bold text-white hover:bg-indigo-700">→</button>
                            </div>
                        </div>
                    </main>

                    {{-- Lista de signos --}}
                    <aside class="hidden lg:block overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 lg:sticky lg:top-6">
                        <div class="bg-indigo-50 dark:bg-indigo-900/40 px-5 py-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Les signes</p>
                            <h2 class="mt-1 font-extrabold text-gray-900 dark:text-white">{{ $subsection->title }}</h2>
                        </div>
                        <ol class="max-h-[60vh] overflow-y-auto p-2">
                            @foreach ($videos as $video)
                                <li>
                                    <button type="button"
                                            class="ekho-item flex w-full items-center gap-3 rounded-xl p-2 text-left text-sm transition {{ $loop->first ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-sky-50 dark:hover:bg-gray-700' }}"
                                            data-index="{{ $loop->index }}"
                                            data-src="{{ $video->url }}"
                                            data-poster="{{ $video->thumbnail_url }}"
                                            data-title="{{ $video->title }}"
                                            @if ($loop->first) aria-current="true" @endif>
                                        <span class="relative h-12 w-20 shrink-0 overflow-hidden rounded-lg bg-gradient-to-br from-sky-300 to-indigo-600">
                                            @if ($video->thumbnail_url)
                                                <img src="{{ $video->thumbnail_url }}" alt="" loading="lazy" class="h-full w-full object-cover">
                                            @endif
                                        </span>
                                        <span class="font-semibold">{{ $video->title }}</span>
                                    </button>
                                </li>
                            @endforeach
                        </ol>
                    </aside>
                </div>
            @else
                <div class="flex aspect-video max-w-3xl items-center justify-center rounded-2xl bg-indigo-950 text-indigo-200">
                    Vidéos bientôt disponibles
                </div>
            @endif

            {{-- Sous-thème précédent / suivant --}}
            <nav aria-label="Navigation entre sous-thèmes" class="mt-12 grid gap-3 sm:grid-cols-2">
                @if ($previous)
                    <a href="{{ route('ekho-sport.show', $previous) }}"
                       class="rounded-xl bg-white dark:bg-gray-800 px-5 py-4 ring-1 ring-gray-200 dark:ring-gray-700 transition hover:ring-indigo-500 hover:shadow-md">
                        <span class="block text-xs font-semibold uppercase tracking-wide text-gray-400">← Sous-thème précédent</span>
                        <span class="mt-1 block font-bold text-gray-900 dark:text-white">{{ $previous->title }}</span>
                    </a>
                @endif
                @if ($next)
                    <a href="{{ route('ekho-sport.show', $next) }}"
                       class="rounded-xl bg-white dark:bg-gray-800 px-5 py-4 text-right ring-1 ring-gray-200 dark:ring-gray-700 transition hover:ring-indigo-500 hover:shadow-md sm:col-start-2">
                        <span class="block text-xs font-semibold uppercase tracking-wide text-gray-400">Sous-thème suivant →</span>
                        <span class="mt-1 block font-bold text-gray-900 dark:text-white">{{ $next->title }}</span>
                    </a>
                @endif
            </nav>
        </div>
    </div>

    @if ($first)
        <script>
            (function () {
                const video = document.getElementById('ekho-video');
                const title = document.getElementById('ekho-title');
                const pos   = document.getElementById('ekho-pos');
                const loop  = document.getElementById('ekho-loop');
                const items = Array.from(document.querySelectorAll('.ekho-item'));
                const speeds = document.querySelectorAll('.ekho-speed');

                const itemOn  = ['bg-indigo-600', 'text-white'];
                const itemOff = ['text-gray-700', 'dark:text-gray-300', 'hover:bg-sky-50', 'dark:hover:bg-gray-700'];
                let current = 0;
                let rate = 1;

                function play(i, autoplay = true) {
                    if (i < 0 || i >= items.length) return;
                    const item = items[i];
                    items.forEach(el => { el.classList.remove(...itemOn); el.classList.add(...itemOff); el.removeAttribute('aria-current'); });
                    item.classList.remove(...itemOff);
                    item.classList.add(...itemOn);
                    item.setAttribute('aria-current', 'true');
                    if (item.offsetParent) item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });

                    video.src = item.dataset.src;
                    video.poster = item.dataset.poster || '';
                    video.playbackRate = rate;
                    title.textContent = item.dataset.title;
                    pos.textContent = i + 1;
                    current = i;
                    if (autoplay) video.play().catch(() => {});
                }

                items.forEach((item, i) => item.addEventListener('click', () => play(i)));
                document.getElementById('ekho-prev').addEventListener('click', () => play(current - 1));
                document.getElementById('ekho-next').addEventListener('click', () => play(current + 1));

                loop.addEventListener('change', () => { video.loop = loop.checked; });

                speeds.forEach(btn => btn.addEventListener('click', () => {
                    rate = parseFloat(btn.dataset.speed);
                    video.playbackRate = rate;
                    speeds.forEach(b => { b.classList.remove('bg-indigo-600', 'text-white'); b.classList.add('text-gray-700', 'dark:text-gray-200', 'hover:bg-indigo-50', 'dark:hover:bg-gray-700'); });
                    btn.classList.remove('text-gray-700', 'dark:text-gray-200', 'hover:bg-indigo-50', 'dark:hover:bg-gray-700');
                    btn.classList.add('bg-indigo-600', 'text-white');
                }));

                // Al terminar un signo, pasa al siguiente (si no está en bucle)
                video.addEventListener('ended', () => { if (!video.loop) play(current + 1); });
            })();
        </script>
    @endif
</x-layout>
