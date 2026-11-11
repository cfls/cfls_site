<x-layout>
    <x-slot name="title">Êkhô Sport</x-slot>

    <div class="bg-gradient-to-b from-sky-50 to-white dark:from-gray-900 dark:to-gray-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

            {{-- Cabecera --}}
            <header class="text-center pt-14 pb-10">
                <span class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-1.5 text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                    Manuel vidéo · LSFB
                </span>

                <h1 class="mt-5 text-4xl sm:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Êkhô <span class="text-indigo-600 dark:text-indigo-400">Sport</span>
                </h1>

                <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
                    Voici la liste des thèmes et sous-thèmes du manuel d'Êkhô Sport, telle que présentée dans le livre.
                    Choisissez une vidéo pour découvrir les signes.
                </p>

                <div class="mt-8 flex justify-center gap-3">
                    <div class="min-w-[8rem] rounded-2xl bg-white dark:bg-gray-800 px-6 py-3 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700">
                        <div class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $sections->whereNotNull('page')->count() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">thèmes</div>
                    </div>
                    <div class="min-w-[8rem] rounded-2xl bg-white dark:bg-gray-800 px-6 py-3 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700">
                        <div class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $totalVideos }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">vidéos</div>
                    </div>
                </div>
            </header>

            {{-- Índice rápido --}}
            <nav aria-label="Thèmes" id="sport-index"
                 class="sticky top-0 z-10 -mx-4 px-4 py-3 mb-4 flex gap-2 overflow-x-auto bg-white/90 dark:bg-gray-900/90 backdrop-blur border-b border-gray-200 dark:border-gray-700 [scrollbar-width:thin] [scrollbar-color:theme(colors.indigo.300)_transparent]">
                @foreach ($sections as $section)
                    <a href="#theme-{{ $section->id }}"
                       class="shrink-0 whitespace-nowrap rounded-full bg-indigo-50 dark:bg-indigo-900/40 px-3.5 py-1.5 text-sm font-semibold text-indigo-700 dark:text-indigo-300 hover:bg-indigo-600 hover:text-white transition">
                        {{ $section->number ? $section->number . '. ' : '' }}{{ \Illuminate\Support\Str::ucfirst(mb_strtolower($section->title)) }}
                    </a>
                @endforeach
            </nav>

            {{-- Secciones --}}
            @foreach ($sections as $section)
                <section id="theme-{{ $section->id }}" class="pt-10 scroll-mt-20">

                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-500 text-lg font-extrabold text-white shadow-lg shadow-indigo-500/30">
                            {{ $section->number ?? '★' }}
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white leading-tight">
                                {{ $section->title }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                @if ($section->subtitle)
                                    <span class="font-semibold text-indigo-600 dark:text-indigo-400 uppercase">{{ $section->subtitle }}</span>
                                @endif
{{--                                @if ($section->page) Page {{ $section->page }} · @endif--}}
{{--                                {{ $section->subsections->sum('videos_count') }} {{ $section->subsections->sum('videos_count') === 1 ? 'vidéo' : 'vidéos' }}--}}
                            </p>
                        </div>
                    </div>

                    @if ($section->subsections->isNotEmpty())
                        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 {{ $loop->last ? '' : 'pb-10 border-b border-gray-200 dark:border-gray-700' }}">
                            @foreach ($section->subsections as $sub)
                                <a href="{{ route('ekho-sport.show', $sub) }}"
                                   class="group flex flex-col overflow-hidden rounded-2xl bg-white dark:bg-gray-800 ring-1 ring-gray-100 dark:ring-gray-700 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-500/15 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 {{ $sub->videos_count ? '' : 'pointer-events-none opacity-60' }}">

                                    <div class="relative aspect-video overflow-hidden bg-gradient-to-br from-sky-300 to-indigo-600">
                                        @if ($sub->thumbnail_url)
                                            <img src="{{ $sub->thumbnail_url }}" alt="" loading="lazy"
                                                 class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-indigo-950/50"></div>

                                        <span class="absolute left-1/2 top-1/2 flex h-14 w-14 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full bg-white/95 shadow-lg transition group-hover:scale-110">
                                            <svg class="ml-1 h-5 w-5 fill-indigo-600" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                                        </span>

                                        @if ($sub->video_number)
                                            <span class="absolute bottom-2.5 left-3 text-xs font-bold tracking-wider text-white">
                                                N° {{ str_pad($sub->video_number, 2, '0', STR_PAD_LEFT) }}
                                            </span>
                                        @endif
                                        <span class="absolute bottom-2 right-3 rounded-full bg-white/90 px-2.5 py-0.5 text-xs font-bold text-indigo-700">
                                            {{ $sub->videos_count }} {{ $sub->videos_count === 1 ? 'signe' : 'signes' }}
                                        </span>
                                    </div>

                                    <div class="px-5 py-4">
                                        <h3 class="font-bold text-gray-900 dark:text-white leading-snug">{{ $sub->title }}</h3>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
{{--                        <div class="flex items-center gap-3 rounded-2xl border border-dashed border-indigo-200 dark:border-indigo-800 bg-white dark:bg-gray-800 px-5 py-4 text-gray-600 dark:text-gray-300">--}}
{{--                            <svg class="h-5 w-5 shrink-0 stroke-indigo-600 dark:stroke-indigo-400" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">--}}
{{--                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>--}}
{{--                            </svg>--}}
{{--                            <span>À découvrir dans le livre{{ $section->page ? ', page ' . $section->page : '' }}.</span>--}}
{{--                        </div>--}}
                    @endif
                </section>
            @endforeach

        </div>
    </div>

    @push('scripts')
        <script>
            // Rueda vertical del ratón → scroll horizontal del índice
            document.getElementById('sport-index')?.addEventListener('wheel', (e) => {
                const nav = e.currentTarget;
                if (nav.scrollWidth <= nav.clientWidth || Math.abs(e.deltaX) > Math.abs(e.deltaY)) return;
                e.preventDefault();
                nav.scrollLeft += e.deltaY;
            }, { passive: false });
        </script>
    @endpush
</x-layout>
