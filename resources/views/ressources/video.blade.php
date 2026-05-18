    @php
    $titles = [
        ['title' => '1er avril', 'videoId' => '827615267','img' => 'https://flowbite.com/docs/images/blog/image-1.jpg'],
        ['title' => '"Au soleil" - Guillaume Apollinaire', 'videoId' => '827615267', 'img' => 'https://flowbite.com/docs/images/blog/image-1.jpg'],
        ['title' => '"Marine" - Paul Verlaine', 'videoId' => '827615267','img' => 'https://flowbite.com/docs/images/blog/image-1.jpg'],
        ['title' => '"Marine" - Paul Verlaine', 'videoId' => '827615267','img' => 'https://flowbite.com/docs/images/blog/image-1.jpg'],
        ['title' => '"Le Corbeau Et Le Renard" - Jean De La Fontaine" - ', 'videoId' => '827615267', 'img' => 'https://flowbite.com/docs/images/blog/image-1.jpg'],
        ['title' => '"Printemps" - Victor Hugo', 'videoId' => '827615267', 'img' => 'https://flowbite.com/docs/images/blog/image-1.jpg'],
    ];
    @endphp
<x-layout>
    <x-slot name="title">Poésie et contes signés</x-slot>

    <h1 class="flex justify-center uppercase text-5xl font-bold dark:text-white">
        Poésie et contes signés dddd
    </h1>

    <section class="flex justify-evenly flex-wrap gap-4 mt-8">
        @foreach ($titles as $video)
            <x-vimeo-thumbnail :title="$video['title']" :vimeoId="$video['videoId']" :img="$video['img']"/>
        @endforeach
    </section>
</x-layout>