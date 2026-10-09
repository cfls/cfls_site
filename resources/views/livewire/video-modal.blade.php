<flux:modal
        wire:model="open"
        x-data="{
        videoUrl: null,
        posterUrl: null,
        videoLoaded: false,
        currentSpeed: 1,
        currentIdx: 0,

        currentTitle() {
            const v = ($wire.videos ?? [])[this.currentIdx];
            if (!v) return $wire.displayTitle ?? '';
            return / \(\d+\)$/.test(v.title)
                ? v.title.replace(/ \(\d+\)$/, '')
                : v.title;
        },

        loadVideo(idx) {
            const videos = $wire.videos ?? [];
            if (!videos[idx]) return;
            const v = videos[idx];
            this.videoLoaded = false;
            this.videoUrl = null;
            this.posterUrl = null;
            setTimeout(() => {
                this.videoUrl = v.url.replace('w_1280', 'w_640');
                this.posterUrl = v.poster;
            }, 100);
        },

        goNext() {
            const total = ($wire.videos ?? []).length;
            if (this.currentIdx < total - 1) {
                if ($refs.myVideo) { $refs.myVideo.pause(); $refs.myVideo.src = ''; }
                this.currentIdx++;
                this.loadVideo(this.currentIdx);
            }
        },

        goPrev() {
            if (this.currentIdx > 0) {
                if ($refs.myVideo) { $refs.myVideo.pause(); $refs.myVideo.src = ''; }
                this.currentIdx--;
                this.loadVideo(this.currentIdx);
            }
        }
    }"
        x-init="
        $watch('$wire.open', isOpen => {
            if (isOpen && $wire.videos && $wire.videos.length > 0) {
                currentIdx = 0;
                loadVideo(0);
            }

            if (!isOpen) {
                if ($refs.myVideo) {
                    $refs.myVideo.pause();
                    $refs.myVideo.src = '';
                }

                videoUrl = null;
                videoLoaded = false;
                currentSpeed = 1;
                currentIdx = 0;
            }
        })
    "
>
    <div class="space-y-4">

        {{-- Título --}}
        <h2 class="text-xl font-semibold text-center" x-text="currentTitle()"></h2>

        {{-- Current Speed --}}
        <div class="text-center text-sm font-medium text-gray-700 dark:text-gray-200">
            Vitesse actuelle : <span x-text="currentSpeed + 'x'"></span>
        </div>

        {{-- Contenedor del video --}}
        <div class="relative w-full aspect-video">

            {{-- Skeleton mientras carga --}}
            <div
                    x-show="!videoLoaded"
                    class="absolute inset-0 bg-gray-900 rounded-lg flex items-center justify-center"
            >
                <div class="flex flex-col items-center gap-3">
                    <svg class="w-12 h-12 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                        ></circle>
                        <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                        ></path>
                    </svg>

                    <span class="text-white text-sm font-medium">
                        Chargement...
                    </span>
                </div>
            </div>

            {{-- Video --}}
            <video
                    x-ref="myVideo"
                    x-bind:src="videoUrl"
                    x-bind:poster="posterUrl"
                    class="w-full rounded-lg transition-opacity duration-300"
                    :class="{ 'opacity-0': !videoLoaded }"
                    autoplay
                    muted
                    loop
                    playsinline
                    preload="metadata"
                    @loadeddata="
                    videoLoaded = true;
                    $refs.myVideo.playbackRate = currentSpeed;
                "
            ></video>
        </div>

        {{-- Controles de carrusel (solo cuando hay varios vídeos) --}}
        @if(count($videos) > 1)
            <div class="flex justify-center items-center gap-4 pt-1">
                <button
                        type="button"
                        @click="goPrev()"
                        :disabled="currentIdx === 0"
                        class="p-2 rounded-lg border border-gray-300 dark:border-zinc-600 hover:bg-gray-100 dark:hover:bg-zinc-700 disabled:opacity-40 transition"
                        aria-label="Vidéo précédente"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <span
                        class="text-sm font-medium text-gray-600 dark:text-gray-300 min-w-[3rem] text-center"
                        x-text="(currentIdx + 1) + ' / ' + {{ count($videos) }}"
                ></span>

                <button
                        type="button"
                        @click="goNext()"
                        :disabled="currentIdx === {{ count($videos) - 1 }}"
                        class="p-2 rounded-lg border border-gray-300 dark:border-zinc-600 hover:bg-gray-100 dark:hover:bg-zinc-700 disabled:opacity-40 transition"
                        aria-label="Vidéo suivante"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Synonymes --}}
        @if(count($synonyms) > 0)
            <div class="text-center pt-1">
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Synonymes :</span>
                <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">
                    {{ implode(' · ', $synonyms) }}
                </span>
            </div>
        @endif

        {{-- Botones velocidad --}}
        <div class="flex justify-center gap-2">
            <button
                    type="button"
                    class="px-3 py-1.5 text-sm rounded-lg border"
                    @click="currentSpeed = 0.5; $refs.myVideo.playbackRate = currentSpeed"
            >
                0.5x
            </button>

            <button
                    type="button"
                    class="px-3 py-1.5 text-sm rounded-lg border"
                    @click="currentSpeed = 0.75; $refs.myVideo.playbackRate = currentSpeed"
            >
                0.75x
            </button>

            <button
                    type="button"
                    class="px-3 py-1.5 text-sm rounded-lg border"
                    @click="currentSpeed = 1; $refs.myVideo.playbackRate = currentSpeed"
            >
                1x
            </button>
        </div>

    </div>
</flux:modal>
