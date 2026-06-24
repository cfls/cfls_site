<x-layout>
    <x-slot name="title">Archives</x-slot>

    <section class="bg-white dark:bg-gray-900 mb-4">
        <div class="max-w-screen-2xl mx-auto px-4 py-12">

            @foreach ($features as $feature)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center py-12 border-b-2 border-gray-300 dark:border-gray-700">


                    <div class="lg:col-span-7 px-4">

                        <div class="mb-4">
        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                     bg-blue-100 text-blue-800
                     dark:bg-blue-900 dark:text-white">
            📅 {{ $feature->created_at->translatedFormat('d F Y') }}
        </span>
                        </div>

                        <h1 class="responsive-title mb-4 font-extrabold leading-tight tracking-tight text-gray-900 dark:text-white">
                            {{ $feature->title }}
                        </h1>

                        <hr class="border-gray-200 dark:border-gray-700 my-4">

                        <div class="responsive-text prose prose-p:my-1 prose-strong:text-gray-900 dark:prose-invert dark:prose-strong:text-white mb-6 font-light md:text-2xl text-gray-800 dark:text-white">
                            {!! $feature->description !!}
                        </div>

                    </div>

                    <div class="lg:col-span-5 flex justify-center px-4">
                        <img
                                src="{{ asset('storage/' . $feature->image) }}"
                                alt="{{ $feature->title }}"
                                class="w-full max-w-[500px] h-auto object-contain rounded-lg shadow-md"
                                loading="lazy"
                        >
                    </div>

                </div>
            @endforeach

            {{-- Paginación --}}
                <div class="mt-12 pt-8 flex justify-center">
                    {{ $features->withQueryString()->links() }}
                </div>

        </div>
    </section>
</x-layout>