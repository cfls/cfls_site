<div class="space-y-4 min-h-screen">
    <div class="bg-gradient-to-br from-teal-500 to-purple-600 text-white pt-[var(--inset-top)] rounded-none border-none">
        <div class="px-4">
            <div class="p-2 inline-block">
                <flux:subheading class="text-white text-xl pb-4">
                    Découvrez la LSFB grâce à LSFBGo — une application pensée pour l'accessibilité et l'inclusion.
                </flux:subheading>
            </div>
        </div>
    </div>

    <div class="px-4 relative w-full">
        <div class="space-y-4">
            <!-- SELECT 1: SYLLABUS -->
            <div>
                <form class="max-w-sm mx-auto">
                    <label for="syllabus" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Sélectionnez un programme
                    </label>
                    <select
                            id="syllabus"
                            wire:change="ShowTheme($event.target.value)"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Choisissez un programme</option>
                        @foreach($syllabus as $syllabu)
                            <option value="{{$syllabu->id}}">{{ $syllabu->title }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- SELECT 2: THEME -->
            @if($themes && count($themes) > 0)
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="theme" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Sélectionnez un thème
                        </label>
                        <select
                                id="theme"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Choisissez un thème</option>
                            @foreach($themes as $theme)
                                <option value="{{$theme->id}}">{{ $theme->title }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="space-y-1">
                    <p class="text-base text-black leading-relaxed">
                        Sélectionnez les types à modifier
                    </p>
                </div>

                <!-- SELECT 3: TYPE -->
                <div class="space-y-4 -mx-4 mt-5">
                    <div class="flex gap-3 overflow-x-auto pb-4 scrollbar-hide pl-4 pr-4 my-10 snap-x snap-mandatory scroll-smooth">
                        @foreach($featured as $demo)
                            <a href="#"
                               wire:key="demo-{{ $loop->index }}"
                               data-type="{{$demo['type']}}"
                               class="type-link bg-neutral-primary-soft block max-w-sm p-6 border border-default rounded-base shadow-xs hover:bg-neutral-secondary-medium hover:shadow-lg cursor-pointer">
                                <h5 class="mb-3 text-2xl font-semibold tracking-tight text-heading leading-8 dark:text-white">
                                    {{$demo['title']}}
                                </h5>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('click', function(e) {
            if (e.target.closest('.type-link')) {
                e.preventDefault();

                const link = e.target.closest('.type-link');
                const syllabusSelect = document.getElementById('syllabus');
                const themeSelect = document.getElementById('theme');
                const type = link.getAttribute('data-type');

                const syllabusValue = syllabusSelect ? syllabusSelect.value : '';
                const themeValue = themeSelect ? themeSelect.value : '';

                console.log('Clicked!', {syllabusValue, themeValue, type});

                if (!syllabusValue) {
                    alert('Veuillez d\'abord sélectionner un programme');
                    return;
                }

                if (!themeValue) {
                    alert('Veuillez d\'abord sélectionner un thème');
                    return;
                }

                const url = `/admin-lsfbgo/${type}?syllabus=${syllabusValue}&theme=${themeValue}`;
                window.location.href = url;
            }
        });
    </script>
@endpush
