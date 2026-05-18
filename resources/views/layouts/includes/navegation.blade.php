<header class="w-full bg-[#84DBF0]">
    <div class="flex justify-between items-center px-4 py-2 max-w-7xl mx-auto">
    <!-- Logo -->
    <a wire:navigate href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
{{--        <img class="object-contain w-16 sm:w-20 h-auto" src="{{ asset('storage/' . $logo) }}" alt="CFLS Logo" />--}}
        <img class="object-contain md:w-32 w-28 h-auto " src="{{ asset('storage/' . $logo) }}" alt="CFLS Logo" />
    </a>

    <!-- Redes sociales + switch + login/logout -->
    <div class="flex items-center space-x-2 sm:space-x-4">
        <!-- Jetstream login/logout -->
        <div class="flex items-center space-x-2">
            @auth
                <!-- Usuario autenticado -->
                <div class="relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex text-sm bg-gray-800 rounded-full focus:ring-2 focus:ring-gray-300">
                                <img class="w-10 h-10 sm:w-10 sm:h-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="block px-4 py-2 text-sm text-gray-900 dark:text-white">
                                {{ Auth::user()->name }}
                            </div>
                            <div class="block px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ Auth::user()->email }}
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-700"></div>
                            <x-dropdown-link wire:navigate href="{{ route('profile.show') }}">
                                {{ __('Profil') }}
                            </x-dropdown-link>
                            <x-dropdown-link wire:navigate href="{{ route('syllabus') }}">
                                {{ __('Mon Syllabus') }}
                            </x-dropdown-link>
                            <x-dropdown-link wire:navigate href="{{ route('order.list') }}">
                                {{ __('Mes Commandes') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}"
                                                 @click.prevent="$root.closest('form').submit();">
                                    {{ __('Se déconnecter') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth
                @guest
                    <a href="{{ route('login') }}"
                       class="flex items-center gap-1 bg-cyan-400 hover:bg-cyan-500 text-gray-700 rounded px-2 py-1.5 sm:px-4 sm:py-2 transition-colors">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span class="hidden sm:inline text-sm whitespace-nowrap">Se connecter</span>
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="flex items-center gap-1 bg-cyan-400 hover:bg-cyan-500 text-gray-700 rounded px-2 py-1.5 sm:px-4 sm:py-2 transition-colors">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            <span class="hidden sm:inline text-sm whitespace-nowrap">S'inscrire</span>
                        </a>
                    @endif
                @endguest
        </div>

        <!-- Switch de tema -->
        <div>
            @livewire('theme-switch')
        </div>

        <!-- Redes sociales -->
        <div class="hidden sm:flex items-center space-x-2 sm:space-x-3">
            <a href="https://www.facebook.com/cfls.asbl" target="_blank" class="hover:opacity-80">
                <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" clip-rule="evenodd"></path>
                </svg>
            </a>
            <a href="https://www.instagram.com/cflsasbl" target="_blank" class="hover:opacity-80">
                <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z" clip-rule="evenodd"></path>
                </svg>
            </a>
            <a href="https://wa.me/1234567890" target="_blank" class="hover:opacity-80">
                <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.472-.148-.67.149-.197.297-.767.966-.94 1.164-.173.198-.347.223-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.447-.52.149-.174.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.669-1.611-.916-2.208-.242-.579-.487-.501-.669-.51l-.571-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.693.625.711.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.412.248-.694.248-1.288.173-1.412-.074-.124-.272-.198-.57-.347z"></path>
                    <path d="M12.004 2.004a9.998 9.998 0 0 0-8.708 14.721L2 22l5.42-1.29a9.998 9.998 0 1 0 4.583-18.706zm0 18.175a8.177 8.177 0 0 1-4.166-1.147l-.298-.174-3.208.764.857-3.13-.197-.323a8.176 8.176 0 1 1 7.012 3.978z"></path>
                </svg>
            </a>
        </div>
    </div>
    </div>

    <!-- NAV dentro del header -->
    <nav class="border-t border-gray-200 dark:border-gray-700 w-full">
        <div class="flex flex-wrap items-center justify-between lg:justify-center mx-auto px-4 py-2">
            <!-- Botón hamburguesa -->
            <div class="flex items-center ms-auto lg:hidden">
                <button
                    x-on:click="open = !open"
                    type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 17 14">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
            </div>

            <!-- Menú principal -->
            <div id="navbar-user" class="hidden lg:flex w-full justify-center mt-0">
                @livewire('navigation')
            </div>
        </div>
    </nav>
</header>
