@php
$vimeos = $videos->map(function ($video) {
    return [
        'title' => $video->title,
        'videoId' => $video->code_vimeo,
        'slug' => $video->slug,
        'img' => $video->image ? asset('storage/'.$video->image) : " ",
    ];
})->all();
@endphp

<x-layout>

    <x-slot name="title">{!! $category->name !!}</x-slot>

    <h1 class="flex justify-center mb-4 mt-8 text-3xl lg:text-5xl xl:text-6xl 2xl:text-7xl font-extrabold tracking-tight text-gray-900 dark:text-white text-center uppercase">
        {{ $category->name }}
    </h1>

    <section class="flex justify-evenly flex-wrap gap-4 mt-8 py-12">
        @foreach ($vimeos as $video)

            <x-vimeo-thumbnail
                :title="$video['title']"
                :vimeo="$video['videoId']"
                :img="$video['img']"
                :video-slug="$video['slug']"
                :category="$category->slug"
            />
        @endforeach
    </section>
</x-layout>
