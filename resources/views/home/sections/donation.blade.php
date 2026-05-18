@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

@endpush

<section class="bg-white dark:bg-gray-900 mb-4" {{ $don->status ? '' : 'hidden' }}>
    <div class="max-w-screen-2xl px-4 py-12 mx-auto space-y-8">
        <!-- Responsive Row -->
        <div class="flex flex-col lg:grid lg:grid-cols-2 gap-8 xl:gap-12 items-center">
            <!-- Video Player -->
            <div class="order-1 lg:order-2 w-full"
                 x-data
                 :class="window.innerWidth >= 1024 ? 'wow animate__animated animate__backInRight' : ''"
                 data-wow-duration="1s"
                 data-wow-delay="0.2s">
                <div class="w-full rounded-lg shadow-lg overflow-hidden video-container">
                    <video
                        src="{{ $don->video_url }}"
                        controls
                        class="w-full h-auto aspect-video video-element"
                        preload="metadata"
                        aria-label="{{ $don->title }} video"
                    ></video>
                </div>
            </div>

            <!-- Description and Image -->
            <div class="order-2 lg:order-1 w-full px-4 "
                 x-data
                 :class="window.innerWidth >= 1024 ? 'wow animate__animated animate__backInLeft' : ''"
                 data-wow-duration="1s"
                 data-wow-delay="0.2s">>
                <h2 class="text-center uppercase font-extrabold tracking-tight text-gray-900 dark:text-white text-3xl sm:text-4xl lg:text-5xl xl:text-6xl 2xl:text-7xl">
                    {{ $don->title }}
                </h2>
                <hr class="border-b border-gray-200 dark:border-gray-700 my-4" />
                <div class="responsive-text prose prose-p:my-2 font-light md:text-2xl  text-gray-800 dark:text-white text-center">
                    {!! $don->description !!}
                </div>
                <div class="flex items-center justify-center mb-8">
                    <img
                        class="w-32 max-w-xs h-auto rounded-md image-element"
                        src="{{ asset('storage/' . $don->image) }}"
                        alt="{{ $don->title }}"
                        loading="lazy"
                    />
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        new WOW({
            boxClass: 'wow',
            animateClass: 'animate__animated',
            offset: 100,
            mobile: true,
            live: true
        }).init();
    </script>
@endpush
