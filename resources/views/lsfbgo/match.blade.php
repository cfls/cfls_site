<x-app-layout>
    <a href="{{ route('admin-lsfbgo') }}" class="text-sm text-gray-700 underline">Retour</a>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Gestion des {{ ucfirst($type) }}</h1>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <p><strong>Programme:</strong> {{ $syllabus->title }}</p>
            <p><strong>Thème:</strong> {{ $theme->title }}</p>
            <p><strong>Type:</strong> {{ $type }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Questions</h2>

            @if($questions->count() > 0)
                <div class="space-y-4">
                    {{ $type }}
                </div>
            @else
                <p class="text-gray-500">Aucune question trouvée pour ce type.</p>
            @endif
        </div>
    </div>
</x-app-layout>
