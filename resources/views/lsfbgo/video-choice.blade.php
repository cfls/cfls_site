<x-app-layout>
    <!-- Modal de Éxito Personalizado -->
    <div id="success-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl transform transition-all">
                <div class="bg-gradient-to-r from-green-400 to-green-600 px-6 py-4 rounded-t-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-white">Succès!</h3>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <p id="success-message" class="text-gray-700"></p>
                </div>

                <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end">
                    <button
                            onclick="closeSuccessModal()"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensaje de éxito de sesión -->
    @if(session('success'))
        <div id="session-success-alert" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 transition-opacity duration-500">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm text-green-800 font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="closeSessionAlert()" class="text-green-600 hover:text-green-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <script>
            function closeSessionAlert() {
                const alert = document.getElementById('session-success-alert');
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }
            }

            setTimeout(() => {
                closeSessionAlert();
            }, 5000);
        </script>
    @endif

    <div class="min-h-screen bg-gray-50">
        <!-- Header con breadcrumb -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <a href="/admin-lsfbgo"
                   class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour au tableau de bord
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative inline-block group">
                <select
                        id="changeType"
                        class="appearance-none px-4 py-2 pr-10 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-pointer group-hover:shadow-md">
                    <option value="" disabled class="text-gray-400">Type de question</option>
                    <option value="text" {{ $type === 'text' ? 'selected' : '' }}>📝 Text</option>
                    <option value="choice" {{ $type === 'choice' ? 'selected' : '' }}>☑️ Choix</option>
                    <option value="yes-no" {{ $type === 'yes-no' ? 'selected' : '' }}>✅ Oui/Non</option>
                    <option value="video-choice" {{ $type === 'video-choice' ? 'selected' : '' }}>🎥 Vidéos</option>
                </select>

                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <!-- Título principal -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    Gestion des Choix de vidéo
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    Gérer et modifier les questions de type Choix de vidéo
                </p>
            </div>

            <!-- Card de información -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-sm border border-blue-100 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Programme</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $syllabus->title }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Thème</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $theme->title }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Type</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">Choix de vidéo</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Questions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-xl font-semibold text-gray-900">
                            Questions
                            <span class="ml-2 text-sm font-normal text-gray-500">
                                ({{ $questions->total() }})
                            </span>
                        </h2>

                        <!-- Formulario de búsqueda -->
                        <div class="flex items-center gap-3">
                            <form action="{{ route('admin-lsfbgo.show-questions', ['syllabu_id' => $syllabus->id, 'theme_id' => $theme->id, 'type' => $type]) }}"
                                  method="GET"
                                  class="flex items-center gap-2">
                                <div class="relative">
                                    <input
                                            type="text"
                                            name="search"
                                            value="{{ request('search') }}"
                                            placeholder="Rechercher par réponse..."
                                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm w-64"
                                    >
                                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>

                                @if(request('search'))
                                    <a href="{{ route('admin-lsfbgo.show-questions', ['syllabu_id' => $syllabus->id, 'theme_id' => $theme->id, 'type' => $type]) }}"
                                       class="px-3 py-2 text-sm text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                @endif

                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                                    Rechercher
                                </button>
                            </form>

                            <button id="open-create-modal" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Nouvelle question
                            </button>
                        </div>
                    </div>

                    <!-- Mostrar término de búsqueda si existe -->
                    @if(request('search'))
                        <div class="mt-3 flex items-center gap-2">
                            <span class="text-sm text-gray-600">
                                Résultats pour: <span class="font-semibold text-gray-900">"{{ request('search') }}"</span>
                            </span>
                            <span class="text-sm text-gray-500">
                                ({{ $questions->total() }} résultat{{ $questions->total() > 1 ? 's' : '' }})
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-6">
                    @if($questions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vidéo Question</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Réponse</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Options</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">

                                @foreach($questions as $question)
                                    @php
                                        $options = is_string($question->options)
                                            ? json_decode($question->options, true)
                                            : $question->options;
                                    @endphp

                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $loop->iteration + ($questions->currentPage() - 1) * $questions->perPage() }}
                                        </td>

                                        <!-- Columna de Video de la pregunta -->
                                        <td class="px-6 py-4">
                                            @if($question->video && $question->video->url)
                                                <div class="flex items-center space-x-3">
                                                    <div class="relative group cursor-pointer"
                                                         onclick="document.getElementById('video-thumb-{{ $question->id }}').play()">
                                                        <video
                                                                id="video-thumb-{{ $question->id }}"
                                                                class="h-24 w-40 rounded-lg object-cover bg-black shadow-sm hover:shadow-md transition-shadow"
                                                                preload="none"
                                                                muted>
                                                            <source src="{{ $question->video->url }}" type="video/mp4">
                                                        </video>
                                                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg pointer-events-none">
                                                            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">Vidéo #{{ $question->video_id }}</div>
                                                        <div class="text-xs text-gray-500">Cliquez pour lire</div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-20 w-32 bg-gray-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                    <span class="ml-3 text-sm text-gray-500">Pas de vidéo</span>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $question->answer ?? 'N/A' }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ is_array($options) ? count($options) : 0 }} options vidéo
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button
                                                        onclick="openModal({{ $question->id }})"
                                                        class="text-blue-600 hover:text-blue-900 transition"
                                                        title="Voir">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button
                                                        onclick="openEditModal({{ $question->id }})"
                                                        class="text-green-600 hover:text-green-900 transition"
                                                        title="Modifier">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <form action="{{ route('admin-lsfbgo.update-status-question', $question->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <button type="submit"
                                                            onclick="return confirm('{{ $question->status ? 'Désactiver' : 'Activer' }} cette question ?')"
                                                            class="{{ $question->status ? 'text-green-600 hover:text-green-900' : 'text-red-600 hover:text-red-900' }} transition"
                                                            title="{{ $question->status ? 'Désactiver' : 'Activer' }}">

                                                        @if($question->status)


                                                            Activer
                                                        @else
                                                            Désactiver
                                                        @endif
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $questions->appends(request()->query())->links() }}
                        </div>
                    @else
                        <!-- Estado vacío -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune question</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Commencez par créer une nouvelle question pour ce type.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MODALES -->
    @foreach($questions as $question)
        @php
            $options = is_string($question->options)
                ? json_decode($question->options, true)
                : $question->options;
        @endphp

                <!-- Modal de Visualización -->
        <div id="modal-{{ $question->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal({{ $question->id }})"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white">Question Vidéo #{{ $loop->iteration + ($questions->currentPage() - 1) * $questions->perPage() }}</h3>
                            <button onclick="closeModal({{ $question->id }})" class="text-white hover:text-gray-200 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white px-6 py-6">
                        @if($question->video && $question->video->url)
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Vidéo de la question</h4>
                                <div class="flex justify-center">
                                    <video controls class="w-40 h-64 rounded-lg object-cover bg-black shadow-lg" preload="metadata">
                                        <source src="{{ $question->video->url }}" type="video/mp4">
                                    </video>
                                </div>
                            </div>
                        @endif

                        @if($question->answer)
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Réponse correcte</h4>
                                <div class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-800 font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $question->answer }}
                                </div>
                            </div>
                        @endif

                        @if($options && is_array($options) && count($options) > 0)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-3">
                                    Options vidéo disponibles ({{ count($options) }})
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($options as $index => $option)
                                        <div class="bg-gray-50 rounded-lg p-4 border-2 {{ isset($option['value']) && $option['value'] == $question->answer ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                            <div class="space-y-3">
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-500 text-white font-bold text-sm flex-shrink-0">
                                                        {{ $index + 1 }}
                                                    </span>
                                                    @if(isset($option['value']))
                                                        <p class="text-sm font-medium text-gray-900 flex-1">
                                                            {{ $option['value'] }}
                                                            @if($option['value'] == $question->answer)
                                                                <span class="ml-2 text-green-600">✓</span>
                                                            @endif
                                                        </p>
                                                    @endif
                                                </div>

                                                @if(isset($option['video']))
                                                    <video controls class="w-full h-48 rounded-lg object-cover bg-black" preload="metadata">
                                                        <source src="{{ $option['video'] }}" type="video/mp4">
                                                    </video>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button onclick="closeModal({{ $question->id }})" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Edición -->
        <div id="edit-modal-{{ $question->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeEditModal({{ $question->id }})"></div>

                <div class="relative bg-white rounded-lg max-w-6xl w-full max-h-[90vh] overflow-y-auto">
                    <form action="{{ route('admin-lsfbgo.update-question', $question->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4 sticky top-0 z-10">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Modifier la question vidéo #{{ $loop->iteration + ($questions->currentPage() - 1) * $questions->perPage() }}
                                </h3>
                                <button type="button" onclick="closeEditModal({{ $question->id }})" class="text-white hover:text-gray-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="px-6 py-6 space-y-6">
                            <!-- Réponse correcta -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Réponse correcte
                                </label>
                                <input
                                        type="text"
                                        name="answer"
                                        value="{{ $question->answer }}"
                                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                                        required>
                            </div>

                            <!-- Options con video -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Options vidéo
                                </label>

                                <div id="options-container-{{ $question->id }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($options && is_array($options))
                                        @foreach($options as $index => $option)
                                            <div class="option-item bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                <div class="space-y-3">
                                                    <div class="flex items-center gap-2">
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-500 text-white font-bold text-sm flex-shrink-0">
                                                            {{ $index + 1 }}
                                                        </span>
                                                        <button
                                                                type="button"
                                                                onclick="this.closest('.option-item').remove(); updateOptionNumbers({{ $question->id }})"
                                                                class="ml-auto text-red-600 hover:text-red-800">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <input
                                                            type="text"
                                                            name="options[{{ $index }}][value]"
                                                            value="{{ $option['value'] ?? '' }}"
                                                            placeholder="Texte de l'option"
                                                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm"
                                                            required>
                                                    <input
                                                            type="url"
                                                            name="options[{{ $index }}][video]"
                                                            value="{{ $option['video'] ?? '' }}"
                                                            placeholder="URL de la vidéo"
                                                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm"
                                                            required>

                                                    @if(isset($option['video']))
                                                        <video controls class="max-w-md w-full h-auto rounded-lg bg-black shadow-lg" preload="metadata">
                                                            <source src="{{ $option['video'] }}" type="video/mp4">
                                                        </video>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <button
                                        type="button"
                                        onclick="addVideoOption({{ $question->id }})"
                                        class="mt-4 inline-flex items-center px-3 py-2 border border-purple-300 rounded-lg text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Ajouter une option vidéo
                                </button>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 sticky bottom-0">
                            <button
                                    type="button"
                                    onclick="closeEditModal({{ $question->id }})"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                                Annuler
                            </button>
                            <button
                                    type="submit"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
      <!-- Model Creacion -->
    <div id="create-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeCreateModal()"></div>

            <div class="relative bg-white rounded-lg max-w-6xl w-full max-h-[90vh] overflow-y-auto">
                <form action="{{ route('admin-lsfbgo.create-question') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="syllabu_id" value="{{ $syllabus->id }}">
                    <input type="hidden" name="theme_id" value="{{ $theme->id }}">
                    <input type="hidden" name="question_text" value="null">
                    <input type="hidden" name="video_url" value="null">


                    <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4 sticky top-0 z-10">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Créer une nouvelle question
                            </h3>
                            <button type="button" onclick="closeCreateModal()" class="text-white hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6 space-y-6">
                        <!-- Réponse correcte -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Réponse correcte
                            </label>
                            <input
                                    type="text"
                                    name="answer"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                                    required>
                        </div>

                        <!-- Options vidéo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Options vidéo
                            </label>

                            <div id="options-container-create" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Las opciones se agregarán dinámicamente aquí -->
                            </div>

                            <button
                                    type="button"
                                    onclick="addVideoOptionCreate()"
                                    class="mt-4 inline-flex items-center px-3 py-2 border border-purple-300 rounded-lg text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Ajouter une option vidéo
                            </button>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 sticky bottom-0">
                        <button
                                type="button"
                                onclick="closeCreateModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                            Annuler
                        </button>
                        <button
                                type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script>
        function toggleBodyScroll(disable = true) {
            document.body.style.overflow = disable ? 'hidden' : 'auto';
        }

        function toggleModal(id, show = true) {
            const modal = document.getElementById(id);
            if (!modal) return;

            modal.classList.toggle('hidden', !show);
            toggleBodyScroll(show);
        }

        function openModal(questionId) {
            document.getElementById('modal-' + questionId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(questionId) {
            document.getElementById('modal-' + questionId).classList.add('hidden');
            document.body.style.overflow = 'auto';

            const modal = document.getElementById('modal-' + questionId);
            const videos = modal.querySelectorAll('video');
            videos.forEach(video => {
                video.pause();
                video.currentTime = 0;
            });
        }
        function openCreateModal() {
            toggleModal('create-modal', true);
        }

        function closeCreateModal() {
            toggleModal('create-modal', false);
        }

        function openEditModal(questionId) {
            document.getElementById('edit-modal-' + questionId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal(questionId) {
            document.getElementById('edit-modal-' + questionId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function showSuccessModal(message) {
            const modal = document.getElementById('success-modal');
            const messageEl = document.getElementById('success-message');

            if (!modal || !messageEl) return;

            messageEl.textContent = message;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                closeSuccessModal();
            }, 3000);
        }

        function closeSuccessModal() {
            const modal = document.getElementById('success-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        function updateTableRow(questionId, newAnswer, optionsCount) {
            const editButton = document.querySelector(`button[onclick="openEditModal(${questionId})"]`);

            if (editButton) {
                const row = editButton.closest('tr');

                if (row) {
                    const cells = row.querySelectorAll('td');

                    // Actualizar respuesta (columna 3)
                    const answerCell = cells[2];
                    if (answerCell) {
                        const textDiv = answerCell.querySelector('div');
                        if (textDiv) {
                            textDiv.textContent = newAnswer;

                            answerCell.classList.add('bg-green-100');
                            setTimeout(() => {
                                answerCell.classList.remove('bg-green-100');
                            }, 2000);
                        }
                    }

                    // Actualizar contador de options (columna 4)
                    if (optionsCount !== undefined) {
                        const optionsCell = cells[3];
                        if (optionsCell) {
                            const badge = optionsCell.querySelector('span');
                            if (badge) {
                                badge.textContent = `${optionsCount} options vidéo`;
                            }
                        }
                    }
                }
            }
        }

        function addVideoOption(questionId) {
            const container = document.getElementById(`options-container-${questionId}`);
            const currentOptions = container.querySelectorAll('.option-item').length;
            const newIndex = currentOptions;

            const newOption = `
                <div class="option-item bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-500 text-white font-bold text-sm flex-shrink-0">
                                ${newIndex + 1}
                            </span>
                            <button
                                type="button"
                                onclick="this.closest('.option-item').remove(); updateOptionNumbers(${questionId})"
                                class="ml-auto text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        <input
                            type="text"
                            name="options[${newIndex}][value]"
                            placeholder="Texte de l'option"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm"
                            required>
                        <input
                            type="url"
                            name="options[${newIndex}][video]"
                            placeholder="URL de la vidéo"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm"
                            required>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', newOption);
            updateOptionNumbers(questionId);
        }

        function updateOptionNumbers(questionId) {
            const container = document.getElementById(`options-container-${questionId}`);
            const options = container.querySelectorAll('.option-item');

            options.forEach((option, index) => {
                const numberSpan = option.querySelector('span');
                if (numberSpan) {
                    numberSpan.textContent = index + 1;
                }
            });
        }


        // Agregar nuevo question
        let createOptionIndex = 0;

        function addVideoOptionCreate() {
            const container = document.getElementById('options-container-create');
            const optionHtml = `
        <div class="option-item bg-gray-50 rounded-lg p-4 border border-gray-200">
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-500 text-white font-bold text-sm flex-shrink-0">
                        ${createOptionIndex + 1}
                    </span>
                    <button
                        type="button"
                        onclick="this.closest('.option-item').remove(); updateCreateOptionNumbers()"
                        class="ml-auto text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
                <input
                    type="text"
                    name="options[${createOptionIndex}][value]"
                    placeholder="Texte de l'option"
                    class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm"
                    required>
                <input
                    type="url"
                    name="options[${createOptionIndex}][video]"
                    placeholder="URL de la vidéo"
                    class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm"
                    required>
            </div>
        </div>
    `;

            container.insertAdjacentHTML('beforeend', optionHtml);
            createOptionIndex++;
        }

        function updateCreateOptionNumbers() {
            const container = document.getElementById('options-container-create');
            const options = container.querySelectorAll('.option-item');

            options.forEach((option, index) => {
                const numberSpan = option.querySelector('span');
                if (numberSpan) {
                    numberSpan.textContent = index + 1;
                }

                const inputs = option.querySelectorAll('input');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        input.setAttribute('name', newName);
                    }
                });
            });

            createOptionIndex = options.length;
        }


        /// FIN

        // Manejo de formularios con AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form[action*="update-question"]');

            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const url = this.action;
                    const questionId = url.split('/').pop();
                    const submitButton = this.querySelector('button[type="submit"]');

                    submitButton.disabled = true;
                    submitButton.innerHTML = `
                        <svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Enregistrement...
                    `;

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': formData.get('_token')
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                closeEditModal(questionId);

                                const optionsCount = form.querySelectorAll('.option-item').length;
                                updateTableRow(questionId, formData.get('answer'), optionsCount);
                                showSuccessModal(data.message || 'Question mise à jour avec succès!');
                            } else {
                                alert('Erreur: ' + (data.message || 'Une erreur est survenue'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Une erreur est survenue lors de la mise à jour');
                        })
                        .finally(() => {
                            submitButton.disabled = false;
                            submitButton.innerHTML = `
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Enregistrer
                        `;
                        });
                });
            });

            const openBtn = document.getElementById('open-create-modal');
            if (openBtn) {
                openBtn.addEventListener('click', openCreateModal);
            }
        });

        document.getElementById('changeType').addEventListener('change', function () {
            const newType = this.value;
            if (!newType) return;

            const urlParams = new URLSearchParams(window.location.search);

            let syllabusId = urlParams.get('syllabus');
            let themeId = urlParams.get('theme');

            // Si no vienen por query string, los tomamos del path
            if (!syllabusId || !themeId) {
                const parts = window.location.pathname.split('/');
                syllabusId = parts[3] ?? null;
                themeId = parts[4] ?? null;
            }

            if (!syllabusId || !themeId) return;

            window.location.href =
                `/admin-lsfbgo/${newType}?syllabus=${syllabusId}&theme=${themeId}`;
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id^="modal-"]').forEach(modal => {
                    modal.classList.add('hidden');
                });
                document.querySelectorAll('[id^="edit-modal-"]').forEach(modal => {
                    modal.classList.add('hidden');
                });
                closeSuccessModal();
                document.body.style.overflow = 'auto';
            }
        });
    </script>
</x-app-layout>