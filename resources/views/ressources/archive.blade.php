<x-layout>
    <x-slot name="title">Archives</x-slot>
    <section class="bg-white dark:bg-gray-900 mb-4 px-4">

        <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-xl">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-800 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 w-10">#</th>
                    <th class="px-4 py-3 w-20">Photo</th>
                    <th class="px-4 py-3">Titre</th>
                    <th class="px-4 py-3 w-32">Date</th>
                    <th class="px-4 py-3 w-24">Statut</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($features as $feature)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">

                        <td class="px-4 py-3 text-gray-400 font-medium text-center">
                            {{ $loop->iteration + ($features->currentPage() - 1) * 10 }}
                        </td>

                        <td class="px-4 py-3">
                            <img src="{{ asset('storage/' . $feature->image) }}"
                                 alt="{{ $feature->title }}"
                                 data-src="{{ asset('storage/' . $feature->image) }}"
                                 data-title="{{ addslashes($feature->title) }}"
                                 class="modal-trigger w-24 h-24 object-cover rounded-md cursor-pointer hover:opacity-80 transition-opacity"/>
                        </td>

                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                            {{ $feature->title }}
                        </td>

                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                            {{ $feature->created_at->isoFormat('D MMM YYYY') }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                Archivé
                            </span>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                            Aucun élément archivé.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $features->links() }}
        </div>

    </section>

    {{-- Modal imagen --}}
    <div id="imageModal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="relative max-w-3xl w-full mx-4">

            <button id="modalClose"
                    class="absolute -top-4 -right-4 z-10 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-full w-9 h-9 flex items-center justify-center shadow hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                ✕
            </button>

            <img id="modalImage"
                 src=""
                 alt=""
                 class="w-full max-h-[80vh] object-contain rounded-xl"/>

            <p id="modalTitle"
               class="mt-3 text-center text-white text-sm font-medium"></p>
        </div>
    </div>

    {{-- Script directo, sin @push --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const modal     = document.getElementById('imageModal');
            const modalImg  = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalClose = document.getElementById('modalClose');

            // Abrir modal al hacer clic en cualquier miniatura
            document.querySelectorAll('.modal-trigger').forEach(function (img) {
                img.addEventListener('click', function () {
                    modalImg.src       = this.dataset.src;
                    modalImg.alt       = this.dataset.title;
                    modalTitle.textContent = this.dataset.title;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            // Cerrar con el botón ✕
            modalClose.addEventListener('click', closeModal);

            // Cerrar al hacer clic en el fondo
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });

            // Cerrar con Escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeModal();
            });

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modalImg.src = '';
            }
        });
    </script>

</x-layout>