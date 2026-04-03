<x-layout>
    <x-slot name="title">Syllabus</x-slot>
    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <!-- Alpine Core -->


    <div class="flex flex-col lg:flex-row min-h-screen gap-4 px-4">
        <!-- Main Content -->
        <div class="flex-1 p-4">
            <div class="flex justify-center">
                <h2 class="font-extrabold tracking-tight text-gray-800 dark:text-gray-200 mb-5 md:text-7xl text-3xl uppercase">{{ $themeModel->title }}</h2>
            </div>

            <div class="flex flex-col lg:flex-row justify-center items-start gap-x-4 w-full"
                 x-data="videoPlaylist({{ Js::from($videos) }})">

                <button onclick="window.history.back()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </button>

                <div class="flex flex-col lg:flex-row gap-6 w-full max-w-screen-xl mx-auto">
                    <!-- Video Player -->
                    <div class="p-4 bg-white rounded-md shadow-md dark:bg-gray-800 w-full max-w-3xl mx-auto">
                        <div class="flex justify-center mb-10">
                            <button
                                @click="togglePlayPause"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
                                x-text="isPlaying ? '▶ Reprendre' : '⏸ Pause'">
                            </button>
                        </div>
                        <div class="flex justify-center mb-10">
                            <video
                                x-ref="videoPlayer"
                                class="w-[480px] h-[300px] object-cover"
                                @ended="handleEnded"
                                @play="isPlaying = false"
                                @pause="isPlaying = true"
                                playsinline
                                webkit-playsinline                               
                                controlsList="nodownload"
                            >
                                <source :src="currentVideo" type="video/mp4">
                                Tu navigateur ne supporte pas la vidéo.
                            </video>
                        </div>
                        <h2 class="mb-4  md:text-2xl  lg:text-4xl  font-extrabold tracking-tight text-gray-900 dark:text-white text-center uppercase mt-10"
                            x-text="currentTitle"></h2>
                                       <div class="flex justify-center mb-10 gap-4">
                                                    <button
                                                        @click="setSpeed(0.5)"
                                                        :class="playbackSpeed === 0.5 
                                                            ? 'bg-yellow-600' 
                                                            : 'bg-gray-400 dark:bg-gray-600'"
                                                        class="px-4 py-2 text-white rounded-md transition"
                                                    >
                                                        ⏱ Lent
                                                    </button>

                                                    <button
                                                        @click="setSpeed(1)"
                                                        :class="playbackSpeed === 1 
                                                            ? 'bg-green-600' 
                                                            : 'bg-gray-400 dark:bg-gray-600'"
                                                        class="px-4 py-2 text-white rounded-md transition"
                                                    >
                                                        ▶ Normal
                                                    </button>

                                                    <button
                                                        @click="setSpeed(1.5)"
                                                        :class="playbackSpeed === 1.5 
                                                            ? 'bg-red-600' 
                                                            : 'bg-gray-400 dark:bg-gray-600'"
                                                        class="px-4 py-2 text-white rounded-md transition"
                                                    >
                                                        🚀 Vite
                                                    </button>
                                                </div>
                        </div>
                  

                    <!-- Scrollable List -->
                    <div class="flex flex-col w-full max-w-xs lg:w-80">
                        <div class="flex justify-center mb-4 gap-4">
                            <button
                                @click="toggleAutoPlayNext"
                                :disabled="searchQuery.length > 0"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                x-text="autoPlayNext ? '🖱 Manuel' : '⏩ Automatique'">
                            </button>
                          
                        </div>
                        <div class="mb-4">
                            <input
                                type="text"
                                x-model="searchQuery"
                                placeholder="Rechercher..."
                                class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white"
                            >
                        </div>

                        <div
                            x-ref="scrollContainer"
                            class="w-full max-w-md mx-auto space-y-4 overflow-y-auto px-2 max-h-60 sm:max-h-72 md:max-h-80 lg:max-h-96"
                        >
                            <template x-for="(video, index) in filteredVideos" :key="video.id">
                                <div
                                    @click="setVideoByUrl(video.url)"
                                    :class="currentVideo === video.url ? 'bg-blue-100 dark:bg-blue-900' : ''"
                                    class="item pb-3 sm:pb-4 hover:bg-gray-200 rounded-lg dark:hover:bg-gray-700 p-2 cursor-pointer relative transition"
                                >
                                    <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-base font-medium text-gray-900 truncate dark:text-white"
                                               x-text="video.title">
                                            </p>
                                        </div>
                                        <template x-if="currentVideo === video.url">
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
            function videoPlaylist(themes) {
                const allVideos = themes.map(video => ({
                    id: video.id,
                    url: video.url_video,
                    title: video.title
                }));

                return {
                    videos: allVideos,
                    currentIndex: 0,
                    isPlaying: true,
                    autoPlayNext: true,
                    repeatCount: 0,
                    playbackSpeed: 1.0,
                    searchQuery: '',

                    get filteredVideos() {
                        if (!this.searchQuery.trim()) {
                            return this.videos;
                        }
                        const query = this.searchQuery.toLowerCase();
                        return this.videos.filter(v => v.title.toLowerCase().includes(query));
                    },

                    get currentVideo() {
                        return this.videos[this.currentIndex]?.url || '';
                    },
                    get currentTitle() {
                        return this.videos[this.currentIndex]?.title || '';
                    },

                    init() {
                        this.playCurrent();
                        this.$watch('searchQuery', (value) => {
                            if (value.trim() === '') {
                                this.scrollToActive();
                            }
                        });
                    },

                    handleEnded() {
                        this.repeatCount++;
                        if (this.repeatCount < 2) {
                            this.$refs.videoPlayer.currentTime = 0;
                            this.$refs.videoPlayer.play().catch(e => {
                                console.log('Playback blocked:', e);
                            });
                        } else {
                            this.repeatCount = 0;
                            if (this.autoPlayNext) {
                                this.nextVideo();
                            }
                        }
                    },

                    toggleAutoPlayNext() {
                        console.log('toggleAutoPlayNext');
                        this.autoPlayNext = !this.autoPlayNext;
                        if (!this.isPlaying) {
                            this.togglePlayPause();
                        }
                        this.playCurrent();
                    },

                    playCurrent() {
                        const player = this.$refs.videoPlayer;
                        const source = player.querySelector('source');
                        source.src = this.currentVideo;
                        player.load();

                        player.onloadedmetadata = () => {
                            player.playbackRate = this.playbackSpeed;
                            if (this.isPlaying) {
                                player.play().catch(e => {
                                    console.log('Playback blocked:', e);
                                });
                            }
                        };
                    },

                    nextVideo() {
                        this.currentIndex = (this.currentIndex + 1) % this.videos.length;
                        this.repeatCount = 0;
                        this.playCurrent();
                        this.scrollToActive();
                    },

                    setVideoByUrl(url) {

                        const index = this.videos.findIndex(video => video.url === url);

                        if (index !== -1) {

                            if (index === this.currentIndex) {
                                this.togglePlayPause();
                                return;
                            }
                            this.currentIndex = index;
                            this.repeatCount = 0;
                            this.playCurrent();
                            this.scrollToActive();
                        }
                    },

                    togglePlayPause() {
                        const player = this.$refs.videoPlayer;
                        if (player.paused) {
                            player.play().catch(e => {
                                console.log('Playback blocked:', e);
                            });
                            this.isPlaying = true;
                        } else {
                            player.pause();
                            this.isPlaying = false;
                        }
                    },

                setSpeed(speed) {
                            const player = this.$refs.videoPlayer;
                            this.playbackSpeed = speed;
                            player.playbackRate = speed;
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
