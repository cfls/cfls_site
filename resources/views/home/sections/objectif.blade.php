<section class="w-full min-h-screen bg-cover bg-center sm:bg-top bg-no-repeat px-6 py-12 text-white bg-fond-one {{ $mission->status ? '' : 'hidden' }}">

    <div class="w-full max-w-screen-2xl px-4 md:px-8 mx-auto flex items-center justify-center min-h-screen wow animate__animated animate__backInRight" data-wow-duration="1s" data-wow-delay="0.2s">
        <div class="flex flex-col lg:grid lg:grid-cols-2 gap-8 xl:gap-16 items-center w-full">
            <!-- Video y título -->
            <div class="w-full mb-12">
                <h2 class="text-center uppercase font-extrabold tracking-tight text-gray-900 md:text-2xl  mb-4
                    text-3xl sm:text-4xl lg:text-5xl xl:text-6xl 2xl:text-7xl">
                    {{ $mission->title }}
                </h2>

                <div class="w-full rounded-lg shadow-lg overflow-hidden">
                    <video
                        src="{{ $mission->video }}"
                        controls
                        class="w-full h-auto aspect-video video-element"
                        preload="metadata"
                        aria-label="{{ $mission->title }} video"
                    ></video>
                </div>
            </div>

            <!-- Objetivos -->
            <div class="w-full text-gray-700 sm:text-lg">
                <div x-data="{ active: 1 }" class="mx-auto w-full max-w-3xl min-h-[16rem]">
                    @foreach ($mission->objectives as $key => $objective)
                        <div x-data="{
                                id: {{ $key + 1 }},
                                get expanded() {
                                    return this.active === this.id
                                },
                                set expanded(value) {
                                    this.active = value ? this.id : null
                                },
                            }"
                             x-cloak
                             role="region"
                             class="border-b border-gray-800 pb-4 pt-4 first:pt-0 last:border-b-0 last:pb-0 transition-all">

                            <h2>
                                <button type="button"
                                        x-on:click="expanded = !expanded"
                                        :aria-expanded="expanded"
                                        class="group flex w-full items-center justify-between text-left font-bold text-gray-800  text-xl md:text-4xl">
                                    <span class="flex-1">{{ $objective->title }}</span>

                                    <!-- Iconos -->
                                    <svg x-show="expanded" x-cloak class="size-5 shrink-0" fill="currentColor"
                                         viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                    <svg x-show="!expanded" class="size-5 shrink-0" fill="currentColor"
                                         viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </h2>

                            <div x-show="expanded" x-collapse>
                                <div class="pt-2 text-gray-900 md:text-2xl  max-w-xl text-base sm:text-lg font-light">
                                    {{ $objective->description }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
