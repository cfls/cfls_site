<x-layout>
    <x-slot name="title">Interactif virtuel</x-slot>

    <h1 class="flex justify-center mb-4 mt-8 text-3xl lg:text-5xl xl:text-6xl 2xl:text-7xl font-extrabold tracking-tight text-gray-900 dark:text-white text-center uppercase">
        Interactif virtuel
    </h1>

    <section class="grid grid-cols-1 sm:grid-cols-2 gap-6 px-4 py-12 max-w-7xl mx-auto">
        {{-- Tarjeta 1 --}}
        <a href="{{ route('systeme-solaire') }}" target="_blank" class="block rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300 bg-white dark:bg-gray-800">
            <img src="{{ asset('img/interactives/sisteme-solaire.png') }}" alt="Le système solaire" class="w-full h-56 object-cover">
            <div class="p-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Le système solaire</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Exploration accessible avec vidéos en LSFB.</p>
            </div>
        </a>

        {{-- Tarjeta 2 --}}
        <a href="https://visit-lsfb.cfls.be" target="_blank" class="block rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300 bg-white dark:bg-gray-800">
            <img src="{{ asset('img/interactives/visit-lsfb.png') }}" alt="Découvrez la Belgique en LSFB" class="w-full h-56 object-cover">
            <div class="p-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Découvrez la Belgique en LSFB</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Carte interactive des provinces de Belgique.</p>
            </div>
        </a>
    </section>
</x-layout>