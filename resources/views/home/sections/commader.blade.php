@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.4.1/dist/css/glide.core.min.css">
@endpush


<section class="bg-white dark:bg-gray-900 mb-4">

    {{-- <x-specials.calendaravdient /> --}}
    <div class="glide max-w-screen-2xl px-4 pt-12 pb-8 mx-auto lg:pt-16" x-data x-init="initGlide($el)">
        <div class="glide__track" data-glide-el="track">
            <ul class="glide__slides">
                @foreach ($features as $feature)
                    <li class="glide__slide">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                            <div class="place-self-center lg:col-span-7 px-4">
                                <h1 class="responsive-title mb-4 font-extrabold leading-tight tracking-tight text-gray-900 dark:text-white">
                                    {{ $feature->title }}
                                </h1>
                                <hr class="border-b border-gray-200 dark:border-gray-700 my-4"/>
                                <div
                                    class="responsive-text prose prose-p:my-1 mb-6 font-light md:text-2xl  text-gray-800 dark:text-white">
                                    {!! $feature->description !!}
                                </div>
                                <div class="flex flex-col sm:flex-row sm:space-x-4 mt-4">
                                    <a href="{{ $feature->link }}"
                                       target="{{ $feature->target }}"
                                       class="inline-flex items-center justify-center px-5 py-3 text-sm font-medium text-white bg-[#008BCF] border border-gray-200 rounded-lg hover:bg-cyan-500 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-[#008BCF] dark:border-gray-600 dark:hover:bg-gray-700 transition-colors duration-200">
                                        {{ $feature->button_text }}
                                    </a>
                                </div>
                            </div>
                            <div class="lg:col-span-5 flex justify-center px-4">
                                <img src="{{ asset('storage/' . $feature->image) }}"
                                     alt="{{ $feature->title }}"
                                     class="w-full max-w-[500px] h-auto object-contain"
                                     loading="lazy"/>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Navigation Controls -->
        <div class="flex justify-center mt-6">
            <div class="glide__arrows" data-glide-el="controls">
                <button
                    class="glide__arrow glide__arrow--left bg-[#008BCF] hover:bg-cyan-500 p-3 rounded-full transition-colors duration-200"
                    data-glide-dir="<"
                    aria-label="Previous slide">
                    <i class="fas fa-chevron-left text-white"></i>
                </button>
                <button
                    class="glide__arrow glide__arrow--right bg-[#008BCF] hover:bg-cyan-500 p-3 rounded-full transition-colors duration-200"
                    data-glide-dir=">"
                    aria-label="Next slide">
                    <i class="fas fa-chevron-right text-white"></i>
                </button>
            </div>
        </div>
    </div>
</section>


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.4.1/dist/glide.min.js"></script>
    <script>
        function mountGlide(el) {
            new Glide(el, {
                type: 'carousel',
                perView: 1,
                focusAt: 'center',
                gap: 0,
                autoplay: 5000,
                hoverpause: true,
                breakpoints: {
                    640: {
                        gap: 10,
                        autoplay: 3000
                    }
                }
            }).mount();
        }

        function initGlide(el) {
            if (!el) return;
            if (el.classList.contains('glide-initialized')) return; // evita doble init
            if (window.Glide) {
                mountGlide(el);
                el.classList.add('glide-initialized');
            } else {
                // espera a que Glide esté disponible
                const interval = setInterval(() => {
                    if (window.Glide) {
                        mountGlide(el);
                        el.classList.add('glide-initialized');
                        clearInterval(interval);
                    }
                }, 50);
            }
        }

        // Re-montar cuando Livewire refresque el DOM
        document.addEventListener('livewire:navigated', () => {
            document.querySelectorAll('.glide').forEach(initGlide);
        });



    </script>
@endpush
