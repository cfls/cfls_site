@php
    $vimeos = $videos->map(function ($video) {
        return [
            'title' => $video->title,
            'videoId' => $video->code_vimeo,
            'hasSubtitled' => !empty($video->url_cloudinary_subtitled),
            'slug' => $video->slug,
            'img' => $video->image ? asset('storage/'.$video->image) : " ",
        ];
    })->all();
@endphp

<x-layout>

    <x-slot name="title">{!! $category->name !!}</x-slot>

    <div x-data="{ avecSousTitres: false }">

        <h1 class="flex justify-center mb-4 mt-8 text-3xl lg:text-5xl xl:text-6xl 2xl:text-7xl font-extrabold tracking-tight text-gray-900 dark:text-white text-center uppercase">
            {{ $category->name }}
        </h1>

        {{-- Toggle global: inicia en "Sans sous-titres" (default) --}}
        <div class="flex justify-center mt-6">
            <label class="inline-flex items-center cursor-pointer gap-3">
        <span class="px-4 py-2 rounded-lg font-semibold transition"
              :class="!avecSousTitres ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200'">
            Sans sous-titres
        </span>
                <input type="checkbox" x-model="avecSousTitres" class="sr-only peer">
                <div class="relative w-11 h-6 bg-gray-300 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:bg-gray-700 peer-checked:bg-blue-600"></div>
                <span class="px-4 py-2 rounded-lg font-semibold transition"
                      :class="avecSousTitres ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200'">
            Avec sous-titres
        </span>
            </label>
        </div>

        <section class="flex justify-evenly flex-wrap gap-4 mt-8 py-12 text-white">
            @foreach ($vimeos as $video)

                <x-vimeo-thumbnail
                        :title="$video['title']"
                        :vimeo="$video['videoId']"
                        :has-subtitled="$video['hasSubtitled']"
                        :img="$video['img']"
                        :video-slug="$video['slug']"
                        :category="$category->slug"
                />
            @endforeach
        </section>

    </div>
</x-layout>