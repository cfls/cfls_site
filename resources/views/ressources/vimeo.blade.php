<x-layout>
    <x-slot name="title">{!! $video->title !!}</x-slot>
    <section class="bg-white dark:bg-gray-900 mb-4">
        <div class="max-w-screen-2xl px-4 py-8 mx-auto space-y-12">
            <h1 class="flex justify-center mb-4 mt-8 text-3xl lg:text-5xl xl:text-6xl 2xl:text-7xl font-extrabold tracking-tight text-gray-900 dark:text-white text-center uppercase">
                {{ $video->title }}
            </h1>

            @php
                // Función para extraer el public_id de cualquier URL de Cloudinary
                $extractPublicId = function ($url) {
                    if (empty($url)) return null;
                    $path = parse_url($url, PHP_URL_PATH);
                    $filename = basename($path);
                    $publicIdEncoded = pathinfo($filename, PATHINFO_FILENAME);
                    return urldecode($publicIdEncoded);
                };

                // ¿Se pidió la versión subtitulada y existe?
                $useSubtitled = request('st') == 1 && !empty($video->url_cloudinary_subtitled);

                $urlToPlay = $useSubtitled ? $video->url_cloudinary_subtitled : $video->url_cloudinary;
                $publicId = $extractPublicId($urlToPlay);

                $hasSubtitled = !empty($video->url_cloudinary_subtitled);
            @endphp

            {{-- Toggle dentro de la página (solo si existe versión subtitulada) --}}
            @if($hasSubtitled)
                <div class="flex justify-center gap-2">
                    <a href="{{ url()->current() }}"
                       class="px-4 py-2 rounded-lg font-semibold transition
                              {{ !$useSubtitled ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                        Sans sous-titres
                    </a>
                    <a href="{{ url()->current() }}?st=1"
                       class="px-4 py-2 rounded-lg font-semibold transition
                              {{ $useSubtitled ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                        Avec sous-titres
                    </a>
                </div>
            @endif

            <!-- Main Video Player -->
            <div class="flex justify-center">
                <iframe
                        src="https://player.cloudinary.com/embed/?cloud_name={{ config('services.cloudinary.cloud_name') }}&public_id={{ urlencode($publicId) }}&profile=cld-default"
                        class="md:w-1/2 lg:w-1/2 w-full aspect-video"
                        allow="autoplay; fullscreen; encrypted-media; picture-in-picture"
                        allowfullscreen
                        frameborder="0"
                ></iframe>
            </div>
        </div>

        @livewire('video-carousel', [
                'categorySlug' => $category->slug,
                'useSubtitled' => $useSubtitled
            ])

    </section>
</x-layout>