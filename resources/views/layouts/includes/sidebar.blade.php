{{-- menu for mobile --}}
<aside
    id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform duration-300 ease-in-out bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700 [@media(min-width:1450px)]:hidden"
    :class="{
        'translate-x-0': open,
        '-translate-x-full': !open,
    }"
    @open-slide-over.window="open = false"
    x-cloak
    aria-hidden="true"
    aria-label="Sidebar"
>
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            <li>
                <a href="/" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-csfl dark:hover:bg-gray-700 group">
                    <span class="ms-3">Accueil</span>
                </a>
            </li>
            <li>
                <a href="{{ route('equipe') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-csfl dark:hover:bg-gray-700 group">
                    <span class="ms-3">Qui sommes-nous ?</span>
                </a>
            </li>
            <li>
                <a href="{{ route('formations.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-csfl dark:hover:bg-gray-700 group">
                    <span class="ms-3">Formations</span>
                </a>
            </li>
            <li x-data="{ openVideos: false }">
                <button
                    @click="openVideos = !openVideos"
                    class="flex items-center justify-between w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-csfl dark:hover:bg-gray-700 group"
                >
                    <span class="ms-3">Vidéos</span>
                    <svg :class="{ 'rotate-90': openVideos }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <ul x-show="openVideos" x-collapse class="pl-6 mt-2 space-y-1">
                    @php
                        $categories = App\Models\Category::whereStatus(1)
                            ->whereType('video')
                            ->get();
                    @endphp
                    @foreach ($categories as $category)
                        <li>
                            <a href="{{ route('videos.slug', $category->slug) }}"
                               class="block p-2 text-gray-900 rounded-lg dark:text-white hover:bg-csfl dark:hover:bg-gray-700">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li x-data="{ openVideos: false }">
                <button
                        @click="openVideos = !openVideos"
                        class="flex items-center justify-between w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-csfl dark:hover:bg-gray-700 group"
                >
                    <span class="ms-3">Ressources</span>
                    <svg :class="{ 'rotate-90': openVideos }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <ul x-show="openVideos" x-collapse class="pl-6 mt-2 space-y-1">
                    <li>
                        <a href="{{route('resource.slug', ['slug' => 'mots-croises'])}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-csfl dark:hover:bg-gray-700 group">
                            <span class="ms-3">Mots croisés</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('telechargements-gratuits') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-csfl dark:hover:bg-gray-700 group">
                            <span class="ms-3">Téléchargements gratuits</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{route('dictionnaire.home')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-csfl dark:hover:bg-gray-700 group">
                    <span class="ms-3">Dictionnaire LSFB</span>
                </a>
            </li>

            <li>
                <a href="{{ route('boutique.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-csfl dark:hover:bg-gray-700 group">
                    <span class="ms-3">Boutique</span>
                </a>
            </li>
            <li>
                <a href="{{ route('contact') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-csfl dark:hover:bg-gray-700 group">
                    <span class="ms-3">Contact</span>
                </a>
            </li>
            <li>
                <a @click="window.dispatchEvent(new CustomEvent('open-slide-over'))"
                   class="flex items-center p-2 relative text-gray-900 rounded-lg dark:text-white hover:bg-csfl dark:hover:bg-gray-700 group cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218
                         c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25L5.106 5.272M6
                         20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>

                    <span x-cloak x-text="$store.cart.count" x-show="$store.cart.count > 0"
                          class="absolute -top-1 -right-1 inline-flex items-center justify-center
                                 px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                    </span>

                    <span class="ms-3">Mon panier</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<div
    x-show="open"
    x-on:click="open = false"
    class="fixed inset-0 z-30 bg-black bg-opacity-50 [@media(min-width:1450px)]:hidden"
    style="display: none"
></div>
