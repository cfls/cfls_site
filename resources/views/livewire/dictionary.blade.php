<div class="flex flex-col w-full h-screen md:h-auto md:min-h-screen md:my-8 md:rounded-2xl md:shadow-xl overflow-hidden">
    <div class="bg-gradient-to-br from-teal-600 to-purple-600 text-white pt-[var(--inset-top)] md:pt-0 rounded-none md:rounded-t-2xl border-none">
        <div class="px-4 py-3 md:px-6 md:py-5">
            <div class="flex items-center gap-3">

                <flux:subheading size="xl" class="text-white text-lg md:text-2xl font-semibold">
                    {{ $title }}
                </flux:subheading>
            </div>
        </div>
    </div>

    <div class="flex flex-col flex-1 min-h-0 px-4 md:px-6 py-5 gap-5 mb-5 md:mb-0">

        {{-- Búsqueda --}}
        <flux:field>
            <flux:label class="sr-only" for="dict-search">
                Rechercher un mot dans le dictionnaire
            </flux:label>
            <flux:input
                    id="dict-search"
                    wire:model.debounce.300ms="search"
                    placeholder="Rechercher un mot…"
                    aria-label="Rechercher un mot dans le dictionnaire"
                    wire:keydown="$set('letter', 'tous')"
                    class="w-full text-black"
            >
                <x-slot name="iconTrailing">
                    @if($search)
                        <flux:button
                                size="sm"
                                variant="subtle"
                                icon="x-mark"
                                class="-mr-1"
                                wire:click="clearSearch"
                                aria-label="Effacer la recherche"
                        />
                    @else
                        <flux:icon icon="magnifying-glass" class="text-gray-400" aria-hidden="true" />
                    @endif
                </x-slot>
            </flux:input>
        </flux:field>

        {{-- Botón: sugerir una seña --}}
        <div class="flex justify-end">
            @auth
                <flux:button
                        size="sm"
                        variant="ghost"
                        icon="plus-circle"
                        wire:click="openSuggestModal"
                >
                    Vous ne trouvez pas un signe ?
                </flux:button>
            @else
                <a
                    href="{{ route('login') }}"
                    class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 transition"
                >
                    <flux:icon icon="plus-circle" class="w-4 h-4" />
                    Vous ne trouvez pas un signe ? <span class="underline underline-offset-2">Connectez-vous</span>
                </a>
            @endauth
        </div>

        {{-- Anuncio sr-only --}}
        <span class="sr-only" aria-live="polite" aria-atomic="true">
            @if($search || $letter !== 'tous')
                @if($items->total() === 0)
                    Aucun résultat trouvé
                @else
                    {{ $items->total() }} résultat(s) trouvé(s)
                @endif
            @endif
        </span>

        @php $letters = range('A', 'Z'); @endphp

        {{-- Filtro A–Z: scroll en móvil, wrap en desktop --}}
        <div class="flex justify-center">
            <div
                    role="group"
                    aria-label="Filtrer par lettre"
                    x-data="{
            isDown: false,
            startX: 0,
            scrollLeft: 0,
            dragged: false,
            startDrag(e) {
                this.isDown = true;
                this.dragged = false;
                this.startX = e.pageX;
                this.scrollLeft = this.$el.scrollLeft;
            },
            duringDrag(e) {
                if (!this.isDown) return;
                const walk = e.pageX - this.startX;
                if (Math.abs(walk) > 5) this.dragged = true;
                this.$el.scrollLeft = this.scrollLeft - walk;
            },
            stopDrag() {
                this.isDown = false;
            }
        }"
                    x-init="
            $el.querySelectorAll('button').forEach(btn => {
                btn.addEventListener('focus', () => {
                    btn.scrollIntoView({ inline: 'nearest', behavior: 'smooth', block: 'nearest' });
                });
            });
        "
                    x-ref="az"
                    @mousedown="startDrag($event)"
                    @mousemove.window="duringDrag($event)"
                    @mouseup.window="stopDrag()"
                    @mouseleave="stopDrag()"
                    @click.capture="if (dragged) { $event.preventDefault(); $event.stopPropagation(); }"
                    class="flex gap-2 overflow-x-auto md:overflow-x-visible md:flex-wrap whitespace-nowrap md:whitespace-normal no-scrollbar py-1 cursor-grab active:cursor-grabbing select-none md:cursor-auto"
            >
                @foreach ($letters as $ltr)
                    <button
                            type="button"
                            wire:click="setLetter('{{ $ltr }}')"
                            aria-pressed="{{ $letter === $ltr ? 'true' : 'false' }}"
                            class="px-3 py-1.5 rounded-full text-sm font-medium flex-shrink-0 md:flex-shrink transition
                {{ $letter === $ltr
                    ? 'bg-emerald-600 text-white dark:text-black'
                    : 'bg-gray-100 dark:bg-zinc-800   hover:bg-gray-200 dark:hover:bg-white' }}"
                    >
                        {{ $ltr }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Lista de resultados --}}
        <div class="flex-1 min-h-0 overflow-y-auto md:overflow-y-visible overscroll-contain no-scrollbar">
            @if ($items->count() === 0)
                <div class="text-center text-gray-500 py-12">
                    Aucun résultat trouvé
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pb-6">
                    @foreach ($items as $item)
                        <button
                                type="button"
                                wire:key="dict-item-{{ $item->id }}"
                                aria-label="Voir la vidéo : {{ $item->title }}"
                                class="flex items-center justify-between w-full text-left px-4 py-4
                                   bg-white dark:text-white border border-gray-200 rounded-xl shadow-sm
                                   dark:bg-zinc-800 dark:border-zinc-700
                                   hover:bg-gray-50 dark:hover:bg-zinc-700
                                   active:scale-[0.98] transition"
                                wire:click="$dispatch('openVideoModal', { id: {{ $item->id }} })"
                        >
                            <span class="font-medium text-base truncate">{{ $item->title }}</span>

                            <svg
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-gray-400 dark:text-zinc-400 flex-shrink-0"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="m12.75 15 3-3m0 0-3-3m3 3h-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    @endforeach
                </div>

                <div class="pb-6 overflow-x-auto">
                    {{ $items->onEachSide(1)->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- Modal: sugerir una seña --}}
    @if($showSuggestModal)
        <div
                x-data
                @keydown.escape.window="$wire.closeSuggestModal()"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
                role="dialog"
                aria-modal="true"
                aria-labelledby="suggest-modal-title"
        >
            <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl w-full max-w-md p-6 relative">

                <button
                        type="button"
                        wire:click="closeSuggestModal"
                        aria-label="Fermer"
                        class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                >
                    <flux:icon icon="x-mark" class="w-5 h-5" />
                </button>

                @if($suggestionSent)
                    <div class="text-center py-6">
                        <flux:icon icon="check-circle" class="w-12 h-12 text-emerald-600 mx-auto mb-3" />
                        <h3 class="text-lg font-semibold mb-1">Merci !</h3>
                        <p class="text-gray-500 text-sm mb-4">
                            Nous avons bien reçu votre suggestion et nous l'ajouterons prochainement.
                        </p>
                        <flux:button variant="primary" wire:click="closeSuggestModal">
                            Fermer
                        </flux:button>
                    </div>
                @else
                    <h3 id="suggest-modal-title" class="text-lg font-semibold mb-2">
                        Suggérer un signe
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Vous cherchez le signe d'un mot qui n'est pas dans le dictionnaire ? Indiquez-le ci-dessous, nous l'ajouterons prochainement.
                    </p>

                    <form wire:submit.prevent="submitSuggestion">
                        <flux:field>
                            <flux:label for="suggest-word">Mot souhaité</flux:label>
                            <flux:textarea
                                    id="suggest-word"
                                    wire:model="suggestWord"
                                    placeholder="Ex : anniversaire"
                                    rows="3"
                            />
                            <flux:error name="suggestWord" />
                        </flux:field>

                        <div class="flex justify-end gap-2 mt-4">
                            <flux:button type="button" variant="ghost" wire:click="closeSuggestModal">
                                Annuler
                            </flux:button>
                            <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="submitSuggestion">
                                <span wire:loading.remove wire:target="submitSuggestion">Envoyer</span>
                                <span wire:loading wire:target="submitSuggestion">Envoi…</span>
                            </flux:button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif

    @livewire('video-modal')
</div>