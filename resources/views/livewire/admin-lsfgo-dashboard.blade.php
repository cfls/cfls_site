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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($featured as $demo)
                        <a href="#"
                           wire:key="demo-{{ $loop->index }}"
                           data-type="{{$demo['type']}}"
                           class="type-link group bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-lg hover:border-blue-500 transition-all duration-200 text-center">
                            <h5 class="text-xl font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                {{$demo['title']}}
                            </h5>
                            <img
                                    src="{{ asset('img/lsfbgo/' . $demo['img']) }}"
                                    alt="{{ $demo['title'] }}"
                                    class="w-full object-cover"/>

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