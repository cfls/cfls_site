<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-br from-teal-500 to-purple-600 text-white pt-[var(--inset-top)]">
        <div class="max-w-4xl mx-auto px-4 py-8">
            <flux:subheading class="text-white text-xl text-center">
                Découvrez la LSFB grâce à LSFBGo — une application pensée pour l'accessibilité et l'inclusion.
            </flux:subheading>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="space-y-6">

            <!-- SELECT 1: SYLLABUS -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <label for="syllabus" class="block mb-3 text-sm font-semibold text-gray-900">
                    Sélectionnez un programme
                </label>
                <select
                        id="syllabus"
                        wire:change="ShowTheme($event.target.value)"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-3">
                    <option value="">Choisissez un programme</option>
                    @foreach($syllabus as $syllabu)
                        <option value="{{$syllabu->id}}">{{ $syllabu->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- SELECT 2: THEME -->
            @if($themes && count($themes) > 0)
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <label for="theme" class="block mb-3 text-sm font-semibold text-gray-900">
                        Sélectionnez un thème
                    </label>
                    <select
                            id="theme"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-3">
                        <option value="">Choisissez un thème</option>
                        @foreach($themes as $theme)
                            <option value="{{$theme->id}}">{{ $theme->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Título centrado -->
                <div class="text-center py-4">
                    <p class="text-lg font-medium text-gray-900">
                        Sélectionnez les types à modifier
                    </p>
                </div>

                <!-- SELECT 3: TYPE (Cards) -->
                @php
                    // Colores por tipo (clases completas para que Tailwind las detecte)
                    $cardStyles = [
                        'text'         => ['bg' => 'from-teal-400 to-emerald-600',  'text' => 'text-emerald-600', 'ring' => 'hover:ring-emerald-400'],
                        'choice'       => ['bg' => 'from-sky-400 to-indigo-600',    'text' => 'text-indigo-600',  'ring' => 'hover:ring-indigo-400'],
                        'yes-no'       => ['bg' => 'from-rose-400 to-pink-600',     'text' => 'text-pink-600',    'ring' => 'hover:ring-pink-400'],
                        'video-choice' => ['bg' => 'from-violet-400 to-purple-700', 'text' => 'text-purple-600',  'ring' => 'hover:ring-purple-400'],
                        'match'        => ['bg' => 'from-amber-400 to-orange-600',  'text' => 'text-orange-600',  'ring' => 'hover:ring-orange-400'],
                    ];
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featured as $demo)
                        @php $style = $cardStyles[$demo['type']] ?? $cardStyles['choice']; @endphp
                        <a href="#"
                           wire:key="demo-{{ $loop->index }}"
                           data-type="{{$demo['type']}}"
                           class="type-link group flex flex-col overflow-hidden rounded-2xl bg-white ring-1 ring-gray-200 {{ $style['ring'] }} shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                            <!-- Captura tipo teléfono asomando desde abajo -->
                            <div class="relative h-56 overflow-hidden bg-gradient-to-br {{ $style['bg'] }} flex justify-center pt-6">
                                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white/10"></div>
                                <div class="absolute -bottom-12 -left-8 w-28 h-28 rounded-full bg-white/10"></div>
                                <img
                                        src="{{ asset('img/lsfbgo/' . $demo['img']) }}"
                                        alt="{{ $demo['title'] }}"
                                        loading="lazy"
                                        class="relative w-36 self-start rounded-t-[1.25rem] border-[5px] border-b-0 border-gray-900 shadow-2xl transition-transform duration-300 group-hover:-translate-y-2"/>
                            </div>

                            <!-- Texto -->
                            <div class="flex flex-1 flex-col items-center p-5 text-center">
                                <h5 class="text-lg font-semibold text-gray-900">
                                    {{ $demo['title'] }}
                                </h5>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $demo['desc'] ?? '' }}
                                </p>
                                <span class="mt-auto pt-4 inline-flex items-center gap-1 text-sm font-medium {{ $style['text'] }}">
                                    Modifier
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('click', function(e) {
            const link = e.target.closest('.type-link');
            if (!link) return;

            e.preventDefault();

            const syllabusSelect = document.getElementById('syllabus');
            const themeSelect = document.getElementById('theme');
            const type = link.getAttribute('data-type');

            const syllabusValue = syllabusSelect?.value || '';
            const themeValue = themeSelect?.value || '';

            // Validaciones
            if (!syllabusValue) {
                alert('Veuillez d\'abord sélectionner un programme');
                syllabusSelect?.focus();
                return;
            }

            if (!themeValue) {
                alert('Veuillez d\'abord sélectionner un thème');
                themeSelect?.focus();
                return;
            }

            // Redirección
            window.location.href = `/admin-lsfbgo/${type}?syllabus=${syllabusValue}&theme=${themeValue}`;
        });
    </script>
@endpush