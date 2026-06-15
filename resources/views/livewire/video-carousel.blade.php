
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.5.x/dist/css/glide.core.min.css">
@endpush

<style>

</style>

<div
x-data="{
    glide: null,
    basePath: window.location.pathname.split('/').slice(0, 3).join('/'),
    storageKey: window.location.pathname,
    init() {
        this.glide = new Glide(this.$refs.glide, {
            perView: 4,
            breakpoints: {
                640: { perView: 1 },
            },
        });

        this.glide.mount();

        // Check if the stored basePath matches the current basePath
        const storedBasePath = localStorage.getItem('currentBasePath');

        if (storedBasePath !== this.basePath) {
            // If different, reset the glide position to zero and store the new basePath
            localStorage.setItem('glidePosition', 0);
            localStorage.setItem('currentBasePath', this.basePath);
            this.glide.go('=0'); // Reset the glide to the first slide
        } else {
            // Otherwise, restore the last saved position
            const savedIndex = localStorage.getItem('glidePosition');
            if (savedIndex !== null) {
                this.glide.go('=' + savedIndex);
            }
        }

        this.glide.on('run.after', () => {
            const bullets = this.$refs.glide.querySelectorAll('.glide__bullet');
            const activeIndex = Math.floor(this.glide.index / 4);
            bullets.forEach((bullet, index) => {
                bullet.classList.toggle('bg-csfl', index === activeIndex);
                bullet.classList.toggle('bg-gray-200', index !== activeIndex);
            });
            localStorage.setItem('glidePosition', this.glide.index);
        });

        // Agregar listener para limpiar localStorage al cambiar de página
        window.addEventListener('beforeunload', () => {
            localStorage.removeItem('currentBasePath'); // Borrar storedBasePath
            localStorage.removeItem('glidePosition');   // Opcional: borrar también la posición
        });
    }
}"
>
    <div x-ref="glide" class="glide block relative px-12 mt-10">
        <div class="glide__track" data-glide-el="track">
            <ul class="glide__slides">
                @foreach ($vimeos as $vimeo)
                <li class="glide__slide flex flex-col items-center justify-center pb-6">
                    <a wire:navigate href="{{ route('ressources.vimeo', ['category' => $vimeo->category->slug, 'slug' => $vimeo->slug]) }}"
                        @click="localStorage.setItem('glidePosition', glide.index)"
                        >
                    <img class="w-full" src="{{ asset('storage/'.$vimeo['image']) }}" alt="{{ $vimeo['title'] }}">
                    <div class="text-center mt-2">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $vimeo['title'] }}</h3>
                    </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="glide__arrows pointer-events-none absolute inset-0 flex items-center justify-between" data-glide-el="controls">
            <button
                class="glide__arrow glide__arrow--left pointer-events-auto disabled:opacity-50 rounded-lg border border-gray-200 p-1 inline-flex items-center justify-center"
                data-glide-dir="<"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                <span class="sr-only">Skip to previous slide page</span>
            </button>

            <button
                class="glide__arrow glide__arrow--right pointer-events-auto disabled:opacity-50 rounded-lg border border-gray-200 p-1 inline-flex items-center justify-center"
                data-glide-dir=">"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
                <span class="sr-only">Skip to next slide page</span>
            </button>
        </div>

        <div class="glide__bullets w-full items-center justify-center gap-1 hidden sm:flex" data-glide-el="controls[nav]">
            @for ($i = 0; $i < ceil(count($vimeos) / 4); $i++)
            <button
                class="glide__bullet h-3 w-3 rounded-full bg-gray-200 transition-colors hover:bg-csfl"
                data-glide-dir="={{ $i * 4 }}"
            ></button>
            @endfor
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.5.x"></script>
@endpush
