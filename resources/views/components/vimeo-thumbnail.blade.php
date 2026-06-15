@props(['title', 'vimeo', 'hasSubtitled' => false, 'img', 'videoSlug', 'category'])

<div class="relative max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700"
     x-show="!avecSousTitres || {{ $hasSubtitled ? 'true' : 'false' }}">

    {{-- Badge CC: solo en videos con versión subtitulada --}}
    @if($hasSubtitled)
        <span x-show="avecSousTitres"
              class="absolute top-2 right-2 z-10 bg-blue-600 text-white text-xs font-semibold px-2 py-1 rounded shadow">
            CC
        </span>
    @endif

    <img class="rounded-t-lg" src="{{ $img }}"  />
    <div class="p-5">
        <h5 class="mb-2 md:text-2xl font-bold tracking-tight text-gray-900 dark:text-white text-center">
            {{ $title }}</h5>
        <div class="text-center mt-4">
            <a :href="avecSousTitres && {{ $hasSubtitled ? 'true' : 'false' }}
                        ? '{{ $category }}/{{ $videoSlug }}?st=1'
                        : '{{ $category }}/{{ $videoSlug }}'"
               href="{{ $category }}/{{ $videoSlug }}"
               class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </a>
        </div>
    </div>
</div>