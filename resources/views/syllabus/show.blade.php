<x-layout>
    <x-slot name="title">Syllabus</x-slot>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <div class="flex flex-col min-h-screen px-4"
         x-data="tabSwitcher(
             {{ Js::from($videos) }},
             {{ Js::from($annexVideos) }}
         )">

        <div class="flex-1 px-4 py-8">
            @if(count($annexVideos) === 0)
                <div class="flex justify-center">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-200 mb-5 text-3xl md:text-5xl lg:text-6xl uppercase text-center">
                        {{ $themeModel->title }}
                    </h2>
                </div>
            @endif

            {{-- ✅ Botones de selección — solo si hay anexos --}}
            @if(count($annexVideos) > 0)
                <div class="container mx-auto flex justify-center gap-4 mb-8">
                    <button
                            @click="switchTab('principal')"
                            :class="activeTab === 'principal'
                            ? 'bg-blue-600 text-white shadow-lg scale-105'
                            : 'bg-white text-gray-700 dark:bg-gray-700 dark:text-gray-200 border border-gray-300'"
                            class="px-8 py-4 rounded-xl text-lg font-bold transition-all duration-200 uppercase">
                        {{ $themeModel->title }}
                    </button>
                    <button
                            @click="switchTab('annexe')"
                            :class="activeTab === 'annexe'
                            ? 'bg-blue-600 text-white shadow-lg scale-105'
                            : 'bg-white text-gray-700 dark:bg-gray-700 dark:text-gray-200 border border-gray-300'"
                            class="px-8 py-4 rounded-xl text-lg font-bold transition-all duration-200 uppercase">
                        Pour en savoir plus
                    </button>
                </div>
            @endif

            {{-- Player + lista --}}
            <div class="flex flex-col lg:flex-row justify-center items-start gap-x-4 w-full">

                <button onclick="window.history.back()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </button>

                <div class="flex flex-col lg:flex-row gap-6 w-full max-w-screen-xl mx-auto">

                    <!-- Video Player -->
                    <div class="p-4 bg-white rounded-md shadow-md dark:bg-gray-800 w-full max-w-3xl mx-auto">
                        <div class="flex justify-center mb-10">
                            <button @click="togglePlayPause"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
                                    x-text="isPlaying ? '⏸ Pause' : '▶ Reprendre'">
                            </button>
                        </div>
                        <div class="flex justify-center mb-10">
                            <video x-ref="videoPlayer"
                                   class="w-[480px] h-[300px] object-cover"
                                   @ended="handleEnded"
                                   @play="isPlaying = true"
                                   @pause="isPlaying = false"
                                   muted
                                   playsinline webkit-playsinline
                                   controlsList="nodownload">
                                <source :src="currentVideo" type="video/mp4">
                            </video>
                        </div>
                        <h2 class="mb-4 md:text-2xl lg:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white text-center uppercase mt-10"
                            x-text="currentTitle"></h2>
                        <div class="flex justify-center mb-10 gap-4">
                            <button @click="setSpeed(0.5)"
                                    :class="playbackSpeed === 0.5 ? 'bg-yellow-600' : 'bg-gray-400 dark:bg-gray-600'"
                                    class="px-4 py-2 text-white rounded-md transition">⏱ Lent</button>
                            <button @click="setSpeed(1)"
                                    :class="playbackSpeed === 1 ? 'bg-green-600' : 'bg-gray-400 dark:bg-gray-600'"
                                    class="px-4 py-2 text-white rounded-md transition">▶ Normal</button>
                            <button @click="setSpeed(1.5)"
                                    :class="playbackSpeed === 1.5 ? 'bg-red-600' : 'bg-gray-400 dark:bg-gray-600'"
                                    class="px-4 py-2 text-white rounded-md transition">🚀 Rapide</button>
                        </div>
                    </div>

                    <!-- Scrollable List -->
                    <div class="flex flex-col w-full max-w-xs lg:w-80">
                        <div class="flex justify-center mb-4 gap-4">
                            <button @click="toggleAutoPlayNext"
                                    :disabled="searchQuery.length > 0"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    x-text="autoPlayNext ? '🖱 Manuel' : '⏩ Automatique'">
                            </button>
                        </div>
                        <div class="mb-4">
                            <input type="text" x-model="searchQuery" placeholder="Rechercher..."
                                   class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                        </div>
                        <div x-ref="scrollContainer"
                             class="w-full max-w-md mx-auto space-y-4 overflow-y-auto px-2 max-h-60 sm:max-h-72 md:max-h-80 lg:max-h-96">
                            <template x-for="(video, index) in filteredVideos" :key="video.id">
                                <div @click="setVideoByUrl(video.url_video)"
                                     :class="currentVideo === video.url_video ? 'bg-blue-100 dark:bg-blue-900' : ''"
                                     class="item pb-3 sm:pb-4 hover:bg-gray-200 rounded-lg dark:hover:bg-gray-700 p-2 cursor-pointer relative transition">
                                    <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-base font-medium text-gray-900 truncate dark:text-white"
                                               x-text="video.title"></p>
                                        </div>
                                        <template x-if="currentVideo === video.url_video">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                 viewBox="0 0 24 24" class="size-5 text-green-500 animate-pulse">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function tabSwitcher(principalVideos, annexVideos) {
                return {
                    activeTab: 'principal',
                    videos: [],
                    currentIndex: 0,
                    isPlaying: false,
                    autoPlayNext: true,
                    repeatCount: 0,
                    playbackSpeed: 1.0,
                    searchQuery: '',

                    init() {
                        this.videos = principalVideos;
                        this.isPlaying = true;
                        this.autoPlayNext = true;

                        // ✅ Mute para permitir autoplay sin interacción
                        this.$refs.videoPlayer.muted = true;
                        this.playCurrent();

                        // ✅ Desmutear en la primera interacción del usuario
                        const unmute = () => {
                            this.$refs.videoPlayer.muted = false;
                            document.removeEventListener('click', unmute);
                            document.removeEventListener('keydown', unmute);
                        };
                        document.addEventListener('click', unmute);
                        document.addEventListener('keydown', unmute);
                    },
                    switchTab(tab) {
                        if (this.activeTab === tab) return;
                        this.activeTab = tab;
                        this.videos = tab === 'principal' ? principalVideos : annexVideos;
                        this.currentIndex = 0;
                        this.repeatCount = 0;
                        this.searchQuery = '';
                        this.isPlaying = true;
                        this.$refs.videoPlayer.pause();
                        this.playCurrent();
                    },

                    get filteredVideos() {
                        if (!this.searchQuery.trim()) return this.videos;
                        const q = this.searchQuery.toLowerCase();
                        return this.videos.filter(v => v.title.toLowerCase().includes(q));
                    },

                    get currentVideo() {
                        return this.videos[this.currentIndex]?.url_video || '';
                    },

                    get currentTitle() {
                        return this.videos[this.currentIndex]?.title || '';
                    },

                    playCurrent() {
                        const player = this.$refs.videoPlayer;
                        const source = player.querySelector('source');
                        source.src = this.currentVideo;
                        player.load();
                        player.onloadedmetadata = () => {
                            player.playbackRate = this.playbackSpeed;
                            if (this.isPlaying) {
                                player.play().catch(e => console.log('Blocked:', e));
                            }
                        };
                    },

                    handleEnded() {
                        this.repeatCount++;
                        if (this.repeatCount < 2) {
                            const player = this.$refs.videoPlayer;
                            player.currentTime = 0;
                            player.play().catch(e => console.log('Blocked:', e));
                        } else {
                            this.repeatCount = 0;
                            this.nextVideo(); // ✅ siempre avanza, sin depender de autoPlayNext
                        }
                    },

                    nextVideo() {
                        this.currentIndex = (this.currentIndex + 1) % this.videos.length;
                        this.repeatCount = 0;
                        this.isPlaying = true; // ✅ asegurar estado
                        this.playCurrent();
                        this.scrollToActive();
                    },

                    setVideoByUrl(url) {
                        const index = this.videos.findIndex(v => v.url_video === url);
                        if (index === -1) return;
                        if (index === this.currentIndex) {
                            this.togglePlayPause();
                            return;
                        }
                        this.currentIndex = index;
                        this.repeatCount = 0;
                        this.playCurrent();
                        this.scrollToActive();
                    },

                    togglePlayPause() {
                        const player = this.$refs.videoPlayer;
                        if (player.paused) {
                            player.play().catch(e => console.log('Blocked:', e));
                            this.isPlaying = true;
                        } else {
                            player.pause();
                            this.isPlaying = false;
                        }
                    },

                    toggleAutoPlayNext() {
                        this.autoPlayNext = !this.autoPlayNext;
                        if (!this.isPlaying) this.togglePlayPause();
                        this.playCurrent();
                    },

                    setSpeed(speed) {
                        this.playbackSpeed = speed;
                        this.$refs.videoPlayer.playbackRate = speed;
                    },

                    scrollToActive() {
                        this.$nextTick(() => {
                            const container = this.$refs.scrollContainer;
                            const items = container.querySelectorAll('div.item');
                            const currentItem = items[this.currentIndex];
                            if (container && currentItem) {
                                const offset = currentItem.offsetTop - container.offsetTop;
                                const scrollTop = offset - (container.clientHeight / 2) + (currentItem.offsetHeight / 2);
                                container.scrollTo({ top: scrollTop, behavior: 'smooth' });
                            }
                        });
                    }
                };
            }
        </script>
    @endpush
</x-layout>