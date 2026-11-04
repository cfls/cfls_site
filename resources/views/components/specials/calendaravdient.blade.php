<section class="dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div
            x-data="adventCalendar(@js($videos))"
            x-init="init()"
            class="max-w-5xl mx-auto py-10"
    >

        <h1 class="text-4xl font-bold text-center mb-10">
            🎄 Calendrier de l’Avent
        </h1>

        <!-- GRID -->
        <div class="flex flex-wrap justify-center mt-8 gap-2 sm:gap-4 md:gap-6 pb-4 sm:pb-6">
            <template x-for="day in 25">
                <div
                        class="relative overflow-hidden cursor-pointer transition text-white font-semibold
                        border rounded-2xl flex flex-col items-center justify-center
                        aspect-square w-24 sm:w-28 md:w-32 lg:w-36 p-3 sm:p-4 md:p-5"
                        :class="isOpen(day)
                        ? 'bg-green-600 hover:bg-green-700 shadow-lg'
                        : 'bg-green-900 opacity-50 cursor-not-allowed'"
                        @click="openDay(day)"
                >

                    <!-- EFECTO BRILLANTE -->
                    <div class="absolute inset-0 bg-white opacity-0 hover:opacity-10 transition duration-300 mix-blend-overlay"></div>

                    <div class="text-3xl sm:text-4xl md:text-5xl mb-1 drop-shadow-lg">
                        <span x-text="icons[day]"></span>
                    </div>

                    <div class="text-base sm:text-lg md:text-xl drop-shadow-md">
                        Jour <span x-text="day"></span>
                    </div>

                    <template x-if="isOpen(day)">
                        <span class="text-yellow-300 text-xs md:text-sm drop-shadow-md mt-1">
                            Voir la vidéo
                        </span>
                    </template>

                    <template x-if="!isOpen(day)">
                        <span class="text-gray-300 text-sm mt-1">🔒</span>
                    </template>

                </div>
            </template>
        </div>


        <!-- MODAL -->
        <div
                x-show="selectedDay"
                x-transition.opacity
                class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                wire:cloak
        >

            <div
                    class="relative bg-gradient-to-b from-red-50 to-white dark:from-gray-800 dark:to-gray-900
                    w-full max-w-2xl p-6 rounded-2xl shadow-2xl border-4 border-yellow-400 animate-fadeIn"
            >

                <!-- DECORACIÓN SUPERIOR -->
                <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-5xl drop-shadow-lg">
                    🎄
                </div>

                <!-- CERRAR -->
                <div class="mt-4 flex justify-end">
                    <button
                            @click="closeModal()"
                            class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl text-lg font-semibold shadow-lg"
                    >
                        Fermer
                    </button>
                </div>

                <h2 class="text-3xl font-bold mb-4 text-center text-red-700 dark:text-red-300 drop-shadow-sm">
                    Jour <span x-text="selectedDay"></span>
                </h2>

                <!-- SI NO HAY VIDEO PUBLICADO -->
                <template x-if="!videoDisponible(selectedDay)">
                    <div class="w-full text-center py-10 text-xl text-gray-700 dark:text-gray-300">
                        ⏳ La vidéo n’est pas encore disponible. Veuillez réessayer plus tard.
                    </div>
                </template>

                <!-- SI HAY VIDEO DE FACEBOOK -->
                <template x-if="videoDisponible(selectedDay) && useFacebookVideo(selectedDay)">
                    <div class="w-full flex justify-center relative">

                        <!-- LOADER -->
                        <div
                                x-show="loading"
                                class="absolute inset-0 flex items-center justify-center bg-white/40 dark:bg-black/40 backdrop-blur-md rounded-xl z-20"
                        >
                            <div class="animate-spin rounded-full h-12 w-12 border-4 border-red-500 border-t-transparent"></div>
                        </div>

                        <!-- IFRAME FACEBOOK -->
                        <iframe
                                x-ref="fbframe"
                                class="h-[600px] w-[350px] rounded-xl shadow-lg"
                                :src="facebookEmbed(selectedDay)"
                                allowfullscreen
                                allow="autoplay; encrypted-media; picture-in-picture"
                        ></iframe>
                    </div>
                </template>

                <!-- SI HAY VIDEO DE INSTAGRAM -->
                <template x-if="videoDisponible(selectedDay) && useInstagramVideo(selectedDay)">
                    <div class="w-full flex justify-center relative">

                        <!-- LOADER -->
                        <div
                                x-show="loading"
                                class="absolute inset-0 flex items-center justify-center bg-white/40 dark:bg-black/40 backdrop-blur-md rounded-xl z-20"
                        >
                            <div class="animate-spin rounded-full h-12 w-12 border-4 border-purple-500 border-t-transparent"></div>
                        </div>

                        <!-- IFRAME INSTAGRAM -->
                        <iframe
                                x-ref="igframe"
                                class="h-[600px] w-[350px] rounded-xl shadow-lg"
                                :src="instagramEmbed(selectedDay)"
                                allowfullscreen
                        ></iframe>
                    </div>
                </template>




                <!-- DECORACIÓN -->
                <div class="text-center text-4xl mt-4 drop-shadow-md">
                    ✨⭐✨
                </div>

            </div>

        </div>

    </div>
