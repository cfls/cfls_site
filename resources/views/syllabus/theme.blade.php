@php
    $chunks = $themes->chunk(3); // Divide en filas de 3
@endphp

<x-layout>
    <x-slot name="title">Syllabus</x-slot>

    <div class="flex justify-center px-4 py-8">
        <h2 class="font-semibold text-gray-800 dark:text-gray-200 mb-5 text-3xl md:text-5xl lg:text-6xl uppercase text-center">
            {{ $syllabu->formatted_title }}
        </h2>
    </div>

    <div class="w-full max-w-screen-xl mx-auto px-4">
        @foreach($chunks as $chunk)
            <div class="grid grid-cols-1  md:grid-cols-3 gap-6 mb-6">
                @foreach($chunk as $index => $theme)

                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 flex flex-col items-center justify-center text-center p-6 h-auto">
                        <a href="{{ route('syllabus.theme', ['slug' => $slug, 'theme' => $theme->slug]) }}" class="flex flex-col items-center">
                            @if($theme->image)
                                <img src="{{ asset('storage/' . $theme->image) }}" alt="{{ $theme->title }}"
                                     class="mb-4 w-50 h-auto object-cover rounded-full border border-gray-300 dark:border-gray-600">
                            @else
                                <div class="mb-4 w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 dark:bg-gray-700">
                                    <span class="text-sm">Aucune image</span>
                                </div>
                            @endif
                            <h5 class="text-xl font-semibold text-gray-900 dark:text-white uppercase">
                                {{ $index + 1 }} - {{ $theme->title }}
                            </h5>
                        </a>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</x-layout>
