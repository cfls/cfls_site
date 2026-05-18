<x-layout>
    <x-slot name="title">Téléchargements gratuits</x-slot>

    <h1 class="flex justify-center mb-4 mt-8 text-3xl lg:text-5xl xl:text-6xl 2xl:text-7xl font-extrabold tracking-tight text-gray-900 dark:text-white text-center uppercase">
        Téléchargements gratuits
    </h1>

    <section class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 px-4 py-12 max-w-7xl mx-auto">
        @foreach($downloads as $download)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 w-full max-w-sm mx-auto">
                <div class="relative w-full aspect-[4/3] overflow-hidden">
                    <img class="absolute inset-0  rounded-t-lg" src="{{ asset('storage/'.$download->image) }}" alt="{{ $download->name }}" />
                </div>
                <div class="p-5 flex flex-col items-center justify-center">
                    <a download href="{{ asset('storage/'.$download->file_path) }}"
                       class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mt-4 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Télécharger
                    </a>
                </div>
            </div>
        @endforeach
    </section>
</x-layout>