</section>



@push('scripts')
    <script>
        function adventCalendar(videos = {}) {
            return {
                loading: false,
                selectedDay: null,
                videos: videos,   // vidéos publiées depuis Filament

                icons: {
                    1: "🎁", 2: "🎅", 3: "⛄", 4: "🌟", 5: "🍬",
                    6: "🧦", 7: "🕯️", 8: "❄️", 9: "🦌", 10: "🎄", 
                    11: "🔔", 12: "🍪", 13: "🪵", 14: "📯", 15: "🥁",
                    16: "🪅", 17: "📦", 18: "🍫", 19: "🎉", 20: "✨",
                    21: "🧸", 22: "🎀", 23: "🥨", 24: "🌙", 25: "⭐"
                },

                init() {
                    // ESC para cerrar
                    window.addEventListener("keydown", (e) => {
                        if (e.key === "Escape" && this.selectedDay !== null) {
                            this.closeModal();
                        }
                    });
                },

                /* DÍA DESBLOQUEADO = VIDEO PUBLICADO */
                isOpen(day) {
                    return !!this.videos[day];
                },

                /* ABRIR MODAL */
                openDay(day) {
                    if (this.isOpen(day)) {
                        this.selectedDay = day;
                        this.loading = true;   // ⏳ Mostrar loader

                        this.$nextTick(() => {
                            this.$refs.fbframe?.addEventListener("load", () => {
                                this.loading = false;   // 🎉 Video cargó, quitamos loader
                            });
                            this.$refs.igframe?.addEventListener("load", () => this.loading = false);
                        });

                        document.body.classList.add("overflow-hidden");
                        launchConfettiForDay(day);
                    }
                },

                /* DETECTAR FACEBOOK */
                useFacebookVideo(day) {
                    return this.videos[day]?.includes("facebook.com");
                },

                /* EMBED FACEBOOK */
                facebookEmbed(day) {
                    const url = this.videos[day];
                    return "https://www.facebook.com/plugins/video.php?href=" +
                        encodeURIComponent(url) +
                        "&show_text=0&autoplay=1";
                },
                useInstagramVideo(day) {
                    return this.videos[day]?.includes("instagram.com");
                },
                instagramEmbed(day) {
                    // Extrait le code du post (ignore "/" final et ?igsh=...)
                    const match = this.videos[day].match(/instagram\.com\/(?:reels?|p|tv)\/([A-Za-z0-9_-]+)/);
                    return `https://www.instagram.com/reel/${match?.[1]}/embed`;
                },
                videoDisponible(day) {
                    return !!this.videos[day];
                },

                /* CERRAR MODAL */
                closeModal() {
                    this.selectedDay = null;
                    document.body.classList.remove("overflow-hidden");
                },

            }
        }


        /* CONFETTI POR DÍA */
        const confettiByDay = {
            1: ["🎁","✨","⭐"],  2: ["🎅","✨"], 3: ["⛄","❄️"], 4: ["🌟","✨"],
            5: ["🍬","🍭"], 6: ["🍬","🍭","🎩"], 7: ["🕯️","✨"], 8: ["❄️","✨"],
            9: ["🦌","✨"], 10: ["🎄","⭐"], 11: ["🔔","✨"], 12: ["🍪","🍫"],
            13: ["🪵","🔥"], 14: ["📯","✨"], 15: ["🥁","✨"], 16: ["🪅","🎉"],
            17: ["📦","✨"], 18: ["🍫","🍬"], 19: ["🎉","✨"], 20: ["✨","⭐"],
            21: ["🧸","✨"], 22: ["🎀","✨"], 23: ["🥨","✨"],
            24: ["❄️","🌙","✨"], 25: ["✨","⭐","👶"],
        };

        function launchConfettiForDay(day) {
            const emojis = confettiByDay[day] ?? ["✨"];

            for (let i = 0; i < 25; i++) {
                const confetti = document.createElement("div");
                confetti.innerHTML = emojis[Math.floor(Math.random() * emojis.length)];

                confetti.style.position = "fixed";
                confetti.style.left = (Math.random() * 100) + "vw";
                confetti.style.top = "-20px";
                confetti.style.fontSize = (22 + Math.random() * 14) + "px";
                confetti.style.animation = "fall 1.6s linear forwards";
                confetti.style.zIndex = "99999";

                document.body.appendChild(confetti);
                setTimeout(() => confetti.remove(), 1600);
            }
        }

        document.head.insertAdjacentHTML("beforeend", `
        <style>
            @keyframes fall {
                to {
                    transform: translateY(120vh) rotate(360deg);
                    opacity: 0;
                }
            }
        </style>
    `);
    </script>
@endpush
