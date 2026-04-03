@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endpush

<section class="w-full min-h-screen bg-cover bg-center sm:bg-top bg-no-repeat px-6 py-12  bg-fond-two {{ $mission->status ? '' : 'hidden' }}">

    <div class="w-full max-w-screen-2xl px-4 md:px-8 mx-auto flex items-center justify-center min-h-screen wow animate__animated animate__backInLeft" data-wow-duration="1s" data-wow-delay="0.2s"">
        <div class="flex flex-col lg:grid lg:grid-cols-2 gap-8 xl:gap-16 items-center w-full mt-10">

           <div class="w-full rounded-lg shadow-lg overflow-hidden video-container">
               <video 
                   src="{{ $members->video_url }}"
                   controls
                   class="w-full h-auto aspect-video video-element"
                   preload="metadata"
                   aria-label="{{ $members->title }} video"
               ></video>

           </div>
            <div class="mt-10">
                <div class="ml-auto place-self-center lg:col-span-7">
                    <h1 class="text-center uppercase font-extrabold tracking-tight text-gray-900 md:text-2xl  mb-8
                    text-3xl sm:text-4xl lg:text-5xl xl:text-6xl 2xl:text-7xl">
                        {{ $members->title }}
                    </h1>
                    <div class="responsive-text prose prose-p:my-2 font-light md:text-2xl  text-gray-800 text-center members">
                        {!! $members->description !!}
                    </div>
                    <div class="flex items-center justify-center gap-4 mb-6">
                        <a
                            href="{{ asset('storage/' . $members->download) }}"
                            target="_blank"
                            class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-gray-900  rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 download-button transition-colors duration-200"
                            aria-label="Télécharger {{ $members->title }}"
                        >
                            Téléchargement
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 ml-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                            </svg>
                        </a>
                    </div>
                    <div class="flex items-center justify-center mb-8">
                        <img
                            class="size-1/2 max-w-xs h-auto rounded-md image-element"
                            src="{{ asset('storage/' . $members->image) }}"
                            alt="{{ $members->title }}"
                            loading="lazy"
                        />
                    </div>

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
