<div
    x-data
    x-init="
    const updated = JSON.parse(localStorage.getItem('cart')) || [];
    $store.cart.items.splice(0, $store.cart.items.length, ...updated);
    window.addEventListener('cart-updated', () => {
        const updated = JSON.parse(localStorage.getItem('cart')) || [];
        $store.cart.items.splice(0, $store.cart.items.length, ...updated);
    });
    "
    @open-slide-over.window="
    isOpen = true;
    selectedProductId = $event.detail?.id;
    "
    @close="isOpen = false"
    class="w-full md:w-auto md:order-1"
    id="navbar-user"
>
    <div role="list"
         class="hidden lg:flex flex-wrap items-center justify-center font-medium text-2xl p-4 lg:p-0 mt-4 lg:mt-0 w-full box-border">
        <div role="listitem" class="px-4 py-2 min-w-[120px] text-center">
            <a wire:navigate href="/" class="{{ request()->routeIs('home') ? 'text-white' : 'text-black hover:text-gray-700' }}">Accueil</a>
        </div>
        <div role="listitem" class="px-4 py-2 min-w-[250px] text-center">
            <a wire:navigate href="{{ route('equipe') }}" class="{{ request()->routeIs('equipe') ? 'text-white' : 'text-black hover:text-gray-700' }}">Qui sommes-nous ?</a>
        </div>
        <div role="listitem" class="px-4 py-2 min-w-[120px] text-center">
            <a wire:navigate href="{{ route('formations.index') }}" class="{{ request()->routeIs('formations.index') ? 'text-white' : 'text-black hover:text-gray-700' }}">Formations</a>
        </div>
        <div role="listitem" x-data="{ open: false }" class="px-4 py-2 min-w-[120px] text-center relative">
            <a href="#" @click.prevent="open = !open"
               class="transition-colors duration-200 {{ request()->is('ressources/*') ? 'text-white' : 'text-black hover:text-gray-700' }}">
                Vidéos
            </a>
            <div x-show="open" x-cloak @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 role="list"
                 class="absolute top-full left-0 mt-2 w-60 bg-white rounded-lg shadow-lg dark:bg-gray-800 z-10"
            >
                @foreach($this->submenus as $submenu)
                    <div role="listitem">
                        <a wire:navigate href="{{ route('videos.slug', ['slug' => $submenu->slug]) }}"
                           class="block px-4 py-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 text-center">
                            {{ $submenu->name }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div role="listitem" x-data="{ open: false }" class="px-4 py-2 min-w-[120px] text-center relative">
            <a href="#" @click.prevent="open = !open"
               class="transition-colors duration-200 {{ request()->routeIs('resource.slug', 'telechargements-gratuits') ? 'text-white' : 'text-black hover:text-gray-700' }}">
                Ressources
            </a>
            <div x-show="open" x-cloak @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 role="list"
                 class="absolute top-full left-0 mt-2 w-60 bg-white rounded-lg shadow-lg dark:bg-gray-800 z-10"
            >

                    <div role="listitem">
                        <a wire:navigate href="{{route('resource.slug', ['slug' => 'mots-croises'])}}"
                           class="block px-4 py-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 text-center ">
                            Mots croisés
                        </a>
                    </div>
                   <div role="listitem">
                    <a wire:navigate href="{{route('telechargements-gratuits')}}"
                       class="block px-4 py-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 text-center">
                        Téléchargements gratuits
                    </a>
                 </div>

            </div>
        </div>
        <div role="listitem" class="px-4 py-2 min-w-[120px] text-center">
            <a wire:navigate href="{{route('dictionnaire.home')}}" class="{{ request()->routeIs('dictionnaire.home') ? 'text-white' : 'text-black hover:text-gray-700' }}">Dictionnaire LSFB</a>
        </div>

{{--        <div role="listitem" class="px-4 py-2 min-w-[180px] text-center">--}}
{{--            <a wire:navigate href="/ressources/mots-croises" class="{{ request()->is('ressources/mots-croises') ? 'text-white' : 'text-black hover:text-gray-700' }}">Mots croisés</a>--}}
{{--        </div>--}}
{{--        <div role="listitem" class="px-4 py-2 min-w-[120px] text-center">--}}
{{--            <a wire:navigate href="{{route('telechargements-gratuits')}}" class="{{ request()->is('telechargements-gratuits') ? 'text-white' : 'text-black hover:text-gray-700' }}">Téléchargements gratuits</a>--}}
{{--        </div>--}}
        <div role="listitem" class="px-4 py-2 min-w-[120px] text-center">
            <a wire:navigate href="{{ route('boutique.index') }}" class="{{ request()->routeIs('boutique.index') ? 'text-white' : 'text-black hover:text-gray-700' }}">Boutique</a>
        </div>
        <div role="listitem" class="px-4 py-2 min-w-[120px] text-center">
            <a wire:navigate href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-white' : 'text-black hover:text-gray-700' }}">Contact</a>
        </div>
        <div role="listitem" class="px-4 py-2  text-center">
            <!-- Botón del carrito -->
            <a @click="window.dispatchEvent(new CustomEvent('open-slide-over'))" class="relative cursor-pointer">

                <!-- Ícono del carrito -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                     class="w-8 h-8 text-black">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5
                  14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3
                  2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5
                  14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5
                  0 .75.75 0 0 1 1.5 0Z" />
                </svg>

                <!-- Círculo rojo con número -->
                <span
                    x-cloak
                    x-text="$store.cart.count"
                    x-show="$store.cart.count > 0"
                    class="absolute -top-1 -right-1 w-5 h-5 flex items-center justify-center text-xs font-bold text-white bg-red-600 rounded-full">
                </span>

            </a>
        </div>

    </div>
</div>
