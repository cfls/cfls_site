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

{{--                <div class="mt-8 flex justify-center gap-3">--}}
{{--                    <div class="min-w-[8rem] rounded-2xl bg-white dark:bg-gray-800 px-6 py-3 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700">--}}
{{--                        <div class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $sections->whereNotNull('page')->count() }}</div>--}}
{{--                        <div class="text-sm text-gray-500 dark:text-gray-400">thèmes</div>--}}
{{--                    </div>--}}
{{--                    <div class="min-w-[8rem] rounded-2xl bg-white dark:bg-gray-800 px-6 py-3 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700">--}}
{{--                        <div class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $totalVideos }}</div>--}}
{{--                        <div class="text-sm text-gray-500 dark:text-gray-400">vidéos</div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </header>

            {{-- Índice rápido --}}
            <nav aria-label="Thèmes"
                 x-data="{
                     start: true,
                     end: false,
                     update() {
                         const el = this.$refs.track;
                         this.start = el.scrollLeft <= 2;
                         this.end = el.scrollLeft + el.clientWidth >= el.scrollWidth - 2;
                     },
                     go(dir) { this.$refs.track.scrollBy({ left: dir * this.$refs.track.clientWidth * 0.7, behavior: 'smooth' }); },
                 }"
                 x-init="$nextTick(() => update())"
                 @resize.window="update()"
                 class="sticky top-0 z-10 -mx-4 px-4 py-3 mb-4 flex items-center gap-2 bg-white/90 dark:bg-gray-900/90 backdrop-blur border-b border-gray-200 dark:border-gray-700">
                <button type="button" @click="go(-1)" x-show="!start" x-cloak aria-label="Thèmes précédents"
                        class="shrink-0 flex h-8 w-8 items-center justify-center rounded-full bg-white dark:bg-gray-800 shadow ring-1 ring-gray-200 dark:ring-gray-700 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-600 hover:text-white transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
                </button>

                <div x-ref="track"
                     @scroll.passive="update()"
                     @wheel="if (Math.abs($event.deltaY) > Math.abs($event.deltaX) && !(($event.deltaY < 0 && start) || ($event.deltaY > 0 && end))) { $event.preventDefault(); $refs.track.scrollLeft += $event.deltaY; }"
                     class="flex flex-1 gap-2 overflow-x-auto overscroll-x-contain touch-pan-x [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    @foreach ($sections as $section)
                        <a href="#theme-{{ $section->id }}"
                           class="shrink-0 whitespace-nowrap rounded-full bg-indigo-50 dark:bg-indigo-900/40 px-3.5 py-1.5 text-sm font-semibold text-indigo-700 dark:text-indigo-300 hover:bg-indigo-600 hover:text-white transition">
                            {{ $section->number ? $section->number . '. ' : '' }}{{ \Illuminate\Support\Str::ucfirst(mb_strtolower($section->title)) }}
                        </a>
                    @endforeach
                </div>

                <button type="button" @click="go(1)" x-show="!end" x-cloak aria-label="Thèmes suivants"
                        class="shrink-0 flex h-8 w-8 items-center justify-center rounded-full bg-white dark:bg-gray-800 shadow ring-1 ring-gray-200 dark:ring-gray-700 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-600 hover:text-white transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </nav>

            {{-- Secciones --}}
            @foreach ($sections as $section)
                <section id="theme-{{ $section->id }}" class="pt-10 scroll-mt-20 {{ $section->subsections->isNotEmpty() && ! $loop->last ? 'pb-10 border-b border-gray-200 dark:border-gray-700' : '' }}">

                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex h-[52px] w-[52px] shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-500 text-lg font-extrabold text-white shadow-lg shadow-indigo-500/30">
                            {{ $section->number ?? '★' }}
                        </div>
                        <div>
                            <h2 class="{{ $section->subsections->isEmpty() ? 'inline-block rounded-lg bg-indigo-100 dark:bg-indigo-900/60 px-3 py-1' : '' }} text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white leading-tight">
                                {{ $section->title }}
                            </h2>
                            @if ($section->subtitle)
                                <p class="mt-1 text-sm font-extrabold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">
                                    {{ $section->subtitle }}
                                </p>
                            @endif
                        </div>
                    </div>

                    @if ($section->subsections->isNotEmpty())
                        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
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
                    @endif
                </section>
            @endforeach

        </div>
    </div>
</x-layout>
