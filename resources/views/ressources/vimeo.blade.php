<x-layout>
    <x-slot name="title">{!! $video->title !!}</x-slot>
    <section class="bg-white dark:bg-gray-900 mb-4">
        <div class="max-w-screen-2xl px-4 py-8 mx-auto space-y-12">
            <h1 class="flex justify-center mb-4 mt-8 text-3xl lg:text-5xl xl:text-6xl 2xl:text-7xl font-extrabold tracking-tight text-gray-900 dark:text-white text-center uppercase">
                {{ $video->title }}
            </h1>



            @php
                $url = $video->url_cloudinary;
                $path = parse_url($url, PHP_URL_PATH); // Extracts /dmhdsjmzf/video/upload/Les_Origines_De_La_F%C3%AAte_Nationale_n9uxje.mp4
                $filename = basename($path);            // Gets Les_Origines_De_La_F%C3%AAte_Nationale_n9uxje.mp4
                $publicIdEncoded = pathinfo($filename, PATHINFO_FILENAME); // Removes .mp4
                $publicId = urldecode($publicIdEncoded); // Decodes %C3%AA to ê, etc.
            @endphp


            <!-- Main Video Player -->
            <div class="flex justify-center">

                <iframe
                    src="https://player.cloudinary.com/embed/?cloud_name={{ config('services.cloudinary.cloud_name') }}&public_id={{ $publicId }}&profile=cld-default"
                    class="md:w-1/2 lg:w-1/2 w-full aspect-video"
                    allow="autoplay; fullscreen; encrypted-media; picture-in-picture"
                    allowfullscreen
                    frameborder="0"
                ></iframe>

            </div>
        </div>


        @livewire('video-carousel', ['vimeos' => $videos, 'categorySlug' => $category])


    </section>


</x-layout>
